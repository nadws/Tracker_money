<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard Keuangan') }}
    </x-slot>

    <div class="space-y-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm shadow-slate-100/40 flex items-center min-w-0">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl shrink-0">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div class="ms-4 truncate">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Saldo</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight whitespace-nowrap">Rp
                        {{ number_format($saldo ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm shadow-slate-100/40 flex items-center min-w-0">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="ms-4 truncate">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Pemasukan</p>
                    <p class="text-2xl font-bold text-emerald-600 tracking-tight whitespace-nowrap">Rp
                        {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm shadow-slate-100/40 flex items-center min-w-0">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl shrink-0">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
                <div class="ms-4 truncate">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-rose-600 tracking-tight whitespace-nowrap">Rp
                        {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm shadow-slate-100/40">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-800">Tren Arus Kas</h3>
                <p class="text-xs text-slate-400">Analisis real-time pemasukan dan pengeluaran Anda dari waktu ke waktu
                </p>
            </div>

            <div class="relative h-80 w-full">
                <canvas id="financeChart"></canvas>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mengambil data JSON dari Laravel dengan fallback array kosong jika data tidak ada
            const rawData = @json($chartData ?? []);

            // Jika tidak ada data transaksi sama sekali, tampilkan log agar mudah di-debug
            if (!rawData || rawData.length === 0) {
                console.warn("Data chart kosong atau tidak terkirim dari Controller.");
            }

            // Fungsi untuk memformat visual tanggal (Misal: 2026-03-05 menjadi 05 Mar)
            const formatDate = (dateString) => {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short'
                });
            };

            const labels = rawData.map(item => formatDate(item.transaction_date));
            const incomeData = rawData.map(item => Number(item.income || 0));
            const expenseData = rawData.map(item => Number(item.expense || 0));

            const ctx = document.getElementById('financeChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Pemasukan (Rp)',
                            data: incomeData,
                            borderColor: '#10b981', // emerald-500
                            backgroundColor: 'rgba(16, 185, 129, 0.04)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 3,
                            pointRadius: rawData.length > 30 ? 1 :
                            4, // Sembunyikan titik dot jika data terlalu padat
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        },
                        {
                            label: 'Pengeluaran (Rp)',
                            data: expenseData,
                            borderColor: '#f43f5e', // rose-500
                            backgroundColor: 'rgba(244, 63, 94, 0.04)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 3,
                            pointRadius: rawData.length > 30 ? 1 : 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#f43f5e',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 10,
                                boxHeight: 10,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    family: 'Figtree',
                                    size: 13,
                                    weight: '600'
                                },
                                color: '#64748b',
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: {
                                family: 'Figtree',
                                size: 13,
                                weight: '600'
                            },
                            bodyFont: {
                                family: 'Figtree',
                                size: 13
                            },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label = label.split(' ')[0] + ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: {
                                    family: 'Figtree',
                                    size: 11
                                },
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            },
                            border: {
                                dash: [5, 5]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: {
                                    family: 'Figtree',
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
