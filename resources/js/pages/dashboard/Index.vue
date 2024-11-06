<template>
  <div class="container-fluid">
    <!-- Grid Layout for Widgets -->
    <div class="row widget-grid">
      <!-- Widget Pelanggan Baru -->
      <div class="col-12 col-md-4">
        <div class="card widget-card shadow-sm d-flex align-items-center justify-content-between">
          <div class="card-body text-center">
            <!-- Ikon besar di kiri -->
            <span class="symbol symbol-80px bg-light-primary d-inline-flex align-items-center justify-content-center">
              <i class="bi bi-person-plus-fill fs-1 text-primary"></i>
            </span>
            <!-- Teks di atas ikon -->
            <h4 class="text-dark fw-bold mt-3">Pelanggan Baru</h4>
            <span class="text-muted">Total Pelanggan</span>
            <!-- Angka di bawah -->
            <div class="text-primary fw-bold fs-3 mt-2">{{ pelanggan }}</div>
          </div>
        </div>
      </div>
      <!-- Widget Pendapatan -->
      <div class="col-12 col-md-4">
        <div class="card widget-card shadow-sm d-flex align-items-center justify-content-between">
          <div class="card-body text-center">
            <span class="symbol symbol-80px bg-light-success d-inline-flex align-items-center justify-content-center">
              <i class="bi bi-cash-stack fs-1 text-success"></i>
            </span>
            <h4 class="text-dark fw-bold mt-3">Pendapatan</h4>
            <span class="text-muted">Total Pendapatan</span>
            <div class="text-success fw-bold fs-3 mt-2">{{ formatRupiah(pendapatan) }}</div>
          </div>
        </div>
      </div>
      <!-- Widget Tiket Tersedia -->
      <div class="col-12 col-md-4">
        <div class="card widget-card shadow-sm d-flex align-items-center justify-content-between">
          <div class="card-body text-center">
            <span class="symbol symbol-80px bg-light-danger d-inline-flex align-items-center justify-content-center">
              <i class="bi bi-ticket-detailed fs-1 text-danger"></i>
            </span>
            <h4 class="text-dark fw-bold mt-3">Tiket</h4>
            <span class="text-muted">Tiket Tersedia</span>
            <div class="text-danger fw-bold fs-3 mt-2">{{ tiket }}</div>
          </div>
        </div>
      </div>
    </div>
    <!-- Grafik Pendapatan Bulanan dengan Bar dan Area -->
    <div class="card mt-4 shadow-sm">
      <div class="card-header">
        <h3 class="card-title">Pendapatan Tiket</h3>
        <div class="card-toolbar">
    
        </div>
      </div>
      <div class="card-body">
        <apexchart ref="chartRef" type="bar" :options="chart" :series="series"></apexchart>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onBeforeMount, watch } from "vue";
import { useThemeStore } from "@/stores/theme";
import { getCSSVariableValue } from "@/assets/ts/_utils";
import axios from "axios";
import type { ApexOptions } from "apexcharts";
import type VueApexCharts from "vue3-apexcharts";

export default defineComponent({
  name: "DashboardWidgets",
  setup() {
    const pelanggan = ref(0);
    const pendapatan = ref(0);
    const tiket = ref(0);

    const chartRef = ref<typeof VueApexCharts | null>(null);
    const chart = ref<ApexOptions>({});
    const store = useThemeStore();

    // Menambahkan dua seri, satu untuk bar dan satu untuk area
    const series = [
      {
        name: "Pendapatan Bar",
        type: "bar",
        stacked: true,
        data: Array(12).fill(0), // Data dummy untuk 12 bulan
      },
      {
        name: "Pendapatan Area",
        type: "area",
        data: Array(12).fill(0), // Data dummy untuk area chart
      },
    ];

    const fetchData = async () => {
      try {
        const response = await axios.get("/dashboard");
        pelanggan.value = response.data.pelanggan;
        pendapatan.value = response.data.pendapatan;
        tiket.value = response.data.tiket;

        if (response.data.pendapatan_bulanan && Array.isArray(response.data.pendapatan_bulanan)) {
          const bulanData = Array(12).fill(0); // Inisialisasi array untuk 12 bulan
          const areaData = Array(12).fill(0); // Inisialisasi array untuk grafik area

          response.data.pendapatan_bulanan.forEach((item: any) => {
            const bulanIndex = convertMonthToNumber(item.bulan); // Konversi nama bulan ke angka
            if (bulanIndex >= 1 && bulanIndex <= 12) {
              bulanData[bulanIndex - 1] = item.total_pendapatan;
              areaData[bulanIndex - 1] = item.total_pendapatan; // Sama dengan bar untuk sekarang
            }
          });
          series[0].data = bulanData; // Update data pendapatan untuk bar
          series[1].data = areaData; // Update data pendapatan untuk area
        }

        if (chartRef.value) {
          chartRef.value.updateOptions(chart.value);
        }
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    };
    const chartOptions = (): ApexOptions => {
  const labelColor = getCSSVariableValue("--bs-gray-500");
  const baseColor = getCSSVariableValue("--bs-success"); // Warna hijau untuk bar
  const areaColor = getCSSVariableValue("--bs-success"); // Warna hijau untuk area
  const darkMode = store.isDarkMode; // Asumsikan Anda memiliki flag mode gelap di theme store

  return {
    chart: {
      type: "bar",
      height: 450,
      stacked: false, // Tidak stacked untuk memisahkan bar dan area
      toolbar: { show: false },
    },
    plotOptions: {
      bar: {
        borderRadius: 5, // Membuat ujung batang lebih rounded
        columnWidth: "25%",
        dataLabels: {
          enabled: false,
        },
      },
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      curve: "smooth", // Area chart membutuhkan curve
      width: [0, 2], // Set the width for bar and area
    },
    xaxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      labels: {
        style: {
          colors: darkMode ? "#fff" : labelColor,
        },
      },
    },
    yaxis: {
      title: {
        text: "Pendapatan (Rp)",
        style: {
          color: darkMode ? "#fff" : labelColor,
        },
      },
      labels: {
        style: {
          colors: darkMode ? "#fff" : labelColor,
        },
        formatter: (val: number) => formatRupiah(val),
      },
    },
    tooltip: {
      enabled: true,
      y: {
        formatter: (val: number) => formatRupiah(val),
      },
    },
    colors: [baseColor, areaColor], // Warna bar dan area sekarang hijau
    fill: {
      type: ["solid", "gradient"], // Bar menggunakan solid, area menggunakan gradient
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.7,
        opacityTo: 0.3,
        stops: [0, 90, 100],
      },
    },
    grid: {
      borderColor: darkMode ? "#444" : "#e0e0e0",
    },
  };
};

    watch(series, (newSeries) => {
      if (chartRef.value) {
        chartRef.value.updateSeries(newSeries);
      }
    });

    onBeforeMount(() => {
      fetchData();
      chart.value = chartOptions();
    });

    const formatRupiah = (value: number) =>
      new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(value);

    const convertMonthToNumber = (month: string) => {
      const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      return months.indexOf(month) + 1;
    };

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

  }
}

.widget-card {
  border-radius: 8px;
  overflow: hidden;
}

.widget-card .card-body {
  padding: 25px;
}

.widget-card .symbol {
  width: 80px;
  height: 80px;
  border-radius: 10px;
}
</style>
