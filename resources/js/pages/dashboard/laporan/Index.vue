<script setup lang="ts">
import { ref, watch, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { useDelete } from "@/libs/hooks";
import type { Cart } from "@/types"; 
import { formatRupiah } from "@/libs/rupiah";
import axios from 'axios';

const columnHelper = createColumnHelper<Cart>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");
const openForm = ref(false);
const isDownloading = ref(false); // State untuk mengatur status tombol download

const { delete: deleteCart } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const columns = [
    columnHelper.accessor("id", {
        header: "Kode Transaksi",
    }),
    columnHelper.accessor("ticket.name", {
        header: "Nama Tiket",
    }),
    columnHelper.accessor("jumlah_pemesanan", {
        header: "Jumlah Pemesanan",
    }),
    columnHelper.accessor("total_harga", {
        header: "Total Harga",
        cell: (cell) => formatRupiah(cell.getValue()),
    }),
    columnHelper.accessor("created_at", {
        header: "Tanggal Pembelian",
        cell: (cell) => {
            const dateValue = cell.getValue();
            return dateValue ? new Date(dateValue).toLocaleDateString() : "-";
        },
    }),
    columnHelper.accessor("status", {
        header: "Status",
        cell: (cell) =>
            h(
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
                        selected.value = cell.getValue();
                        openForm.value = true;
                    },
                }, h("i", { class: "la la-eye fs-2" })),
            ]),
    }),
];

const refresh = () => paginateRef.value.refetch();

const downloadExcel = async () => {
    if (isDownloading.value) return; // Cegah aksi dobel jika sedang mengunduh
    isDownloading.value = true; // Aktifkan status "sedang mengunduh"

    try {
        const response = await axios.get('laporan/export/excel', { responseType: 'blob' });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'Laporan.xlsx');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error("Gagal mengunduh laporan:", error);
        alert('Gagal mengunduh laporan, silakan coba lagi.');
    } finally {
        isDownloading.value = false; // Reset status setelah unduhan selesai
    }
};

watch(openForm, (newVal) => {
    if (!newVal) {
        selected.value = "";
    }
    window.scrollTo(0, 0);
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
            <button 
                class="btn btn-primary block-btn" 
                @click="downloadExcel" 
                :disabled="isDownloading" 
            >
                {{ isDownloading ? 'Mengunduh...' : 'Download Laporan Excel' }}
            </button>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-carts"
                url="/laporan"
                :columns="columns"
            />
        </div>
    </div>
</template>
