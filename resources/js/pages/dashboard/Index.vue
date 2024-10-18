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

    <!-- Grafik Pendapatan -->
    <div class="card mt-4 shadow-sm">
      <div class="card-header">
        <h3 class="card-title">Pendapatan Bulanan</h3>
      </div>
      <div class="card-body">
        <apexchart ref="chartRef" type="bar" :options="chart" :series="series" width="100%" height="250"></apexchart>
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

// Fungsi untuk mengubah nama bulan ke angka (1-12)
const convertMonthToNumber = (month: string) => {
  const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  return months.indexOf(month) + 1; // indexOf mengembalikan -1 jika tidak ditemukan, tambahkan 1 agar sesuai bulan
};

export default defineComponent({
  name: "DashboardWidgets",
  setup() {
    const pelanggan = ref(0);
    const pendapatan = ref(0);
    const tiket = ref(0);

    const chartRef = ref<typeof VueApexCharts | null>(null);
    const chart = ref<ApexOptions>({});

    const series = ref([
      {
        name: "Pendapatan",
        type: "bar",
        stacked: true,
        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], // Dummy data untuk 12 bulan
      },
    ]);

    const fetchData = async () => {
      try {
        const response = await axios.get("/dashboard");
        pelanggan.value = response.data.pelanggan;
        pendapatan.value = response.data.pendapatan;
        tiket.value = response.data.tiket;

        if (response.data.pendapatan_bulanan && Array.isArray(response.data.pendapatan_bulanan)) {
          const bulanData = Array(12).fill(0); // Inisialisasi array untuk 12 bulan
          response.data.pendapatan_bulanan.forEach((item: any) => {
            const bulanIndex = convertMonthToNumber(item.bulan); // Konversi bulan dari string ke angka
            if (bulanIndex >= 1 && bulanIndex <= 12) {
              bulanData[bulanIndex - 1] = item.total_pendapatan; // Update dengan pendapatan sebenarnya
            }
          });
          series.value[0].data = bulanData;
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

      return {
        chart: {
          type: "bar",
          height: 250, // Ukuran chart diperkecil
          stacked: true,
        },
        plotOptions: {
          bar: {
            columnWidth: "55%",
          },
        },
        xaxis: {
          categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], // Kategori untuk 12 bulan
        },
        yaxis: {
          title: {
            text: "Pendapatan (Rp)",
          },
          labels: {
            formatter: (val: number) => formatRupiah(val), // Format label sumbu Y
          },
        },
        tooltip: {
          y: {
            formatter: (val: number) => formatRupiah(val), // Format tooltip
          },
        },
        colors: [baseColor],
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
  height: 250px; /* Tinggi widget lebih kecil */
}
</style>
