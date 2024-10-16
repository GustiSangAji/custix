<script setup lang="ts">
import { ref, watch, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { useDelete } from "@/libs/hooks";  // Hook untuk delete, sesuaikan dengan project Anda
import type { Cart } from "@/types";  // Pastikan Anda memiliki tipe Cart
import { formatRupiah } from "@/libs/rupiah";  // Fungsi format Rupiah

const columnHelper = createColumnHelper<Cart>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");  // Untuk menyimpan UUID cart yang dipilih
const openForm = ref(false);  // Kontrol visibilitas form

const { delete: deleteCart } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),  // Refetch data setelah berhasil dihapus
});

// Kolom-kolom untuk tabel carts
const columns = [
    columnHelper.accessor("id", {
        header: "Kode Transaksi",  // Mengubah header menjadi "ID Cart"
    }),
    columnHelper.accessor("ticket.name", {
        header: "Nama Tiket",
    }),
    columnHelper.accessor("jumlah_pemesanan", {
        header: "Jumlah Pemesanan",
    }),
    columnHelper.accessor("total_harga", {
        header: "Total Harga",
        cell: (cell) => formatRupiah(cell.getValue()),  // Format Rupiah
    }),
    columnHelper.accessor("created_at", {
        header: "Tanggal Pembelian",
        cell: (cell) => {
            const dateValue = cell.getValue();
            return dateValue ? new Date(dateValue).toLocaleDateString() : "-";  // Format tanggal
        },
    }),
    columnHelper.accessor("status", {
        header: "Status",
        cell: (cell) => h(
            "span", 
            { class: cell.getValue() === "Paid" ? "text-success" : "text-danger" },
            cell.getValue() === "Paid" ? "Sudah Dibayar" : "Belum Dibayar"
        ),
    }),
    columnHelper.accessor("id", {
        header: "Aksi",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h("button", {
                    class: "btn btn-sm btn-icon btn-info",
                    onClick: () => {
                        selected.value = cell.getValue();  // Set ID cart yang dipilih
                        openForm.value = true;  // Buka form
                    },
                }, h("i", { class: "la la-eye fs-2" })),  // Tombol detail
            ]),
    }),
];

// Fungsi untuk refresh data di tabel
const refresh = () => paginateRef.value.refetch();

// Watch perubahan openForm dan reset selected ketika form ditutup
watch(openForm, (newVal) => {
    if (!newVal) {
        selected.value = "";  // Reset ID saat form ditutup
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
            <h2 class="mb-0">Daftar Cart</h2>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-carts"
                url="/laporan"
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template>
