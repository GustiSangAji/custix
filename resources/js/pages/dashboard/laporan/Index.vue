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
const isDownloadingExcel = ref(false); // Status pengunduhan Excel
const isDownloadingPDF = ref(false); // Status pengunduhan PDF

// Hook delete cart
const { delete: deleteCart } = useDelete({
    onSuccess: () => paginateRef.value.refetch(), // Refresh data setelah delete berhasil
});

    // Definisi kolom tabel
const columns = [
        columnHelper.accessor("id", {
            header: "No",
        }),
        columnHelper.accessor("order_id", {
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
     
    ];

    const refresh = () => paginateRef.value.refetch();

// Fungsi download laporan dalam format Excel
const downloadExcel = async () => {
    if (isDownloadingExcel.value) return;
    isDownloadingExcel.value = true;
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
        console.error("Gagal mengunduh laporan Excel:", error);
        alert('Gagal mengunduh laporan Excel, silakan coba lagi.');
    } finally {
        isDownloadingExcel.value = false;
    }
};

// Fungsi download laporan dalam format PDF
const downloadPDF = async () => {
    if (isDownloadingPDF.value) return;
    isDownloadingPDF.value = true;
    try {
        const response = await axios.get('/laporan/pdf', { responseType: 'blob' });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'Laporan.pdf');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error("Gagal mengunduh laporan PDF:", error);
        alert('Gagal mengunduh laporan PDF, silakan coba lagi.');
    } finally {
        isDownloadingPDF.value = false;
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
            <!-- Tombol download Excel dan PDF -->
            <div class="d-flex gap-2">
                <button 
                    class="btn btn-sm btn-primary block-btn" 
                    @click="downloadExcel" 
                    :disabled="isDownloadingExcel"
                    title="Download Excel"
                >
                    <i class="la la-file-excel"></i>
                </button>
                
                <button 
                    class="btn btn-sm btn-danger block-btn" 
                    @click="downloadPDF" 
                    :disabled="isDownloadingPDF"
                    title="Download PDF"
                >
                    <i class="la la-file-pdf"></i>
                </button>
            </div>
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
