<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed, onBeforeUnmount } from "vue";
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
    id_tiket: '',
    name: '',
    place: '',
    datetime: '',
    status: 'available',
    quantity: 0,
    price: 0,
    image: null,
});

const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const formRef = ref();

const imagePreview = computed(() => {
    return ticket.value.image && ticket.value.image.length > 0
        ? URL.createObjectURL(ticket.value.image[0].file)
        : null;
});

onBeforeUnmount(() => {
    if (imagePreview.value) {
        URL.revokeObjectURL(imagePreview.value);
    }
});

const formSchema = Yup.object().shape({
    id_tiket: Yup.string().required("ID Tiket harus diisi"),  // Validasi untuk id_tiket
    name: Yup.string().required("Nama harus diisi"),
    place: Yup.string().required("Tempat harus diisi"),
    status: Yup.string().required("Status harus diisi"),
    quantity: Yup.number().required("Jumlah harus diisi").min(1, "Minimal 1 tiket"),
    price: Yup.number().required("Harga harus diisi").min(0, "Harga minimal 0"),
});

function getEdit() {
    block(document.getElementById("form-ticket"));
    ApiService.get(`/tiket/${props.selected}`)
        .then(({ data }) => {
            if (data && data.tiket) {
                ticket.value = data.tiket;
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
    formData.append("id_tiket", ticket.value.id_tiket);
    formData.append("name", ticket.value.name);
    formData.append("place", ticket.value.place);
    formData.append("datetime", ticket.value.datetime);
    formData.append("status", ticket.value.status);
    formData.append("quantity", ticket.value.quantity.toString());
    formData.append("price", ticket.value.price.toString());

    if (ticket.value?.image && Array.isArray(ticket.value.image)) {
        formData.append("image", ticket.value.image[0].file);
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
            ticket.value = {
                id_tiket: '',
                name: '',
                place: '',
                datetime: '',
                status: 'available',
                quantity: 0,
                price: 0,
                image: null,
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
                <!-- Input untuk ID Tiket -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">ID Tiket</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="id_tiket"
                            v-model="ticket.id_tiket"
                            placeholder="Masukkan ID Tiket"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="id_tiket" />
                        </div>
                    </div>
                </div>

                <!-- Input untuk Nama Tiket -->
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

                <!-- Input untuk Tempat -->
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

                <!-- Input untuk Tanggal & Waktu -->
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

                <!-- Input untuk Status -->
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

                <!-- Input untuk Jumlah Tiket -->
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

                <!-- Input untuk Harga Tiket -->
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

                <!-- Input untuk Gambar Tiket -->
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
      
      <!-- Pratinjau gambar yang di-upload -->
      <img
        v-if="ticket.image && ticket.image.length > 0"
        :src="imagePreview"
        alt="Gambar Tiket"
        class="img-thumbnail"
        style="max-width: 200px; max-height: 200px;"
      />
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

