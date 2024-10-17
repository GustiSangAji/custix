<template>
  <div class="container-fluid">
    <!-- Grid Layout for Widgets -->
    <div class="row widget-grid">
      <!-- Widget Pelanggan Baru -->
      <div class="col-12 col-md-4">
        <div class="card widget-card shadow-sm">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <span class="symbol symbol-50px bg-light-primary me-3">
                <span class="symbol-label">
                  <i class="bi bi-person-plus-fill fs-2 text-primary"></i>
                </span>
              </span>
              <div>
                <h4 class="text-dark fw-bold mb-1">Pelanggan Baru</h4>
                <span class="text-muted">Total Pelanggan</span>
              </div>
            </div>
            <span class="text-dark fw-bold fs-2">{{ pelanggan }}</span>
          </div>
        </div>
      </div>

      <!-- Widget Pendapatan -->
      <div class="col-12 col-md-4">
        <div class="card widget-card shadow-sm">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <span class="symbol symbol-50px bg-light-success me-3">
                <span class="symbol-label">
                  <i class="bi bi-cash-stack fs-2 text-success"></i>
                </span>
              </span>
              <div>
                <h4 class="text-dark fw-bold mb-1">Pendapatan</h4>
                <span class="text-muted">Total Pendapatan</span>
              </div>
            </div>
            <span class="text-success fw-bold fs-2">{{ formatRupiah(pendapatan) }}</span>
          </div>
        </div>
      </div>

      <!-- Widget Tiket Tersedia -->
      <div class="col-12 col-md-4">
        <div class="card widget-card shadow-sm">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <span class="symbol symbol-50px bg-light-danger me-3">
                <span class="symbol-label">
                  <i class="bi bi-ticket-detailed fs-2 text-danger"></i>
                </span>
              </span>
              <div>
                <h4 class="text-dark fw-bold mb-1">Tiket</h4>
                <span class="text-muted">Tiket Tersedia</span>
              </div>
            </div>
            <span class="text-danger fw-bold fs-2">{{ tiket }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Grafik Pesanan Terbaru -->
    <div class="card mt-4 shadow-sm">
      <div class="card-header">
        <h3 class="card-title">Pesanan Terbaru</h3>
        <div class="card-toolbar">
          <a class="btn btn-primary btn-sm">Penjualan</a>
          <a class="btn btn-secondary btn-sm">Pengeluaran</a>
        </div>
      </div>
      <div class="card-body">
        <apexchart ref="chartRef" type="bar" :options="chart" :series="series"></apexchart>
      </div>
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
          const response = await axios.get("/dashboard");
          pelanggan.value = response.data.pelanggan;
          pendapatan.value = response.data.pendapatan;
          tiket.value = response.data.tiket;
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
    box-shadow: 0px 0px 15px rgba(61, 52, 52, 0.2) !important;
  }
}

.large-widget {
  min-height: 250px; /* Sesuaikan tinggi minimum */
  width: 100%; /* Lebar penuh */
}

.card-xl-stretch {
  height: 350px; /* Atur tinggi widget */
}
</style>

