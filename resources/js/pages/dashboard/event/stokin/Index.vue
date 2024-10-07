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

// Mengambil fungsi delete dari custom hook
const { delete: deleteStock } = useDelete({
  onSuccess: () => paginateRef.value.refetch(), // Refetch data setelah berhasil dihapus
});

// Kolom untuk tabel stok-in
const columns = [
  columnHelper.accessor("no", {
    header: "No", // Menampilkan nomor urut
  }),
  columnHelper.accessor("ticket_id", {
    header: "ID Tiket", // Menampilkan ID Tiket
  }),
  columnHelper.accessor("ticket_name", {
    header: "Nama Tiket", // Menampilkan nama tiket
  }),
  columnHelper.accessor("description", {
    header: "Deskripsi", // Menampilkan deskripsi
  }),
  columnHelper.accessor("added_at", {
    header: "Tanggal Penambahan", // Menampilkan tanggal penambahan stok
  }),
  columnHelper.accessor("amount", {
    header: "Jumlah Stok", // Menampilkan jumlah stok yang ditambahkan
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

// Fungsi untuk merefresh data di tabel
const refresh = () => paginateRef.value.refetch();
</script>

<template>
  <!-- Komponen Form untuk tambah/edit stok -->
  <Form
    :selected="selected"
    v-if="openForm"
    @close="openForm = false" 
    @refresh="refresh" />

  <div class="card">
    <div class="card-header align-items-center">
      <h2 class="mb-0">Daftar Stok Masuk</h2>
      <!-- Tombol Tambah -->
      <button
        type="button"
        class="btn btn-sm btn-primary ms-auto"
        v-if="!openForm"
        @click="openForm = true" 
      >
        Tambah Stok <i class="la la-plus"></i>
      </button>
    </div>
    <div class="card-body">
      <!-- Komponen paginate untuk menampilkan tabel stok -->
      <paginate
        ref="paginateRef"
        id="table-stokin"
        url="/stokin"
        :columns="columns"
      ></paginate>
    </div>
  </div>
</template>
