<script setup lang="ts">
import { ref, watch, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { useDelete } from "@/libs/hooks";
import type { Laporan } from "@/types"; // Pastikan Anda memiliki tipe Laporan
import { formatRupiah } from "@/libs/rupiah";

const columnHelper = createColumnHelper<Laporan>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");  // Untuk menyimpan UUID laporan yang dipilih
const openForm = ref(false);  // Kontrol visibilitas form

const { delete: deleteReport } = useDelete({
    onSuccess: () => paginateRef.value.refetch(), // Refetch data setelah berhasil dihapus
});

// Kolom-kolom untuk tabel laporan
const columns = [
    columnHelper.accessor("email", {
        header: "Email",
    }),
    columnHelper.accessor("nama_tiket", {
        header: "Nama Tiket",
    }),
    columnHelper.accessor("jumlah", {
        header: "Jumlah",
    }),
    columnHelper.accessor("harga", {
        header: "Harga",
        cell: (cell) => formatRupiah(cell.getValue()),
    }),
    columnHelper.accessor("tanggal_pembelian", {
        header: "Tanggal Pembelian",
        cell: (cell) => {
            const dateValue = cell.getValue();
            return dateValue ? new Date(dateValue).toLocaleDateString() : "-"; // Format tanggal
        },
    }),
    columnHelper.accessor("status", {
        header: "Status",
        cell: (cell) => h("span", { class: cell.getValue() === "paid" ? "text-success" : "text-danger" }, cell.getValue() === "paid" ? "Sudah Dibayar" : "Belum Dibayar"),
    }),
    columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h("button", {
                    class: "btn btn-sm btn-icon btn-info",
                    onClick: () => {
                        selected.value = cell.getValue();  // Set UUID laporan yang dipilih
                        openForm.value = true;  // Buka form
                    },
                }, h("i", { class: "la la-eye fs-2" })), // Tombol detail
                h("button", {
                    class: "btn btn-sm btn-icon btn-danger",
                    onClick: () => deleteReport(`/laporan/${cell.getValue()}`),
                }, h("i", { class: "la la-trash fs-2" }))
            ]),
    }),
];

// Fungsi untuk refresh data di tabel
const refresh = () => paginateRef.value.refetch();

// Watch perubahan openForm dan reset selected ketika form ditutup
watch(openForm, (newVal) => {
    if (!newVal) {
        selected.value = "";  // Reset UUID saat form ditutup
    }
    window.scrollTo(0, 0);  // Scroll ke atas ketika form dibuka
});
</script>

<template>
    <Form
        :selected="selected"
        v-if="openForm"
        @close="openForm = false"
        @refresh="refresh"
    />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Laporan Pembelian Tiket</h2>
            <!-- Tombol tambah dihapus sesuai permintaan -->
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-laporan"
                url="/laporan"  
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template> 