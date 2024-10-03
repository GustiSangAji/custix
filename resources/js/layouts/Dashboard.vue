<template>
    <div class="hero">
      <!-- Desktop -->
      <div
        id="kt_carousel_1_carousel"
        class="carousel carousel-custom slide"
        data-bs-ride="carousel"
        data-bs-interval="8000"
      >
        <!--begin::Heading-->
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <!--begin::Carousel Indicators-->
          <ol class="p-0 m-0 carousel-indicators carousel-indicators-dots">
            <li
              data-bs-target="#kt_carousel_1_carousel"
              data-bs-slide-to="0"
              class="ms-1 active"
            ></li>
            <li
              data-bs-target="#kt_carousel_1_carousel"
              data-bs-slide-to="1"
              class="ms-1"
            ></li>
            <li
              data-bs-target="#kt_carousel_1_carousel"
              data-bs-slide-to="2"
              class="ms-1"
            ></li>
          </ol>
          <!--end::Carousel Indicators-->
        </div>
        <!--end::Heading-->
  
        <!--begin::Carousel-->
        <div class="carousel-inner pt-8 shadow-sm">
          <!--begin::Item-->
          <div class="carousel-item active">
            <img
              src="/media/carousel/closing-ceremony.png"
              class="img-fluid rounded"
              width="100%"
              alt=""
            />
          </div>
          <!--end::Item-->
  
          <!--begin::Item-->
          <div class="carousel-item">
            <img
              src="/media/carousel/slider-desktop-dewa-19-kediri.png"
              class="img-fluid rounded"
              width="100%"
              alt=""
            />
          </div>
          <!--end::Item-->
  
          <!--begin::Item-->
          <div class="carousel-item">
            <img
              src="/media/carousel/swara-semesta-menyapa-tulungagung.jpg"
              class="img-fluid rounded"
              width="100%"
              alt=""
            />
          </div>
          <!--end::Item-->
        </div>
        <!--end::Carousel-->
  
        <div class="d-none d-md-block">
          <!-- Cards Section -->
          <div class="mt-20">
            <div class="row">
              <div class="col">
                <h2 class="text-light">Unggulan</h2>
              </div>
              <div class="col text-end">
                <router-link to="/tiket" class="btn btn-success"
                  >Lihat semua</router-link
                >
              </div>
            </div>
          </div>
  
          <!-- Ticket Cards Section -->
          <div class="row mt-5">
            <!-- Card 1 -->
            <div class="col-md-6">
              <div class="card shadow-sm">
                <img
                  src="/media/tiket/closing-ceremony-of-artefac-uns-2024.png"
                  class="card-img-top img-fluid"
                  alt="Closing Ceremony"
                />
                <div class="card-body p-4">
                  <h5 class="card-title fw-bold text-uppercase mb-3">
                    CLOSING CEREMONY OF ARTEFAC UNS 2024
                  </h5>
                  <p class="card-text fs-6 mb-3 text-muted">
                    <i class="bi bi-calendar me-2"></i> 11 Oktober 2024 &nbsp;
                    <i class="bi bi-geo-alt me-2"></i> Solo, Artefac UNS
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="bg-dark text-light px-3 py-2 rounded">
                      Mulai Dari Rp. 185,000
                    </div>
                    <RouterLink to="/pemesanan" class="btn btn-danger fw-bold px-4 py-2">
                      Beli Tiket
                    </RouterLink>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Card 2 -->
            <div class="col-md-6">
              <div
                class="card shadow-sm"
              >
                <img
                  src="/media/tiket/midea-run-to-party.png"
                  class="card-img-top img-fluid"
                  alt="Run to Party"
                />
                <div class="card-body p-4">
                  <h5 class="card-title fw-bold text-uppercase mb-3">
                    MIDEA RUN TO PARTY
                  </h5>
                  <p class="card-text fs-6 mb-3 text-muted">
                    <i class="bi bi-calendar me-2"></i> 10 November 2024 &nbsp;
                    <i class="bi bi-geo-alt me-2"></i> Jakarta, PT GILA LALALA
                    ENTERTAINMENT
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="bg-dark text-light px-3 py-2 rounded">
                      Mulai Dari Rp. 135,000
                    </div>
                    <RouterLink to="/pemesanan" class="btn btn-danger fw-bold px-4 py-2">
                      Beli Tiket
                    </RouterLink>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <div class="row mt-5">
            <!-- Card 3 -->
            <div class="col-md-6">
              <div
                class="card shadow-sm"
              >
                <img
                  src=" /media/tiket/thumbnail-unggulan-pasuruan.jpg"
                  class="card-img-top img-fluid"
                  alt="Run to Party"
                />
                <div class="card-body p-4">
                  <h5 class="card-title fw-bold text-uppercase mb-3">
                    Swara Semesta Menyapa Pasuruan
                  </h5>
                  <p class="card-text fs-6 mb-3 text-muted">
                    <i class="bi bi-calendar me-2"></i> 30 November 2024  &nbsp;
                    <i class="bi bi-geo-alt me-2"></i> Pasuruan, Maju Gemilang Production
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="bg-dark text-light px-3 py-2 rounded">
                      Mulai Dari Rp. 99,000
                    </div>
                    <RouterLink to="/pemesanan" class="btn btn-danger fw-bold px-4 py-2">
                      Beli Tiket
                    </RouterLink>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Card 4 -->
            <div class="col-md-6">
              <div
                class="card shadow-sm"
              >
                <img
                src="/media/tiket/bernadya.jpg"
                  class="card-img-top img-fluid " 
                  alt="Run to Party"
                />
                <div class="card-body p-4">
                  <h5 class="card-title fw-bold text-uppercase mb-3">
                    #mendengarkanMP3Fest
                  </h5>
                  <p class="card-text fs-6 mb-3 text-muted">
                    <i class="bi bi-calendar me-2"></i> 10 November 2024 &nbsp;
                    <i class="bi bi-geo-alt me-2"></i> Banjarbaru, Space Team Indonesia
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="bg-dark text-light px-3 py-2 rounded">
                      Mulai Dari Rp. 150,000
                    </div>
                    <RouterLink to="/pemesanan" class="btn btn-danger fw-bold px-4 py-2">
                      Beli Tiket
                    </RouterLink>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End of Ticket Cards -->
        </div>
      </div>
      <!-- Closing tag for hero div -->
    </div>
  </template>
  
  <script>
  import { RouterLink } from 'vue-router';
  
  export default {
    name: "Dashboard",
  };
  </script>
  
  <style>
  </style>
  