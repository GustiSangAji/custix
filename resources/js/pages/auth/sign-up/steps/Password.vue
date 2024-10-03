<template>
    <section class="w-100">
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bold text-dark fs-6">Password</label>
            <div class="position-relative mb-3">
                <!--begin::Input-->
                <Field class="form-control form-control-lg form-control-solid" type="password" name="password"
                    autocomplete="off" v-model="formData.password" />
                <!--end::Input-->

                <!--begin::Visibility toggle-->
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2">
                    <i class="bi bi-eye-slash fs-2" @click="togglePassword"></i>
                </span>
                <!--end::Visibility toggle-->
            </div>
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="password" />
                </div>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bold text-dark fs-6">Konfirmasi Password</label>
            <div class="position-relative mb-3">
                <!--begin::Input-->
                <Field class="form-control form-control-lg form-control-solid" type="password" name="password_confirmation"
                    autocomplete="off" v-model="formData.password_confirmation" />
                <!--end::Input-->

                <!--begin::Visibility toggle-->
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2">
                    <i class="bi bi-eye-slash fs-2" @click="toggleConfirmPassword"></i>
                </span>
                <!--end::Visibility toggle-->
            </div>
            <div class="fv-plugins-message-container">
                <div class="fv-help-block" v-if="!isPasswordMatch">
                    <span style="color: red;">Password dan konfirmasi password tidak cocok!</span>
                </div>
            </div>
        </div>
        <!--end::Input group-->
    </section>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue'

export default defineComponent({
    name: 'Password',
    props: {
        formData: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        const isPasswordMatch = ref(true);

        // Watch changes on password and password confirmation
        watch(() => [props.formData.password, props.formData.password_confirmation], () => {
            isPasswordMatch.value = props.formData.password === props.formData.password_confirmation;
        });

        return {
            formData: props.formData,
            isPasswordMatch,
        }
    },
    methods: {
        togglePassword(ev) {
            const passwordInput = document.querySelector("[name=password]") as HTMLInputElement;
            if (passwordInput) {
                passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                ev.target.classList.toggle("bi-eye");
                ev.target.classList.toggle("bi-eye-slash");
            }
        },
        toggleConfirmPassword(ev) {
            const confirmPasswordInput = document.querySelector("[name=password_confirmation]") as HTMLInputElement;
            if (confirmPasswordInput) {
                confirmPasswordInput.type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
                ev.target.classList.toggle("bi-eye");
                ev.target.classList.toggle("bi-eye-slash");
            }
        }
    }
})
</script>
