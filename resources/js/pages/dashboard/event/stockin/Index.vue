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
const selectedStockIn = ref<StockIn | null>(null);  // Data stok masuk yang dipilih
const openDetailModal = ref(false);  // Kontrol modal detail

const { delete: deleteStockIn } = useDelete({
    onSuccess: () => paginateRef.value.refetch(), // Refetch data setelah berhasil dihapus
});

// Definisi kolom untuk tabel stock-in
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
                // Tombol untuk membuka modal detail
                h("button", {
                    class: "btn btn-sm btn-icon btn-primary",
                    onClick: () => {
                        selectedStockIn.value = cell.row.original;  // Set data stok yang dipilih
                        openDetailModal.value = true;  // Buka modal
                    },
                }, h("i", { class: "la la-eye fs-2" })),

                // Tombol untuk menghapus data
                h("button", {
                    class: "btn btn-sm btn-icon btn-danger",
                    onClick: () => deleteStockIn(`/stockin/${cell.getValue()}`),
                }, h("i", { class: "la la-trash fs-2" })),
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
    <!-- Form untuk Tambah/Edit Stok -->
    <Form
        :selected="selected"
        v-if="openForm"
        @close="openForm = false"
        @refresh="refresh"
    />

    <!-- Modal Detail -->
    <div v-if="openDetailModal" class="custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Stok Masuk</h5>
                <button type="button" class="btn-close" @click="openDetailModal = false"></button>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <th>Kode Tiket</th>
                        <td>{{ selectedStockIn?.kode_tiket }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td>{{ selectedStockIn?.jumlah }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ selectedStockIn?.deskripsi }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Penambahan</th>
                        <td>{{ new Date(selectedStockIn?.datetime).toLocaleString() }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="openDetailModal = false">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Tabel List Stok Masuk -->
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

<style scoped>
/* Modal Container */
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 9999;
    padding: 20px;
}

/* Modal Content */
.modal-content {
    background-color: white;
    border-radius: 8px;
    width: 100%;
    max-width: 600px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
    overflow-y: auto;
    max-height: 90%;
    transition: all 0.3s ease;
}

/* Modal Header */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

/* Modal Title */
.modal-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: bold;
}

/* Modal Body */
.modal-body table {
    width: 100%;
    border-collapse: collapse;
}

.modal-body th,
.modal-body td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

.modal-body th {
    width: 40%;
    font-weight: 600;
}

/* Modal Footer */
.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

/* Close Button */
.btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

/* Dark Mode */
@media (prefers-color-scheme: dark) {
    .modal-content {
        background-color: #2a2a2a;
        color: white;
        box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
    }

    .modal-body th,
    .modal-body td {
        border-bottom: 1px solid #444;
    }

    .modal-header {
        border-bottom: 1px solid #444;
    }

    .btn-close {
        color: white;
    }

    .modal-footer {
        border-top: 1px solid #444;
    }
}

/* Responsive */
@media (max-width: 600px) {
    .modal-content {
        width: 100%;
        padding: 15px;
    }

    .modal-title {
        font-size: 1.25rem;
    }

    .modal-footer {
        flex-direction: column;
        gap: 10px;
    }

    .btn-close {
        font-size: 1.25rem;
    }
}
</style>
