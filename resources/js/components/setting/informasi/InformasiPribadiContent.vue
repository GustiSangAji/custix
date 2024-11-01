<script lang="ts">
import { defineComponent, ref } from "vue";
import { ErrorMessage, Field, Form as VForm } from "vee-validate";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useAuthStore } from "@/stores/auth"; // pastikan jalur ini sesuai
import * as Yup from "yup";
import axios from 'axios';

interface ProfileDetails {
  photo: string;
  name: string;
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
    const authStore = useAuthStore(); // ambil store autentikasi
    const photoFile = ref<File | null>(null);

    const submitButton1 = ref<HTMLElement | null>(null);
    const submitButton2 = ref<HTMLElement | null>(null);
    const submitButton3 = ref<HTMLElement | null>(null);
    const submitButton4 = ref<HTMLElement | null>(null);
    const submitButton5 = ref<HTMLElement | null>(null);
    const updateEmailButton = ref<HTMLElement | null>(null);
    const updatePasswordButton = ref<HTMLElement | null>(null);

    const emailFormDisplay = ref(false);
    const passwordFormDisplay = ref(false);

    const profileDetailsValidator = Yup.object().shape({
      name: Yup.string().required().label("Your name"),
      phone: Yup.string().required().label("Phone number"),
    });

    const changeEmail = Yup.object().shape({
      emailaddress: Yup.string().required().email().label("Email"),
      confirmemailpassword: Yup.string().required().label("Password"),
    });

    const changePassword = Yup.object().shape({
      currentpassword: Yup.string().required().label("Current password"),
      newpassword: Yup.string().min(4).required().label("Password"),
      confirmpassword: Yup.string()
        .min(4)
        .required()
        .oneOf([Yup.ref("newpassword")], "Passwords must match")
        .label("Password Confirmation"),
    });

    const profileDetails = ref<ProfileDetails>({
        photo: authStore.user?.photo || "/media/avatars/blank.png", // ambil foto dari store
        name: authStore.user?.name || "",
        email: authStore.user?.email || "",
        phone: authStore.user?.phone || "",
});

const handleFileChange = (event: Event) => {
      const target = event.target as HTMLInputElement;
      if (target.files && target.files[0]) {
        photoFile.value = target.files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
          profileDetails.value.photo = e.target?.result as string; // Set preview
        };
        reader.readAsDataURL(target.files[0]);
      }
    };



    const saveChanges1 = async () => {
      const formData = new FormData();
      formData.append("name", profileDetails.value.name);
      formData.append("phone", profileDetails.value.phone);
      formData.append("email", profileDetails.value.email);
      if (photoFile.value) {
        formData.append("photo", photoFile.value);
      }

      try {
        const response = await axios.post('/api/profile/update', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            'Authorization': `Bearer ${localStorage.getItem('token')}`, // Jika menggunakan token
          },
        });

        authStore.user.photo = response.data.photo; // Perbarui dengan URL foto baru
        profileDetails.value.photo = response.data.photo; // Update untuk tampilan

        Swal.fire({
          text: response.data.message,
          icon: 'success',
          confirmButtonText: 'Ok',
          customClass: {
            confirmButton: 'btn btn-light-primary',
          },
        });
      } catch (error) {
        console.error(error);
        Swal.fire({
          text: 'Failed to update profile!',
          icon: 'error',
          confirmButtonText: 'Ok',
          customClass: {
            confirmButton: 'btn btn-light-primary',
          },
        });
      }
    };

    const removeImage = () => {
      profileDetails.value.photo = "/media/avatars/blank.png";
    };




    const saveChanges2 = () => {
      if (submitButton2.value) {
        submitButton2.value.setAttribute("data-kt-indicator", "on");
        setTimeout(() => {
          submitButton2.value?.removeAttribute("data-kt-indicator");
        }, 2000);
      }
    };

    const saveChanges3 = () => {
      if (submitButton3.value) {
        submitButton3.value.setAttribute("data-kt-indicator", "on");
        setTimeout(() => {
          submitButton3.value?.removeAttribute("data-kt-indicator");
        }, 2000);
      }
    };

    const saveChanges4 = () => {
      if (submitButton4.value) {
        submitButton4.value.setAttribute("data-kt-indicator", "on");
        setTimeout(() => {
          submitButton4.value?.removeAttribute("data-kt-indicator");
        }, 2000);
      }
    };

    const deactivateAccount = () => {
      if (submitButton5.value) {
        submitButton5.value.setAttribute("data-kt-indicator", "on");
        setTimeout(() => {
          submitButton5.value?.removeAttribute("data-kt-indicator");

          Swal.fire({
            text: "Email is successfully changed!",
            icon: "success",
            confirmButtonText: "Ok",
            buttonsStyling: false,
            heightAuto: false,
            customClass: {
              confirmButton: "btn btn-light-primary",
            },
          }).then(() => {
            emailFormDisplay.value = false;
          });
        }, 2000);
      }
    };

    const updateEmail = () => {
      if (updateEmailButton.value) {
        updateEmailButton.value.setAttribute("data-kt-indicator", "on");
        setTimeout(() => {
          updateEmailButton.value?.removeAttribute("data-kt-indicator");
          emailFormDisplay.value = false;
        }, 2000);
      }
    };

    const updatePassword = () => {
      if (updatePasswordButton.value) {
        updatePasswordButton.value.setAttribute("data-kt-indicator", "on");
        setTimeout(() => {
          updatePasswordButton.value?.removeAttribute("data-kt-indicator");

          Swal.fire({
            text: "Password is successfully changed!",
            icon: "success",
            confirmButtonText: "Ok",
            buttonsStyling: false,
            heightAuto: false,
            customClass: {
              confirmButton: "btn btn-light-primary",
            },
          }).then(() => {
            passwordFormDisplay.value = false;
          });
        }, 2000);
      }
    };

  

    return {
      submitButton1,
      submitButton2,
      submitButton3,
      submitButton4,
      submitButton5,
      updateEmailButton,
      updatePasswordButton,
      emailFormDisplay,
      passwordFormDisplay,
      profileDetails,
      handleFileChange,
      saveChanges1,
      saveChanges2,
      saveChanges3,
      saveChanges4,
      deactivateAccount,
      updateEmail,
      updatePassword,
      removeImage,
      profileDetailsValidator,
      changeEmail,
      changePassword,
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
          novalidate
          @submit="saveChanges1"
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
                  :style="{ backgroundImage: `url(${profileDetails.photo})` }"
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

                  <!--begin::Remove-->
                  <span
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="remove"
                    data-bs-toggle="tooltip"
                    @click="removeImage()"
                    title="Remove avatar"
                  >
                    <i class="bi bi-x fs-2"></i>
                  </span>
                  <!--end::Remove-->
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
                  v-model="profileDetails.name"
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
              type="reset"
              class="btn btn-light btn-active-light-primary me-2"
            >
              Discard
            </button>

            <button
              type="submit"
              id="kt_account_profile_details_submit"
              ref="submitButton1"
              class="btn btn-primary"
            >
              <span class="indicator-label"> Save Changes </span>
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
