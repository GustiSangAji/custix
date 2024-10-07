<script setup lang="ts">
import { ref, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Orders } from "@/types/orders";
import { formatRupiah } from "@/libs/rupiah";

const columnHelper = createColumnHelper<Orders>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");

// Kolom-kolom untuk tabel order
const columns = [
    columnHelper.accessor("no", {
        header: "#",
    }), 
    columnHelper.accessor("total_price", {
        header: "Total Harga",
        cell: (cell) => formatRupiah(cell.getValue()),
    }),
    columnHelper.accessor("payment_status", {
        header: "Status Pembayaran",
        cell: (cell) => {
            const status = cell.getValue();
            let statusText = "";

            switch (status) {
                case 1:
                    statusText = "Menunggu Pembayaran";
                    break;
                case 2:
                    statusText = "Sudah Dibayar";
                    break;
                case 3:
                    statusText = "Kadaluarsa";
                    break;
                default:
                    statusText = "Status Tidak Diketahui";
            }

            return h("span", statusText);
        },
    }),
    columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h("button", {
                    class: "btn btn-sm btn-icon btn-warning",
                    onClick: () => {
                        console.log(`View details for order UUID: ${cell.getValue()}`);
                    },
                }, h("i", { class: "la la-eye fs-2" }))
            ]),
    }),
];

// Fungsi untuk refresh data di tabel
const refresh = () => paginateRef.value?.refetch();
</script>

<template>
  <div class="card">
    <div class="card-header align-items-center">
      <h2 class="mb-0">List Tiket</h2>
      <button
        type="button"
        class="btn btn-sm btn-primary ms-auto"
        @click="refresh"
      >
        Refresh <i class="la la-refresh"></i>
      </button>
    </div>
    <div class="card-body">
      <paginate
        ref="paginateRef"
        id="table-tiket"
        url="/orders"
        :columns="columns"
      ></paginate>
    </div>
  </div>
</template>
