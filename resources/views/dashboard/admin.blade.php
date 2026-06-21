@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    /* Animasi Card Entrance (Fade In & Slide Up) */
    .animate-card {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    /* Staggered Delay untuk Card Statistic */
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }

    /* Staggered Delay untuk Chart */
    .delay-chart-1 { animation-delay: 0.5s; }
    .delay-chart-2 { animation-delay: 0.6s; }
    .delay-chart-3 { animation-delay: 0.7s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hover effect halus untuk card stat */
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }

    /* Transisi ikon membesar saat di-hover */
    .stat-card:hover .stat-icon {
        transform: scale(1.15) rotate(5deg);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .stat-icon {
        transition: transform 0.3s ease;
    }
</style>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Admin Dashboard</li>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row">
    <!-- Card 1: Total Event -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100 border-0 border-start border-primary border-4 shadow-sm animate-card delay-1 rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 700;">Total Event</div>
                        <div class="h3 mb-0 fw-bold text-slate-800">{{ $totalEvent }}</div>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle stat-icon shadow-sm">
                        <i class="bi bi-calendar2-week-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Peserta -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100 border-0 border-start border-success border-4 shadow-sm animate-card delay-2 rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 700;">Total Peserta</div>
                        <div class="h3 mb-0 fw-bold text-slate-800">{{ $totalPeserta }}</div>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-circle stat-icon shadow-sm">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Registrasi -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100 border-0 border-start border-warning border-4 shadow-sm animate-card delay-3 rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 700;">Total Registrasi</div>
                        <div class="h3 mb-0 fw-bold text-slate-800">{{ $totalRegistrasi }}</div>
                    </div>
                    <div class="bg-warning-subtle text-warning p-3 rounded-circle stat-icon shadow-sm">
                        <i class="bi bi-clipboard2-check-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Total Pendapatan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100 border-0 border-start border-danger border-4 shadow-sm animate-card delay-4 rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 700;">Total Pendapatan</div>
                        <div class="h3 mb-0 fw-bold text-slate-800" style="font-size: 1.4rem;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-danger-subtle text-danger p-3 rounded-circle stat-icon shadow-sm">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <!-- Chart 1: Jumlah Registrasi per Bulan -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card shadow-sm h-100 border-0 animate-card delay-chart-1 rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-graph-up text-primary me-2"></i>Jumlah Registrasi per Bulan</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <div style="position: relative; height:320px; width:100%">
                    <canvas id="registrasiBulanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 3: Status Pembayaran -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow-sm h-100 border-0 animate-card delay-chart-2 rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-pie-chart-fill text-warning me-2"></i>Status Pembayaran</h6>
            </div>
            <div class="card-body px-4 pb-4 d-flex flex-column justify-content-center">
                <div style="position: relative; height:240px; width:100%">
                    <canvas id="statusPembayaranChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart 2: Jumlah Peserta per Event -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0 animate-card delay-chart-3 rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-bar-chart-fill text-success me-2"></i>Jumlah Peserta per Event</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <div style="position: relative; height:320px; width:100%">
                    <canvas id="pesertaEventChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Konfigurasi font global untuk chart agar senada dengan UI
    Chart.defaults.font.family = "'Outfit', sans-serif";
    Chart.defaults.color = '#64748b'; // text-slate-500

    // Chart 1: Jumlah Registrasi per Bulan
    const ctx1 = document.getElementById('registrasiBulanChart').getContext('2d');
    
    // Membuat gradient untuk Line Chart
    let gradientLine = ctx1.createLinearGradient(0, 0, 0, 400);
    gradientLine.addColorStop(0, 'rgba(37, 99, 235, 0.4)'); 
    gradientLine.addColorStop(1, 'rgba(37, 99, 235, 0.0)'); 

    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart1Labels) !!},
            datasets: [{
                label: 'Pendaftaran',
                data: {!! json_encode($chart1Data) !!},
                backgroundColor: gradientLine,
                borderColor: '#2563EB',
                borderWidth: 3,
                tension: 0.4, 
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#2563EB',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#2563EB'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                y: { duration: 1500, easing: 'easeOutQuart' } 
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                },
                x: {
                    grid: { display: false, drawBorder: false }
                }
            }
        }
    });

    // Chart 2: Jumlah Peserta per Event
    const ctx2 = document.getElementById('pesertaEventChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chart2Labels) !!},
            datasets: [{
                label: 'Jumlah Peserta (Diterima)',
                data: {!! json_encode($chart2Data) !!},
                backgroundColor: '#22C55E', 
                borderRadius: { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 },
                maxBarThickness: 40,
                hoverBackgroundColor: '#16A34A'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                y: { duration: 1200, easing: 'easeOutBounce', delay: 300 } 
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: {
                        callback: function(val, index) {
                            const label = this.getLabelForValue(val);
                            return label.length > 20 ? label.substr(0, 20) + '...' : label;
                        }
                    }
                }
            }
        }
    });

    // Chart 3: Status Pembayaran 
    const ctx3 = document.getElementById('statusPembayaranChart').getContext('2d');
    new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chart3Labels) !!},
            datasets: [{
                data: {!! json_encode($chart3Data) !!},
                backgroundColor: [
                    '#F59E0B', 
                    '#10B981', 
                    '#EF4444'  
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 6 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                animateScale: true, 
                animateRotate: true, 
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { 
                        boxWidth: 12, 
                        padding: 20, 
                        usePointStyle: true, 
                        font: { weight: '600' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            cutout: '70%' 
        }
    });
</script>
@endsection