<script setup lang="ts">
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    stockin: Object,  // Properti untuk menerima data stok yang dipilih
});

const emit = defineEmits(["close"]);  // Emit event untuk menutup modal
</script>

<template>
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Stok Masuk</h5>
                    <button type="button" class="btn-close" @click="emit('close')"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Kode Tiket</th>
                            <td>{{ stockin.kode_tiket }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td>{{ stockin.jumlah }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ stockin.deskripsi }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Penambahan</th>
                            <td>{{ new Date(stockin.datetime).toLocaleString() }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="emit('close')">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-dialog {
    max-width: 80%;  /* Ubah sesuai preferensi */
}
.modal-backdrop {
    opacity: 0;
}
.dark-mode .modal-content {
    background-color: #555;
    color: #fff;
}
.dark-mode .table {
    background-color: #555;
    color: #333;
}
</style>

