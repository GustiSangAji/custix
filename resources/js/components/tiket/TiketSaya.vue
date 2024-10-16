<template>
    <div class="col-md-9 col-lg-10">
      <div class="card mb-5 mb-xl-10">
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
                <h3 class="fw-bold m-0">Tiket Saya</h3>
            </div>
            <!--end::Card title-->
      </div>
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="kt_account_profile_details" class="collapse show">
          <div class="card">
            <div class="card-body">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" href="#">Event Aktif</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">Event Tidak Aktif</a>
                </li>
              </ul>
              <div
                class="d-flex flex-column align-items-center justify-content-center mt-4"
              >
                <img
                  src="/media/icons/ticket_tiket-saya.png"
                  alt="Empty Ticket"
                  class="img-fluid mb-6"
                  style="width: 100px; height: auto"
                />
                <p class="text-muted fs-4 fw-bold">
                  Kamu belum memiliki tiket, silakan membeli tiket terlebih
                  dahulu.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
</template>

<script>
export default {
  name: "TiketSaya",
};
</script>

<style scoped>
/* Tambahkan styling jika diperlukan */
</style>
