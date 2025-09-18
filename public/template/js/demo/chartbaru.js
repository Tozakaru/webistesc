
let trendChart;

// Fetch data dari backend Laravel
async function fetchChartData(days) {
  try {
    const response = await fetch(`/dashboard/chart-data?days=${days}`);
    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error fetching chart data:', error);
    return null;
  }
}

async function initChart() {
  const ctx = document.getElementById('trendChart').getContext('2d');

  // Fetch awal (7 hari)
  const initialData = await fetchChartData(7);
  if (!initialData) {
    document.querySelector('.chart-area').innerHTML =
      '<p class="text-center text-muted">Gagal memuat data grafik</p>';
    return;
  }

  trendChart = new Chart(ctx, {
    type: 'bar',                 // <-- histogram
    data: {
      labels: initialData.labels,
      datasets: [
        {
          label: 'Masuk Valid',
          data: initialData.valid,
          backgroundColor: 'rgba(40,167,69,0.7)',
          borderColor: '#28a745',
          borderWidth: 1,
          borderRadius: 6,
          maxBarThickness: 32,   // batasi ketebalan biar rapi
        },
        {
          label: 'Scan Invalid',
          data: initialData.invalid,
          backgroundColor: 'rgba(220,53,69,0.7)',
          borderColor: '#dc3545',
          borderWidth: 1,
          borderRadius: 6,
          maxBarThickness: 32,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,

      // set ke 'y' kalau mau bar horizontal:
      // indexAxis: 'y',

      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            usePointStyle: false,
            padding: 16,
            font: { size: 12 }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0,0,0,0.85)',
          titleColor: '#fff',
          bodyColor: '#fff',
          borderColor: '#ddd',
          borderWidth: 1,
          cornerRadius: 8,
          displayColors: true,
          callbacks: {
            label: function(ctx) {
              const val = ctx.parsed.y ?? ctx.parsed; // vertikal/horizontal safe
              return `${ctx.dataset.label}: ${val} orang`;
            }
          }
        }
      },
      scales: {
        x: {
          stacked: false, // set true kalau mau ditumpuk
          grid: { display: false },
          ticks: { color: '#666', font: { size: 11 } }
        },
        y: {
          beginAtZero: true,
          stacked: false, // set true kalau mau ditumpuk
          grid: { color: 'rgba(0,0,0,0.05)' },
          ticks: {
            color: '#666',
            font: { size: 11 },
            // stepSize: 2, // bisa aktifkan kalau mau langkah tetap
            callback: (v) => v + ' org'
          }
        }
      },
      interaction: {
        intersect: false,
        mode: 'index'
      }
    }
  });

  // kalau kamu punya ringkasan angka
  if (typeof updateStats === 'function') updateStats(initialData);
}

async function updateChart(period) {
  const data = await fetchChartData(period);
  if (!data) return;

  trendChart.data.labels = data.labels;
  trendChart.data.datasets[0].data = data.valid;
  trendChart.data.datasets[1].data = data.invalid;

  trendChart.update('active');
}

// Dropdown periode
document.getElementById('trendPeriod').addEventListener('change', function () {
  const period = parseInt(this.value);
  updateChart(period);
});

// init saat halaman siap
document.addEventListener('DOMContentLoaded', initChart);