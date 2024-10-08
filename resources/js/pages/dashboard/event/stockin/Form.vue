<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import Flatpickr from 'vue-flatpickr-component'; // Import Flatpickr
import 'flatpickr/dist/flatpickr.css'; // Flatpickr CSS

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

const stockIn = ref({
    kode_tiket: '',  // Unique code for the stock entry
    jumlah: 0,
    deskripsi: '',
    datetime: '',
});

const formRef = ref();

const formSchema = Yup.object().shape({
    kode_tiket: Yup.string().required("Kode Tiket harus diisi"),
    jumlah: Yup.number().required("Jumlah harus diisi").min(1, "Minimal 1 item"),
    deskripsi: Yup.string().required("Deskripsi harus diisi"),
    //datetime: Yup.string().required("Tanggal penambahan harus diisi"), // Validasi datetime
});

function getEdit() {
    block(document.getElementById("form-stockin"));
    axios.get(`/stockin/${props.selected}`)
        .then(({ data }) => {
            stockIn.value = data;
        })
        .catch((err) => {
            toast.error(err.response?.data?.message || "Error fetching data");
        })
        .finally(() => {
            unblock(document.getElementById("form-stockin"));
        });
}

function submit() {
    const formData = new FormData();
    formData.append("kode_tiket", stockIn.value.kode_tiket);
    formData.append("jumlah", stockIn.value.jumlah.toString());
    formData.append("deskripsi", stockIn.value.deskripsi);
    formData.append("datetime", stockIn.value.datetime);

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-stockin"));

    axios.post(props.selected ? `/stockin/${props.selected}` : '/stockin/store', formData)
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
            unblock(document.getElementById("form-stockin"));
        });
}

onMounted(() => {
    if (props.selected) {
        getEdit();
    }
});

watch(() => props.selected, (newVal) => {
    if (newVal) {
        getEdit();
    } else {
        stockIn.value = {
            kode_tiket: '',
            jumlah: 0,
            deskripsi: '',
            datetime: '',
        };
        formRef.value.resetForm();
    }
});
</script>

<template>
    <VForm
        class="form card mb-10"
        @submit="submit"
        :validation-schema="formSchema"
        id="form-stockin"
        ref="formRef"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Stok Masuk</h2>
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
                        <label class="form-label fw-bold fs-6 required">Kode Tiket</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="kode_tiket"
                            v-model="stockIn.kode_tiket"
                            placeholder="Masukkan Kode Tiket"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="kode_tiket" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Jumlah</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="number"
                            name="jumlah"
                            v-model="stockIn.jumlah"
                            placeholder="Masukkan Jumlah"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="jumlah" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Deskripsi</label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="deskripsi"
                            v-model="stockIn.deskripsi"
                            placeholder="Masukkan Deskripsi"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="deskripsi" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Tanggal & Waktu</label>
                        <Flatpickr
                            class="form-control form-control-lg form-control-solid"
                            v-model="stockIn.datetime"
                            :config="{ enableTime: true, dateFormat: 'Y-m-d H:i' }"
                        />
                        <div class="fv-help-block">
                            <ErrorMessage name="datetime" />
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
