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
            <!-- Ubah warna teks jumlah pelanggan menjadi biru -->
            <span class="text-primary fw-bold fs-2">{{ pelanggan }}</span>
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

    <!-- Grafik Pendapatan Bulanan -->
    <div class="card mt-4 shadow-sm">
      <div class="card-header">
        <h3 class="card-title">Pendapatan Bulanan</h3>
        <div class="card-toolbar">
          <a class="btn btn-primary btn-sm">Penjualan</a>
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

    const series = [
      {
        name: "Pendapatan",
        type: "bar",
        stacked: true,
        data: Array(12).fill(0), // Data dummy untuk 12 bulan
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
          response.data.pendapatan_bulanan.forEach((item: any) => {
            const bulanIndex = convertMonthToNumber(item.bulan); // Konversi nama bulan ke angka
            if (bulanIndex >= 1 && bulanIndex <= 12) {
              bulanData[bulanIndex - 1] = item.total_pendapatan;
            }
          });
          series[0].data = bulanData; // Update data pendapatan
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
      const baseColor = getCSSVariableValue("--bs-primary");
      const darkMode = store.isDarkMode; // Asumsikan kamu punya flag mode gelap di theme store

      return {
        chart: {
          type: "bar",
          height: 450,
          stacked: true,
          toolbar: { show: false },
        },
        plotOptions: {
          bar: {
            borderRadius: 5, // Membuat ujung batang lebih rounded
            columnWidth: "25%",
            dataLabels: {
              enabled: false, // Menghilangkan data labels di batang
            },
          },
        },
        dataLabels: {
          enabled: false, // Ini juga untuk memastikan data labels dinonaktifkan secara global
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
          enabled: true, // Aktifkan tooltip jika ingin menampilkan informasi saat hover
          y: {
            formatter: (val: number) => formatRupiah(val),
          },
          style: {
            fontSize: "12px",
            color: "#fff",
            background: darkMode ? "#333" : "#fff",
          },
        },
        colors: [baseColor],
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
    box-shadow: 0px 0px 15px rgba(61, 52, 52, 0.2) !important;
  }
}

.large-widget {
  min-height: 250px;
  width: 100%;
}

.card-xl-stretch {
  height: 350px;
}

.card-body {
  padding: 1rem; // Mengurangi padding
}

.text-primary {
  color: #0d6efd; // Warna biru untuk jumlah pelanggan
}

.text-success {
  font-size: 1.5rem; // Ukuran font yang lebih kecil
  overflow: hidden; // Menyembunyikan overflow
  text-overflow: ellipsis; // Menggunakan ellipsis jika ada overflow
  white-space: nowrap; // Mencegah teks baru
}

body.dark-mode {
  background-color: #121212; // Warna latar belakang gelap
  color: #fff; // Teks berwarna putih
}

.card-header {
  border-bottom: none;
}
</style>
