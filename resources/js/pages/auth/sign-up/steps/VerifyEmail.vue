<template>
    <section class="w-100">
        <!--begin::Input group for Nama-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bold text-dark fs-6">Nama</label>
            <Field
                class="form-control form-control-lg form-control-solid"
                type="text"
                name="nama"
                autocomplete="off"
                v-model="formData.name"
            />
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="nama" />
                </div>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bold text-dark fs-6">Email</label>
            <Field
                class="form-control form-control-lg form-control-solid"
                type="text"
                name="email"
                autocomplete="off"
                v-model="formData.email"
                disabled
            />
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="email" />
                </div>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label
                class="form-label fw-bold text-dark fs-6 d-flex align-items-center justify-content-between"
                >Kode OTP
            </label>
            <vue-otp-input
                input-classes="form-control form-control-lg form-control-solid text-center"
                name="otp_email"
                separator="-"
                :num-inputs="6"
                :should-auto-focus="true"
                input-type="number"
                v-model="formData.otp_email"
                @onChange="handleOtp"
                @onComplete="handleOtp"
            />
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="otp_email" />
                </div>
            </div>
            <div
                v-if="otpInterval == 0"
                class="text-gray-400 fw-semobold fs-4 text-end mt-4"
            >
                Tidak menerima kode OTP?
                <button
                    type="button"
                    class="btn p-0 link-primary fw-bold"
                    @click="sendOtp"
                >
                    Kirim Ulang
                </button>
            </div>
            <div v-else class="text-gray-400 fw-semobold fs-4 text-end mt-4">
                Kode OTP dapat dikirim ulang dalam
                <span class="fw-bold">{{ otpInterval }}</span> detik
            </div>
        </div>
        <!--end::Input group-->
    </section>
</template>

<script lang="ts">
import { defineComponent, reactive } from "vue";
import { useOtpIntervalStore } from "../Index.vue";
import VueOtpInput from "vue3-otp-input"; // Import komponen

export default defineComponent({
    name: "VerifyPhone",
    components: {
        VueOtpInput, // Daftarkan komponen
    },
    props: {
        formData: {
            type: Object,
            required: true,
        },
    },
    emits: ["onComplete", "sendOtp"],
    setup(props, ctx) {
        const { otpInterval } = useOtpIntervalStore();

        // Inisialisasi formData
        const formData = reactive({
            email: props.formData.email || "",
            otp_email: props.formData.otp_email || "",
            name: props.formData.name || "", // Tambahkan field nama
        });
        const handleOtp = (value: string) => {
            ctx.emit("onComplete", value);
        };

        const sendOtp = () => {
            ctx.emit("sendOtp");
        };

        return {
            formData,
            handleOtp,
            sendOtp,
            otpInterval,
        };
    },
});
</script>
