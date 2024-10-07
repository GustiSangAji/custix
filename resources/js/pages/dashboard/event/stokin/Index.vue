<script setup lang="ts">
import { ref, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { useDelete } from "@/libs/hooks"; // Hook untuk delete request
import Form from "./Form.vue"; // Import komponen Form
import type { StokIn } from "@/types"; // Definisikan tipe data untuk stok-in

const columnHelper = createColumnHelper<StokIn>();
const paginateRef = ref<any>(null);
const openForm = ref(false); // Kontrol visibilitas form
const selected = ref<string>(""); // Untuk menyimpan ID stok yang dipilih

const { delete: deleteStock } = useDelete({
  onSuccess: () => paginateRef.value.refetch(), // Refetch data setelah berhasil dihapus
});

// Kolom untuk tabel stok-in
const columns = [
  columnHelper.accessor("no", {
    header: "No", // Mengganti dari ID ke No
  }),
  columnHelper.accessor("tiket_id", {
    header: "ID Tiket", // Menambahkan kolom ID Tiket
  }),
  columnHelper.accessor("tiket_name", {
    header: "Produk Tiket",
  }),
  columnHelper.accessor("quantity", {
    header: "Jumlah",
  }),
  columnHelper.accessor("date", {
    header: "Tanggal Penambahan Stok",
  }),
  columnHelper.accessor("uuid", {
    header: "Aksi",
    cell: (cell) =>
      h("div", { class: "d-flex gap-2" }, [
        h("button", {
          class: "btn btn-sm btn-icon btn-info",
          onClick: () => {
            selected.value = cell.getValue(); // Set ID stok yang dipilih
            openForm.value = true; // Buka form untuk edit
          },
        }, h("i", { class: "la la-pencil fs-2" })),
        h("button", {
          class: "btn btn-sm btn-icon btn-danger",
          onClick: () => deleteStock(`/stok-in/${cell.getValue()}`),
        }, h("i", { class: "la la-trash fs-2" }))
      ]),
  }),
];

// Fungsi untuk refresh data di tabel
const refresh = () => paginateRef.value.refetch();
</script>

<template>
  <Form
    :selected="selected"
    v-if="openForm"
    @close="openForm = false" 
    @refresh="refresh" />

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
        id="table-stok-in"
        url="/stokin"
        :columns="columns"
      ></paginate>
    </div>
  </div>
</template>
