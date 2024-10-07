<template>
  <div class="row g-5 g-xl-5">
    <!-- Widget Pelanggan Baru -->
    <div class="col-12 col-md-4">
      <div class="card card-xl-stretch mb-3 mb-xl-8 widget-hover shadow-lg border-0">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="text-start">
            <div class="d-flex align-items-center">
              <span class="symbol symbol-50px bg-light-primary me-3">
                <span class="symbol-label">
                  <i class="bi bi-person-plus-fill fs-2 text-primary"></i>
                </span>
              </span>
              <div>
                <h3 class="text-dark fw-bolder fs-3 mb-0">Pelanggan Baru</h3>
                <span class="text-muted fw-bold fs-7">Total Pelanggan</span>
              </div>
            </div>
          </div>
          <div class="d-flex flex-column text-center">
            <span class="text-dark fw-bolder fs-1x">{{ pelanggan }}</span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Widget Pendapatan -->
    <div class="col-12 col-md-5">
      <div class="card card-xl-stretch mb-3 mb-xl-8 widget-hover shadow-lg border-0">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="text-start">
            <div class="d-flex align-items-center">
              <span class="symbol symbol-50px bg-light-success me-3">
                <span class="symbol-label">
                  <i class="bi bi-cash-stack fs-2 text-success"></i>
                </span>
              </span>
              <div>
                <h3 class="text-dark fw-bolder fs-3 mb-0">Pendapatan</h3>
                <span class="text-muted fw-bold fs-7">Total Pendapatan</span>
              </div>
            </div>
          </div>
          <div class="d-flex flex-column w-100">
            <span class="text-success fw-bolder fs-10x rp-formatter">{{ formatRupiah(2000000) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Widget Tiket Tersedia -->
    <div class="col-12 col-md-3">
      <div class="card card-xl-stretch mb-3 mb-xl-8 widget-hover shadow-lg border-0">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="text-start">
            <div class="d-flex align-items-center">
              <span class="symbol symbol-50px bg-light-danger me-3">
                <span class="symbol-label">
                  <i class="bi bi-ticket-detailed fs-2 text-danger"></i>
                </span>
              </span>
              <div>
                <h3 class="text-dark fw-bolder fs-3 mb-0">Tiket</h3>
                <span class="text-muted fw-bold fs-7">Tiket Tersedia</span>
              </div>
            </div>
          </div>
          <div class="d-flex flex-column text-center">
            <span class="text-danger fw-bolder fs-1x">{{ tiket }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Widget Pesanan -->
    <div class="col-12 col-md-4">
      <div class="card card-xl-stretch mb-3 mb-xl-8 widget-hover shadow-lg border-0">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="text-start">
            <div class="d-flex align-items-center">
              <span class="symbol symbol-50px bg-light-info me-3">
                <span class="symbol-label">
                  <i class="bi bi-cart fs-2 text-info"></i>
                </span>
              </span>
              <div>
                <h3 class="text-dark fw-bolder fs-3 mb-0">Pesanan</h3>
                <span class="text-muted fw-bold fs-7">Total Pesanan</span>
              </div>
            </div>
          </div>
          <div class="d-flex flex-column text-center">
            <span class="text-info fw-bolder fs-1x">{{ pesanan }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Widget Produk Terjual -->
    <div class="col-12 col-md-4">
      <div class="card card-xl-stretch mb-3 mb-xl-8 widget-hover shadow-lg border-0">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="text-start">
            <div class="d-flex align-items-center">
              <span class="symbol symbol-50px bg-light-warning me-3">
                <span class="symbol-label">
                  <i class="bi bi-box-seam fs-2 text-warning"></i>
                </span>
              </span>
              <div>
                <h3 class="text-dark fw-bolder fs-3 mb-0">Produk Terjual</h3>
                <span class="text-muted fw-bold fs-7">Total Produk Terjual</span>
              </div>
            </div>
          </div>
          <div class="d-flex flex-column text-center">
            <span class="text-warning fw-bolder fs-1x">{{ produkTerjual }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Grafik Pesanan Terbaru -->
  <div :class="widgetClasses" class="card card-xl-stretch shadow-lg mb-5 hoverable">
    <div class="card-header border-0 pt-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label fw-bold fs-3 mb-1">Pesanan Terbaru</span>
        <span class="text-muted fw-semibold fs-7">Lebih dari 500+ pesanan baru</span>
      </h3>

      <div class="card-toolbar" data-kt-buttons="true">
        <a class="btn btn-sm btn-color-muted btn-active btn-active-primary active px-4 me-1">Penjualan</a>
        <a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1">Pengeluaran</a>
      </div>
    </div>

    <div class="card-body">
      <apexchart
        ref="chartRef"
        type="bar"
        :options="chart"
        :series="series"
      ></apexchart>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onBeforeMount, computed, watch } from "vue";
import { useThemeStore } from "@/stores/theme";
import { getCSSVariableValue } from "@/assets/ts/_utils";
import axios from "axios";
import type { ApexOptions } from "apexcharts";
import type VueApexCharts from "vue3-apexcharts";

export default defineComponent({
  name: "DashboardWidgets",
  props: {
    widgetClasses: String,
  },
  setup() {
    // Data dashboard yang akan diambil dari API
    const pelanggan = ref(0);
    const pendapatan = ref(0);
    const tiket = ref(0);
    const pesanan = ref(0);
    const produkTerjual = ref(0);
    
    // Ref untuk Chart dan pengaturan chart
    const chartRef = ref<typeof VueApexCharts | null>(null);
    const chart = ref<ApexOptions>({});
    const store = useThemeStore();
    
    // Data untuk grafik (series)
    const series = [
      {
        name: "Laba Bersih",
        type: "bar",
        stacked: true,
        data: [40, 50, 65, 70, 50, 30],
      },
      {
        name: "Pendapatan",
        type: "bar",
        stacked: true,
        data: [20, 20, 25, 30, 30, 20],
      },
      {
        name: "Expenses",
        type: "area",
        data: [50, 80, 60, 90, 50, 70],
      },
    ];

    // Mengambil data dari API Laravel
    const fetchData = async () => {
      try {
        // Ambil data dari API
        const response = await axios.get("/api/dashboard");
        pelanggan.value = response.data.pelanggan;
        pendapatan.value = response.data.pendapatan;
        tiket.value = response.data.tiket;
        pesanan.value = response.data.pesanan;
        produkTerjual.value = response.data.produkTerjual;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    };

    // Opsi untuk chart menggunakan ApexChart
    const chartOptions = (): ApexOptions => {
      const labelColor = getCSSVariableValue("--bs-gray-500");
      const borderColor = getCSSVariableValue("--bs-gray-200");
      const baseColor = getCSSVariableValue("--bs-primary");
      const baseLightColor = getCSSVariableValue("--bs-light-primary");

      return {
        chart: {
          fontFamily: "inherit",
          type: "bar",
          stacked: true,
          height: 350,
          toolbar: {
            show: false,
          },
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: ["12%"],
            borderRadius: 4,
          },
        },
        legend: {
          show: true,
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: "smooth",
          show: true,
          width: 2,
          colors: [baseColor],
        },
        fill: {
          type: "solid",
        },
        xaxis: {
          categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
          axisBorder: {
            show: false,
          },
          axisTicks: {
            show: false,
          },
          labels: {
            style: {
              colors: labelColor,
              fontSize: "12px",
            },
          },
        },
        yaxis: {
          labels: {
            style: {
              colors: labelColor,
              fontSize: "12px",
            },
          },
        },
        grid: {
          borderColor: borderColor,
          strokeDashArray: 4,
          yaxis: {
            lines: {
              show: true,
            },
          },
        },
        colors: [baseColor, baseLightColor],
        series,
      };
    };

    // Memantau perubahan tema
    const themeMode = computed(() => store.mode);
    watch(themeMode, () => {
      if (chartRef.value) {
        chartRef.value.updateOptions(chartOptions());
      }
    });

    // Ambil data dari API saat komponen di-mount
    onBeforeMount(() => {
      fetchData();
      chart.value = chartOptions();
    });

    // Fungsi untuk format angka ke dalam Rupiah
    const formatRupiah = (value: number) =>
      new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
      }).format(value);

    return {
      pelanggan,
      pendapatan,
      tiket,
      pesanan,
      produkTerjual,
      chart,
      series,
      chartRef,
      formatRupiah,
    };
  },
});
</script>


<style scoped lang="scss">
.widget-hover {
  transition: box-shadow 0.2s ease-in-out;
  &:hover {
    box-shadow: 0px 0px 15px rgba(27, 7, 212, 0.2) !important;
  }

}
</style>
