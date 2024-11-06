<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import { ErrorMessage, Field, Form as VForm } from "vee-validate";
import Swal from "sweetalert2/dist/sweetalert2.js";
import * as Yup from "yup";
import axios from "axios";

interface ProfileDetails {
  photo: string;
  nama: string;
  email: string;
  phone: string;
}

export default defineComponent({
  components: {
    ErrorMessage,
    Field,
    VForm,
  },
  setup() {
    const photoFile = ref<File | null>(null);

    const submitButton1 = ref<HTMLElement | null>(null);

    const emailFormDisplay = ref(false);
    const passwordFormDisplay = ref(false);

    const profileDetailsValidator = Yup.object().shape({
      nama: Yup.string().required().label("Your name"),
      phone: Yup.string().required().label("Phone number"),
    });

    const profileDetails = ref<ProfileDetails>({
      photo: "",
      nama: "",
      email: "",
      phone: "",
    });

    // Ambil data profil saat komponen dimuat
    onMounted(async () => {
      try {
        const response = await axios.get("/profile", {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`, // Pastikan token disimpan di localStorage atau session
          },
        });
        profileDetails.value = {
          photo: response.data.photo || "/media/avatars/profz.png",
          nama: response.data.name,
          email: response.data.email,
          phone: response.data.phone,
        };
      } catch (error) {
        console.error("Failed to fetch profile data", error);
      }
    });

    const handleFileChange = (event: Event) => {
      const target = event.target as HTMLInputElement;
      if (target.files && target.files[0]) {
        photoFile.value = target.files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
          profileDetails.value.photo = e.target?.result as string;
        };
        reader.readAsDataURL(target.files[0]);
      }
    };

    const saveChanges1 = async () => {
      const formData = new FormData();
      formData.append("name", profileDetails.value.nama);
      formData.append("phone", profileDetails.value.phone);
      formData.append("email", profileDetails.value.email);
      if (photoFile.value) {
        formData.append("photo", photoFile.value);
      }

      try {
        const response = await axios.post("/profile/update", formData, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        profileDetails.value.photo = response.data.photo;

        Swal.fire({
          text: response.data.message,
          icon: "success",
          confirmButtonText: "Ok",
          customClass: {
            confirmButton: "btn btn-light-primary",
          },
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.reload();
          }
        });
      } catch (error) {
        console.error(error);
        Swal.fire({
          text: "Failed to update profile!",
          icon: "error",
          confirmButtonText: "Ok",
          customClass: {
            confirmButton: "btn btn-light-primary",
          },
        });
      }
    };

    return {
      submitButton1,
      emailFormDisplay,
      passwordFormDisplay,
      profileDetails,
      handleFileChange,
      saveChanges1,
      profileDetailsValidator,
    };
  },
});
</script>


<template>
  <div class="col-md-8 col-lg-9">
    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
      <!--begin::Card header-->
      <div
        class="card-header border-0 cursor-pointer"
        role="button"
        data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details"
        aria-expanded="true"
        aria-controls="kt_account_profile_details"
      >
        <!--begin::Card title-->
        <div class="card-title m-0">
          <h3 class="fw-bold m-0">Profile Details</h3>
        </div>
        <!--end::Card title-->
      </div>
      <!--begin::Card header-->

      <!--begin::Content-->
      <div id="kt_account_profile_details" class="collapse show">
        <!--begin::Form-->
        <VForm
          id="kt_account_profile_details_form"
          class="form"
          @submit.prevent="saveChanges1"
          :validation-schema="profileDetailsValidator"
        >
          <!--begin::Card body-->
          <div class="card-body border-top p-9">
            <!--begin::Input group-->
            <div class="row mb-6">
              <!--begin::Label-->
              <label class="col-lg-4 col-form-label fw-semibold fs-6"
                >Photo</label
              >
              <!--end::Label-->

              <!--begin::Col-->
              <div class="col-lg-8">
                <!--begin::Image input-->
                <div
                  class="image-input image-input-outline"
                  data-kt-image-input="true"
                  :style="{ backgroundImage: `url(${profileDetails.photo}) ||  '/media/avatars/profz.png'})` }"
                >
                  <!--begin::Preview existing avatar-->
                  <div
                    class="image-input-wrapper w-125px h-125px"
                    :style="`background-image: url(${profileDetails.photo})`"
                  ></div>
                  <!--end::Preview existing avatar-->

                  <!--begin::Label-->
                  <label
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change"
                    data-bs-toggle="tooltip"
                    title="Change Photo"
                  >
                    <i class="bi bi-pencil-fill fs-7"></i>

                    <!--begin::Inputs-->
                    <input
                      type="file"
                      name="avatar"
                      accept=".png, .jpg, .jpeg"
                      @change="handleFileChange"
                    />
                    <input type="hidden" name="avatar_remove" />
                    <!--end::Inputs-->
                  </label>
                  <!--end::Label-->
                </div>
                <!--end::Image input-->

                <!--begin::Hint-->
                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                <!--end::Hint-->
              </div>
              <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
              <!--begin::Label-->
              <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                Name
              </label>
              <!--end::Label-->

              <!--begin::Col-->
              <div class="col-lg-8 fv-row">
                <Field
                  type="text"
                  name="name"
                  class="form-control form-control-lg form-control-solid"
                  placeholder="Masukkan Nama Anda"
                  v-model="profileDetails.nama"
                  readonly
                />
                <div class="fv-plugins-message-container">
                  <div class="fv-help-block">
                    <ErrorMessage name="name" />
                  </div>
                </div>
              </div>
              <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
              <!--begin::Label-->
              <label class="col-lg-4 col-form-label fw-semibold fs-6">
                <span class="required">Nomor Telepon</span>

                <i
                  class="fas fa-exclamation-circle ms-1 fs-7"
                  data-bs-toggle="tooltip"
                  title="Phone number must be active"
                ></i>
              </label>
              <!--end::Label-->

              <!--begin::Col-->
              <div class="col-lg-8 fv-row">
                <Field
                  type="tel"
                  name="phone"
                  class="form-control form-control-lg form-control-solid"
                  placeholder="Masukkan Nomor Telepon Anda"
                  v-model="profileDetails.phone"
                  readonly
                />
                <div class="fv-plugins-message-container">
                  <div class="fv-help-block">
                    <ErrorMessage name="phone" />
                  </div>
                </div>
              </div>
              <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
              <!--begin::Label-->
              <label class="col-lg-4 col-form-label fw-semibold fs-6">
                <span class="required">Email</span>

                <i
                  class="fas fa-exclamation-circle ms-1 fs-7"
                  data-bs-toggle="tooltip"
                  title="Phone number must be active"
                ></i>
              </label>
              <!--end::Label-->

              <!--begin::Col-->
              <div class="col-lg-8 fv-row">
                <Field
                  type="email"
                  name="email"
                  class="form-control form-control-lg form-control-solid"
                  placeholder="Masukkan Email Anda"
                  v-model="profileDetails.email"
                  readonly
                />
                <div class="fv-plugins-message-container">
                  <div class="fv-help-block">
                    <ErrorMessage name="email" />
                  </div>
                </div>
              </div>
              <!--end::Col-->
            </div>
            <!--end::Input group-->
          </div>
          <!--end::Card body-->

          <!--begin::Actions-->
          <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button
              type="button"
              id="kt_account_profile_details_submit"
              ref="submitButton1"
              class="btn btn-primary"
              @click.prevent="saveChanges1"
            >
              <span class="indicator-label">Save Changes</span>
              <span class="indicator-progress">
                Please wait...
                <span
                  class="spinner-border spinner-border-sm align-middle ms-2"
                ></span>
              </span>
            </button>
          </div>
          <!--end::Actions-->
        </VForm>
        <!--end::Form-->
      </div>
      <!--end::Content-->   
    </div>
    <!--end::Basic info-->
  </div>
</template>
