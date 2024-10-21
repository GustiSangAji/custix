<script setup lang="ts">
import { ref, watch, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { useDelete } from "@/libs/hooks";
import type { Cart } from "@/types"; 
import { formatRupiah } from "@/libs/rupiah";
import axios from 'axios';

// Kolom tabel dan data pagination
const columnHelper = createColumnHelper<Cart>();
const paginateRef = ref<any>(null);
const selected = ref<string>(""); // Menyimpan data yang dipilih
const openForm = ref(false); // Kontrol untuk membuka form
const isDownloading = ref(false); // Status pengunduhan

// Hook delete cart
const { delete: deleteCart } = useDelete({
    onSuccess: () => paginateRef.value.refetch(), // Refresh data setelah delete berhasil
});

// Definisi kolom tabel
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
        cell: (cell) => formatRupiah(cell.getValue()), // Format Rupiah untuk total harga
    }),
    columnHelper.accessor("created_at", {
        header: "Tanggal Pembelian",
        cell: (cell) => {
            const dateValue = cell.getValue();
            return dateValue ? new Date(dateValue).toLocaleDateString() : "-"; // Format tanggal
        },
    }),
    columnHelper.accessor("status", {
        header: "Status",
        cell: (cell) =>
            h(
                "span", 
                { class: cell.getValue() === "Paid" ? "text-success" : "text-danger" },
                cell.getValue() === "Paid" ? "Sudah Dibayar" : "Belum Dibayar" // Status pembayaran
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
                }, h("i", { class: "la la-eye fs-2" })), // Tombol untuk melihat detail
            ]),
    }),
];

// Fungsi untuk me-refresh data
const refresh = () => paginateRef.value.refetch();

// Fungsi download laporan dalam format Excel
const downloadExcel = async () => {
    if (isDownloading.value) return;
    isDownloading.value = true;
    try {
        const response = await axios.get('/laporan/export/excel', { responseType: 'blob' });
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
        isDownloading.value = false;
    }
};

// Fungsi print laporan
const printLaporan = () => {
    window.print(); // Memunculkan dialog cetak browser
};

// Menangani perubahan form
watch(openForm, (newVal) => {
    if (!newVal) {
        selected.value = "";
    }
    window.scrollTo(0, 0); // Scroll ke atas ketika form ditutup
});
</script>

<template>
    <!-- Form untuk menampilkan detail Cart -->
    <Form
        :selected="selected"
        v-if="openForm"
        @close="openForm = false"
        @refresh="refresh"
    />

    <!-- Kartu yang menampilkan daftar Cart -->
    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Daftar Cart</h2>
            <!-- Tombol download Excel -->
            <button 
                class="btn btn-sm btn-primary block-btn" 
                @click="downloadExcel" 
                :disabled="isDownloading"
                style="background-color: #4CAF50; color: white;"
            >
                {{ isDownloading ? 'Mengunduh...' : 'Download Excel' }}
            </button>
            <!-- Tombol print laporan -->
            <button 
                class="btn btn-sm btn-secondary block-btn ml-2" 
                @click="printLaporan" 
                style="background-color: #FF5733; color: white;"
            >
                Cetak Laporan
            </button>
        </div>



        
        <!-- Tabel yang menampilkan daftar cart -->
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
