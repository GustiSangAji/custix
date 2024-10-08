<script setup lang="ts">
import { ref, watch, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue"; // Komponen Form StockIn
import type { StockIn } from "@/types"; // Pastikan tipe StockIn sudah didefinisikan

const columnHelper = createColumnHelper<StockIn>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");  // Untuk menyimpan ID stock-in yang dipilih
const openForm = ref(false);  // Kontrol visibilitas form

const { delete: deleteStockIn } = useDelete({
    onSuccess: () => paginateRef.value.refetch(), // Refetch data setelah berhasil dihapus
});

// Kolom-kolom untuk tabel stockin
const columns = [
    columnHelper.accessor("id", {
            header: "#",
    }),
    columnHelper.accessor("kode_tiket", {
        header: "Kode Tiket",
    }),
    columnHelper.accessor("jumlah", {
        header: "Jumlah",
    }),
    columnHelper.accessor("deskripsi", {
        header: "Deskripsi",
    }),
    columnHelper.accessor("datetime", {
        header: "Tanggal Penambahan",
        cell: (cell) => new Date(cell.getValue()).toLocaleString(), // Format tanggal
    }),
    columnHelper.accessor("id", {
        header: "Aksi",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h("button", {
                    class: "btn btn-sm btn-icon btn-info",
                    onClick: () => {
                        selected.value = cell.getValue();  // Set ID stock-in yang dipilih
                        openForm.value = true;  // Buka form
                    },
                }, h("i", { class: "la la-pencil fs-2" })),
                h("button", {
                    class: "btn btn-sm btn-icon btn-danger",
                    onClick: () => deleteStockIn(`/stockin/${cell.getValue()}`),
                }, h("i", { class: "la la-trash fs-2" }))
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
            <h2 class="mb-0">List Stok Masuk</h2>
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
                id="table-stockin"
                url="/stockin"
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template>
