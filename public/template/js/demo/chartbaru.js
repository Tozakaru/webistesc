
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
            
            // Fetch initial data (7 days)
            const initialData = await fetchChartData(7);
            
            if (!initialData) {
                document.querySelector('.chart-area').innerHTML = '<p class="text-center text-muted">Gagal memuat data grafik</p>';
                return;
            }
            
            trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: initialData.labels,
                    datasets: [{
                        label: 'Masuk Valid',
                        data: initialData.valid,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#28a745',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Scan Invalid',
                        data: initialData.invalid,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#ddd',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' mahasiswa';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#666',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#666',
                                font: {
                                    size: 11
                                },
                                stepSize: 2,
                                callback: function(value) {
                                    return value + ' org';
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        line: {
                            tension: 0.4
                        }
                    }
                }
            });

            updateStats(initialData);
        }

        async function updateChart(period) {
            const data = await fetchChartData(period);
            
            if (data) {
                trendChart.data.labels = data.labels;
                trendChart.data.datasets[0].data = data.valid;
                trendChart.data.datasets[1].data = data.invalid;
                
                trendChart.update('active');
            }
        }



        // Event listener untuk dropdown
        document.getElementById('trendPeriod').addEventListener('change', function() {
            const period = parseInt(this.value);
            updateChart(period);
        });

        // Initialize chart when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initChart();
        });
