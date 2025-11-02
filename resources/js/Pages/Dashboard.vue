<template>
  <Layout>
    <div class="ml-64 min-h-screen p-0 flex-1 bg-gradient-to-br from-blue-50 to-blue-200">
      <div class="max-w-7xl mx-auto py-10 px-4">
      

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
          <div class="bg-gradient-to-br from-blue-200 to-blue-400 p-6 rounded-2xl shadow-lg flex flex-col items-center hover:scale-105 transition-transform">
            <i class="fas fa-book text-4xl text-white mb-3"></i>
            <h3 class="text-lg font-semibold text-white mb-1">Total Books</h3>
            <p class="text-3xl font-extrabold text-white">{{ stats.totalBooks }}</p>
          </div>
          <div class="bg-gradient-to-br from-green-200 to-green-400 p-6 rounded-2xl shadow-lg flex flex-col items-center hover:scale-105 transition-transform">
            <i class="fas fa-book-reader text-4xl text-white mb-3"></i>
            <h3 class="text-lg font-semibold text-white mb-1">Total Borrows</h3>
            <p class="text-3xl font-extrabold text-white">{{ stats.totalBorrowings }}</p>
          </div>
          <div class="bg-gradient-to-br from-yellow-200 to-yellow-400 p-6 rounded-2xl shadow-lg flex flex-col items-center hover:scale-105 transition-transform">
            <i class="fas fa-undo-alt text-4xl text-white mb-3"></i>
            <h3 class="text-lg font-semibold text-white mb-1">Total Returns</h3>
            <p class="text-3xl font-extrabold text-white">{{ stats.totalReturns }}</p>
          </div>
          <div class="bg-gradient-to-br from-purple-200 to-purple-400 p-6 rounded-2xl shadow-lg flex flex-col items-center hover:scale-105 transition-transform">
            <i class="fas fa-users text-4xl text-white mb-3"></i>
            <h3 class="text-lg font-semibold text-white mb-1">Active Borrowings</h3>
            <p class="text-3xl font-extrabold text-white">{{ stats.activeBorrowings }}</p>
            <p class="text-sm text-white mt-2">Active reservations: <span class="font-semibold">{{ stats.activeReservations }}</span>
              <span class="ml-2 text-xs opacity-80" title="Active borrowings counts unique books currently borrowed (not returned). Active reservations counts unique approved reservations with until date today or later.">ℹ️</span>
            </p>
          </div>
        </div>

      
        <!-- Monthly Borrows & Reservations Chart -->
        <div class="mt-6 bg-white p-6 rounded-lg shadow-lg">
          <div class="flex items-start justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">Monthly Borrows & Reservations</h3>
            <div class="flex items-center space-x-2">
              <button @click="downloadChartPNG" class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                <i class="fas fa-download mr-2"></i> PNG
              </button>
              <button @click="downloadChartCSV" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                <i class="fas fa-file-csv mr-2"></i> CSV
              </button>
            </div>
          </div>
          <div class="w-full h-72">
            <canvas ref="borrowChartCanvas" class="w-full h-full"></canvas>
          </div>
        </div>

        <!-- Yearly Borrows & Reservations Chart -->
        <div class="mt-6 bg-white p-6 rounded-lg shadow-lg">
          <div class="flex items-start justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">Yearly Borrows & Reservations</h3>
            <div class="flex items-center space-x-2">
              <button @click="downloadYearChartPNG" class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                <i class="fas fa-download mr-2"></i> PNG
              </button>
              <button @click="downloadYearChartCSV" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                <i class="fas fa-file-csv mr-2"></i> CSV
              </button>
            </div>
          </div>
          <div class="w-full h-60">
            <canvas ref="borrowYearChartCanvas" class="w-full h-full"></canvas>
          </div>
        </div>

        <!-- Top 5 Most Borrowed Books (aesthetic card list) -->
        <div class="mt-8 bg-gradient-to-br from-white to-slate-50 p-6 rounded-2xl shadow-xl">
          <h3 class="text-xl font-bold text-gray-800 mb-4">Top 5 Most Borrowed Books</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div v-for="(book, idx) in topBooks" :key="book.title" class="flex items-center space-x-4 p-4 bg-white/80 backdrop-blur-sm border border-transparent hover:border-indigo-100 rounded-xl transition-shadow transition-transform hover:shadow-2xl hover:-translate-y-1">
              <div class="flex-shrink-0 w-14 h-14 rounded-lg bg-gradient-to-br from-indigo-50 to-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-lg shadow-sm">
                <div class="text-sm">#{{ idx + 1 }}</div>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                  <div class="text-gray-800 font-semibold truncate">{{ book.title }}</div>
                  <div class="text-indigo-600 font-bold text-lg">{{ book.count }}</div>
                </div>
                <p class="text-xs text-gray-400 mt-1 truncate">Total borrows</p>
                <div class="mt-3 h-2 bg-indigo-100 rounded-full overflow-hidden">
                  <div class="h-full rounded-full progress-bar-inner transition-width" :data-width="Math.max(6, Math.round((book.count / topBooksMax) * 100)) + '%'" :style="{ background: 'linear-gradient(90deg,#6366f1,#7c3aed)', width: '0%' }"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        

        <!-- Top 5 Most Request Users (aesthetic card list) -->
        <div class="mt-8 bg-gradient-to-br from-white to-slate-50 p-6 rounded-2xl shadow-xl">
          <h3 class="text-xl font-bold text-gray-800 mb-4">Top 5 Most Request Users</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div v-for="(user, idx) in topRequestUsers" :key="user.fullname" class="flex items-center space-x-4 p-4 bg-white/80 backdrop-blur-sm border border-transparent hover:border-green-100 rounded-xl transition-shadow transition-transform hover:shadow-2xl hover:-translate-y-1">
              <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center text-emerald-700 font-semibold shadow-sm">
                {{ user.fullname.charAt(0).toUpperCase() }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                  <div class="text-gray-800 font-semibold truncate">{{ user.fullname }}</div>
                  <div class="text-emerald-600 font-bold text-lg">{{ user.total_requests }}</div>
                </div>
                <p class="text-xs text-gray-400 mt-1 truncate">Requests (borrow & reserve)</p>
                <div class="mt-3 h-2 bg-emerald-100 rounded-full overflow-hidden">
                  <div class="h-full rounded-full progress-bar-inner transition-width" :data-width="Math.max(6, Math.round((user.total_requests / topRequestUsersMax) * 100)) + '%'" :style="{ background: 'linear-gradient(90deg,#10b981,#059669)', width: '0%' }"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import { onMounted } from "vue";
import Layout from '../Layouts/FrontEndLayout.vue';

import { Chart } from "chart.js/auto";
import { Link } from "@inertiajs/vue3";

export default {
  components: {
    Link,
    Layout
  },
  props: {
    stats: Object,
    loginsData: Object,
    borrowMonthly: Array,
    reserveMonthly: Array,
    monthLabels: Array,
    borrowYearly: Array,
    reserveYearly: Array,
    yearLabels: Array,
    topBooks: Array,
    topRequestUsers: Array,
    bookActions: Array,
  },
  mounted() {
    this.initChart();
    this.initYearChart();
    // animate progress bars after the DOM updates
    this.$nextTick(() => {
      this.animateTopBars();
    });
  },
  data() {
    return {
      borrowChart: null,
      borrowYearChart: null,
    };
  },
  computed: {
    topBooksMax() {
      if (!this.topBooks || !this.topBooks.length) return 1;
      return Math.max(...this.topBooks.map(b => b.count || 0));
    },
    topRequestUsersMax() {
      if (!this.topRequestUsers || !this.topRequestUsers.length) return 1;
      return Math.max(...this.topRequestUsers.map(u => u.total_requests || 0));
    }
  },
  // no computed props needed: backend now supplies distinct counts
  methods: {
    initChart() {
      // Create a visually pleasing line chart for borrows and reservations
      if (this.$refs.borrowChartCanvas) {
        const ctx = this.$refs.borrowChartCanvas.getContext('2d');

        // destroy existing chart instance if present
        if (this.borrowChart) {
          try { this.borrowChart.destroy(); } catch (e) { /* ignore */ }
          this.borrowChart = null;
        }

        // create gradients
        const gradBorrows = ctx.createLinearGradient(0, 0, 0, 200);
        gradBorrows.addColorStop(0, 'rgba(99,102,241,0.85)');
        gradBorrows.addColorStop(1, 'rgba(99,102,241,0.1)');

        const gradReserves = ctx.createLinearGradient(0, 0, 0, 200);
        gradReserves.addColorStop(0, 'rgba(16,185,129,0.85)');
        gradReserves.addColorStop(1, 'rgba(16,185,129,0.08)');

        const chart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: this.monthLabels || ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [
              {
                label: 'Borrows',
                data: this.borrowMonthly || [],
                fill: true,
                backgroundColor: gradBorrows,
                borderColor: 'rgba(99,102,241,1)',
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(99,102,241,1)',
                tension: 0.35,
                pointRadius: 4,
                borderWidth: 2,
                yAxisID: 'y',
              },
              {
                label: 'Reservations',
                data: this.reserveMonthly || [],
                fill: true,
                backgroundColor: gradReserves,
                borderColor: 'rgba(16,185,129,1)',
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(16,185,129,1)',
                tension: 0.35,
                pointRadius: 4,
                borderWidth: 2,
                yAxisID: 'y',
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
              mode: 'index',
              intersect: false,
            },
            plugins: {
              legend: {
                labels: { color: '#334155', usePointStyle: true, padding: 16 }
              },
              tooltip: {
                backgroundColor: '#0f172a',
                titleColor: '#fff',
                bodyColor: '#e6eef8',
                padding: 10,
                cornerRadius: 6,
              }
            },
            scales: {
              x: {
                grid: { display: false },
                ticks: { color: '#475569', maxRotation: 0 }
              },
              y: {
                beginAtZero: true,
                grid: { color: 'rgba(15,23,42,0.06)' },
                ticks: { color: '#475569', stepSize: 1 }
              }
            }
          }
        });

        this.borrowChart = chart;
      }
    },
    initYearChart() {
      if (this.$refs.borrowYearChartCanvas) {
        const ctx = this.$refs.borrowYearChartCanvas.getContext('2d');

        if (this.borrowYearChart) {
          try { this.borrowYearChart.destroy(); } catch (e) { }
          this.borrowYearChart = null;
        }

        const gradBorrows = ctx.createLinearGradient(0, 0, 0, 120);
        gradBorrows.addColorStop(0, 'rgba(99,102,241,0.85)');
        gradBorrows.addColorStop(1, 'rgba(99,102,241,0.12)');

        const gradReserves = ctx.createLinearGradient(0, 0, 0, 120);
        gradReserves.addColorStop(0, 'rgba(16,185,129,0.85)');
        gradReserves.addColorStop(1, 'rgba(16,185,129,0.12)');

        const chart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: this.yearLabels || [],
            datasets: [
              {
                label: 'Borrows',
                data: this.borrowYearly || [],
                backgroundColor: gradBorrows,
                borderColor: 'rgba(99,102,241,1)',
                borderWidth: 1,
              },
              {
                label: 'Reservations',
                data: this.reserveYearly || [],
                backgroundColor: gradReserves,
                borderColor: 'rgba(16,185,129,1)',
                borderWidth: 1,
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
              legend: { labels: { color: '#334155', padding: 12 } },
              tooltip: {
                backgroundColor: '#0f172a',
                titleColor: '#fff',
                bodyColor: '#e6eef8',
                padding: 8,
                cornerRadius: 6,
              }
            },
            scales: {
              x: { grid: { display: false }, ticks: { color: '#475569' } },
              y: { beginAtZero: true, grid: { color: 'rgba(15,23,42,0.06)' }, ticks: { color: '#475569' } }
            }
          }
        });

        this.borrowYearChart = chart;
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    }
    ,
    downloadChartPNG() {
      try {
        const year = new Date().getFullYear();
        if (!this.borrowChart) {
          // fallback to canvas
          const canvas = this.$refs.borrowChartCanvas;
          if (!canvas) return;
          const url = canvas.toDataURL('image/png');
          const a = document.createElement('a');
          a.href = url;
          a.download = `monthly-borrows-reservations-${year}.png`;
          document.body.appendChild(a);
          a.click();
          a.remove();
          return;
        }

        const url = this.borrowChart.toBase64Image();
        const a = document.createElement('a');
        a.href = url;
        a.download = `monthly-borrows-reservations-${year}.png`;
        document.body.appendChild(a);
        a.click();
        a.remove();
      } catch (e) {
        console.error('Export PNG failed', e);
      }
    },
    downloadChartCSV() {
      try {
        const year = new Date().getFullYear();
        const labels = this.monthLabels || ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const borrows = this.borrowMonthly || Array(12).fill(0);
        const reserves = this.reserveMonthly || Array(12).fill(0);

        let csv = 'Month,Borrows,Reservations\n';
        for (let i = 0; i < labels.length; i++) {
          csv += `${labels[i]},${borrows[i] ?? 0},${reserves[i] ?? 0}\n`;
        }

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `monthly-borrows-reservations-${year}.csv`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
      } catch (e) {
        console.error('Export CSV failed', e);
      }
    }
  },
    downloadYearChartPNG() {
      try {
        const yearRange = (this.yearLabels || []).join('-') || new Date().getFullYear();
        if (!this.borrowYearChart) {
          const canvas = this.$refs.borrowYearChartCanvas;
          if (!canvas) return;
          const url = canvas.toDataURL('image/png');
          const a = document.createElement('a');
          a.href = url;
          a.download = `yearly-borrows-reservations-${yearRange}.png`;
          document.body.appendChild(a);
          a.click();
          a.remove();
          return;
        }
        const url = this.borrowYearChart.toBase64Image();
        const a = document.createElement('a');
        a.href = url;
        a.download = `yearly-borrows-reservations-${yearRange}.png`;
        document.body.appendChild(a);
        a.click();
        a.remove();
      } catch (e) { console.error('Export Year PNG failed', e); }
    },
    downloadYearChartCSV() {
      try {
        const labels = this.yearLabels || [];
        const borrows = this.borrowYearly || [];
        const reserves = this.reserveYearly || [];
        let csv = 'Year,Borrows,Reservations\n';
        for (let i = 0; i < labels.length; i++) {
          csv += `${labels[i]},${borrows[i] ?? 0},${reserves[i] ?? 0}\n`;
        }
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `yearly-borrows-reservations-${labels.join('-') || new Date().getFullYear()}.csv`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
      } catch (e) { console.error('Export Year CSV failed', e); }
    }
    ,
    animateTopBars() {
      try {
        const bars = this.$el.querySelectorAll('.progress-bar-inner');
        bars.forEach((bar, idx) => {
          const target = bar.getAttribute('data-width') || bar.dataset.width || '0%';
          // set initial width to 0 (already set), then animate to target with a small stagger
          bar.style.width = '0%';
          setTimeout(() => {
            bar.style.width = target;
          }, 120 + idx * 90);
        });
      } catch (e) {
        // fail silently
        console.error('animateTopBars error', e);
      }
    }
};
</script>
<style scoped>
/* Smooth transition for progress bars */
.transition-width {
  transition: width 800ms cubic-bezier(.2,.9,.2,1);
}

/* Slight card lift animation */
.hover-lift:hover {
  transform: translateY(-6px);
}
</style>
