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

const stokIn = ref({
  ticket_id: '', // Menjadi input manual untuk memasukkan ID tiket
  description: '',
  added_at: '',
  amount: 0,
});

const formRef = ref();

const formSchema = Yup.object().shape({
  ticket_id: Yup.string().required("ID Tiket harus diisi"),
  description: Yup.string().required("Deskripsi harus diisi"),
  amount: Yup.number().required("Jumlah harus diisi").min(1, "Jumlah minimal 1"),
});

function getEdit() {
  block(document.getElementById("form-stok-in"));
  ApiService.get(`/stok-in/${props.selected}`)
    .then(({ data }) => {
      if (data && data.stokIn) {
        stokIn.value = data.stokIn;
      }
    })
    .catch((err) => {
      toast.error(err.response?.data?.message || "Error fetching data");
    })
    .finally(() => {
      unblock(document.getElementById("form-stok-in"));
    });
}

function submit() {
  const formData = new FormData();
  formData.append("ticket_id", stokIn.value.ticket_id); // ID Tiket diisi manual
  formData.append("description", stokIn.value.description);
  formData.append("added_at", stokIn.value.added_at);
  formData.append("amount", stokIn.value.amount.toString());

  if (props.selected) {
    formData.append("_method", "PUT");
  }

  block(document.getElementById("form-stok-in"));

  axios
    .post(props.selected ? `/stok-in/${props.selected}` : "/stok-in/store", formData, {
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
      unblock(document.getElementById("form-stok-in"));
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
      stokIn.value = {
        ticket_id: '',
        description: '',
        added_at: '',
        amount: 0,
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
    id="form-stok-in"
    ref="formRef"
  >
    <div class="card-header align-items-center">
      <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Stok</h2>
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
              name="ticket_id"
              v-model="stokIn.ticket_id"
              placeholder="Masukkan ID Tiket"
            />
            <div class="fv-help-block">
              <ErrorMessage name="ticket_id" />
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tanggal Penambahan</label>
            <Flatpickr
              class="form-control form-control-lg form-control-solid"
              v-model="stokIn.added_at"
              :config="{ enableTime: true, dateFormat: 'Y-m-d H:i' }"
            />
            <div class="fv-help-block">
              <ErrorMessage name="added_at" />
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Deskripsi</label>
            <Field
              class="form-control form-control-lg form-control-solid"
              type="text"
              name="description"
              v-model="stokIn.description"
              placeholder="Masukkan Deskripsi"
            />
            <div class="fv-help-block">
              <ErrorMessage name="description" />
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Jumlah Stok</label>
            <Field
              class="form-control form-control-lg form-control-solid"
              type="number"
              name="amount"
              v-model="stokIn.amount"
              placeholder="Masukkan Jumlah Stok"
            />
            <div class="fv-help-block">
              <ErrorMessage name="amount" />
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
