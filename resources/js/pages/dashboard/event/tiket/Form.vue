<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import ApiService from "@/core/services/ApiService";    
import Flatpickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

const ticket = ref({
    kode_tiket: '', 
    name: '',
    place: '',
    datetime: '',
    expiry_date: '',
    status: 'available',
    quantity: 0,
    price: 0,
    image: null,
    description: '', // Tambahkan kolom deskripsi
    banner: null, // Tambahkan kolom banner
});
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const formRef = ref();

const formSchema = Yup.object().shape({
    kode_tiket: Yup.string().required("ID Tiket harus diisi"), 
    name: Yup.string().required("Nama harus diisi"),
    place: Yup.string().required("Tempat harus diisi"),
    status: Yup.string().required("Status harus diisi"),
    quantity: Yup.number().required("Jumlah harus diisi").min(1, "Minimal 1 tiket"),
    price: Yup.number().required("Harga harus diisi").min(0, "Harga minimal 0"),
    expiry_date: Yup.date().nullable(),
});

function getEdit() {
    block(document.getElementById("form-ticket"));
    ApiService.get(`/tiket/${props.selected}`)
        .then(({ data }) => {
            if (data && data.tiket) {
                ticket.value = data.tiket; // Pastikan response berisi data.tiket
                ticket.value.expiry_date = data.tiket.expiry_date;
            } else {
                console.error("Response data format is incorrect", data);
            }
        })
        .catch((err) => {
            toast.error(err.response?.data?.message || "Error fetching data");
        })
        .finally(() => {
            unblock(document.getElementById("form-ticket"));
        });
}

function submit() {
    const formData = new FormData();
    formData.append("kode_tiket", ticket.value.kode_tiket);
    formData.append("name", ticket.value.name);
    formData.append("place", ticket.value.place);
    formData.append("datetime", ticket.value.datetime);
    formData.append("expiry_date", ticket.value.expiry_date);
    formData.append("status", ticket.value.status);
    formData.append("quantity", ticket.value.quantity.toString());
    formData.append("price", ticket.value.price.toString());
    formData.append("description", ticket.value.description); // Tambahkan deskripsi ke FormData
    if (ticket.value?.image && Array.isArray(ticket.value.image)) {
        formData.append("image", ticket.value.image[0].file); // Pastikan ini sesuai dengan struktur file
    }
    if (ticket.value?.banner && Array.isArray(ticket.value.banner)) {
        formData.append("banner", ticket.value.banner[0].file); // Pastikan ini sesuai dengan struktur file
    }

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-ticket"));

    axios.post(props.selected ? `/tiket/${props.selected}` : '/tiket/store', formData, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    })
        .then(() => {
            emit("close");
            emit("refresh");
            toast.success("Data berhasil disimpan");
            formRef.value.resetForm();
        })
        .catch((err: any) => {
            formRef.value.setErrors(err.response.data.errors);
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-ticket"));
        });
}

onMounted(() => {
    if (props.selected) {
        getEdit();
    }
});

watch(
    () => props.selected,
    (newVal) => {
        if (newVal) {
            getEdit();
        } else {
            // Reset form untuk mode tambah
            ticket.value = {
                kode_tiket: '', // Reset kode_tiket
                name: '',
                place: '',
                datetime: '',
                expiry_date: '',
                status: 'available',
                quantity: 0,
                price: 0,
                image: null,
                description: '', // Reset deskripsi
                banner: null, // Reset banner
            };
            formRef.value.resetForm();
        }
    }
);
</script>

<template>
    <VForm
        class="form card mb-10"
        @submit="submit"
        :validation-schema="formSchema"
        id="form-ticket"
        ref="formRef"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Tiket</h2>
            <button
                type="button"
                class="btn btn-sm btn-light-danger ms-auto"
                @click="emit('close')"
            >
                Batal
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">ID Tiket</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="kode_tiket"
                            v-model="ticket.kode_tiket"
                            placeholder="Masukkan ID Tiket"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="kode_tiket" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Nama Tiket</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="name"
                            v-model="ticket.name"
                            placeholder="Masukkan Nama Tiket"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="name" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Tempat</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="place"
                            v-model="ticket.place"
                            placeholder="Masukkan Tempat"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="place" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Tanggal & Waktu</label>
                        <Flatpickr
                            class="form-control form-control-lg form-control-solid"
                            v-model="ticket.datetime"
                            :config="{ enableTime: true, dateFormat: 'Y-m-d H:i' }"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="datetime" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Masa Berlaku</label>
                        <Flatpickr
                            class="form-control form-control-lg form-control-solid"
                            v-model="ticket.expiry_date"
                            :config="{ dateFormat: 'Y-m-d' }"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="expiry_date" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Status</label>
                        <Field
                            class="form-select form-select-solid"
                            as="select"
                            v-model="ticket.status"
                            name="status"
                        >
                            <option value="available">Tersedia</option>
                            <option value="unavailable">Tidak Tersedia</option>
                        </Field>
                        <div class="fv-help-block">
                            <ErrorMessage name="status" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Jumlah Tiket</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="number"
                            name="quantity"
                            v-model="ticket.quantity"
                            placeholder="Masukkan Jumlah Tiket"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="quantity" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Harga Tiket</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="number"
                            name="price"
                            v-model="ticket.price"
                            placeholder="Masukkan Harga Tiket"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="price" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Deskripsi Tiket</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="description"
                            v-model="ticket.description"
                            placeholder="Masukkan Deskripsi Tiket"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="description" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Banner Tiket</label>
                        <file-upload
                            :files="ticket.banner" 
                            :accepted-file-types="fileTypes" 
                            required
                            v-on:updatefiles="(file) => (ticket.banner = file)" 
                        ></file-upload>
                        <div class="fv-help-block">
                            <ErrorMessage name="banner" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Gambar Tiket</label>
                        <file-upload
                            :files="ticket.image" 
                            :accepted-file-types="fileTypes" 
                            required
                            v-on:updatefiles="(file) => (ticket.image = file)" 
                        ></file-upload>
                        <div class="fv-help-block">
                            <ErrorMessage name="image" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-primary btn-sm ms-auto">
                {{ selected ? "Update" : "Simpan" }}
            </button>
        </div>
    </VForm>
</template>
