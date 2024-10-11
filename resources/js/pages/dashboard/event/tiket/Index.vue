<script setup lang="ts">
import { ref, watch, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue"; // Komponen Form Tiket
import type { Tiket } from "@/types";
import { formatRupiah } from "@/libs/rupiah";


const columnHelper = createColumnHelper<Tiket>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");  // Untuk menyimpan UUID tiket yang dipilih
const openForm = ref(false);  // Kontrol visibilitas form

const { delete: deleteTicket } = useDelete({
    onSuccess: () => paginateRef.value.refetch(), // Refetch data setelah berhasil dihapus
});

// Kolom-kolom untuk tabel tiket
const columns = [
    columnHelper.accessor("no", {
        header: "#",
    }),
    columnHelper.accessor("kode_tiket", {
        header: "Kode Tiket",
    }),
    columnHelper.accessor("name", {
        header: "Nama Tiket",
    }),
    columnHelper.accessor("place", {
        header: "Tempat",
    }),
    columnHelper.accessor("datetime", {
        header: "Tanggal & Waktu",
    }),
    columnHelper.accessor("expiry_date", { 
    header: "Masa Berlaku",
    cell: (cell) => {
        const dateValue = cell.getValue();
        return dateValue ? new Date(dateValue).toISOString().split('T')[0] : "-"; // Format YYYY-MM-DD
    },
    }),
    columnHelper.accessor("status", {
        header: "Status",
        cell: (cell) => h("span", cell.getValue() === "available" ? "Tersedia" : "Tidak Tersedia"),
    }),
    columnHelper.accessor("quantity", {
        header: "Jumlah",
    }),
    columnHelper.accessor("price", {
        header: "Harga",
        cell: (cell) => formatRupiah(cell.getValue()),
    }),
    columnHelper.accessor("image", {
        header: "Gambar",
        cell: (cell) => {
            const imagePath = cell.getValue();
            return h("img", { 
                src: `/storage/${imagePath}`, // Pastikan Anda menggunakan path yang benar
                alt: "Gambar", 
                style: { maxWidth: "100px", maxHeight: "100px" } 
            });
        },
    }),
    columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h("button", {
                    class: "btn btn-sm btn-icon btn-info",
                    onClick: () => {
                        selected.value = cell.getValue();  // Set UUID tiket yang dipilih
                        openForm.value = true;  // Buka form
                    },
                }, h("i", { class: "la la-pencil fs-2" })),
                h("button", {
                    class: "btn btn-sm btn-icon btn-danger",
                    onClick: () => deleteTicket(`/tiket/${cell.getValue()}`),
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
            <h2 class="mb-0">List Tiket</h2>
            <button
                type="button"
                class="btn btn-sm btn-primary ms-auto"
                v-if="!openForm"
                @click="openForm = true"
            >
                Tambah <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-tiket"
                url="/tiket"
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template>
