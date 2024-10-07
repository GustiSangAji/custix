<script setup lang="ts">
import { onMounted, ref } from "vue";
import * as Yup from "yup";
import ApiService from "@/core/services/ApiService";
import { toast } from "vue3-toastify";

// State untuk form stok-in
const stockIn = ref({
    id_ticket: '',
    quantity: 0,
});

// State untuk menyimpan daftar tiket yang diambil dari API
const tickets = ref([]);

// Schema validasi form
const formSchema = Yup.object().shape({
    id_ticket: Yup.string().required("ID Tiket harus diisi"),
    quantity: Yup.number().required("Jumlah harus diisi").min(1, "Jumlah minimal 1"),
});

// Fungsi untuk mengambil data tiket dari API
function fetchTickets() {
    ApiService.get("/tiket")
        .then(({ data }) => {
            tickets.value = data.tickets; // Simpan data tiket yang diambil
        })
        .catch((err) => {
            toast.error(err.response?.data?.message || "Gagal mengambil data tiket");
        });
}

// Fungsi submit form
function submitForm() {
    const formData = {
        id_ticket: stockIn.value.id_ticket,
        quantity: stockIn.value.quantity,
    };

    ApiService.post("/stok-in/store", formData)
        .then(() => {
            toast.success("Stok berhasil ditambahkan");
            resetForm();
        })
        .catch((err) => {
            toast.error(err.response?.data?.message || "Gagal menambah stok");
        });
}

// Fungsi reset form
function resetForm() {
    stockIn.value = {
        id_ticket: '',
        quantity: 0,
    };
}

// Ambil data tiket saat komponen di-mount
onMounted(() => {
    fetchTickets();
});
</script>

<template>
    <VForm
        class="form card mb-10"
        @submit="submitForm"
        :validation-schema="formSchema"
        id="form-stock-in"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">Tambah Stok Tiket</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <!-- Dropdown untuk memilih tiket -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">ID Tiket</label>
                        <Field
                            class="form-select form-select-solid"
                            as="select"
                            v-model="stockIn.id_ticket"
                            name="id_ticket"
                        >
                            <option value="" disabled>Pilih Tiket</option>
                            <option v-for="ticket in tickets" :key="ticket.id" :value="ticket.id">
                                {{ ticket.name }} - {{ ticket.place }}
                            </option>
                        </Field>
                        <div class="fv-help-block">
                            <ErrorMessage name="id_ticket" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Input jumlah stok -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Jumlah Stok</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="number"
                            name="quantity"
                            v-model="stockIn.quantity"
                            placeholder="Masukkan jumlah stok"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="quantity" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-primary btn-sm ms-auto">
                Simpan
            </button>
        </div>
    </VForm>
</template>

<style scoped>
/* Tambahkan styling sesuai kebutuhan */
</style>
