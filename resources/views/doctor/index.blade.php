@extends('layouts.dashboard')

@section('title', 'Doctor Dashboard')

@section('content')
    <div class="welcome-section" style="margin-bottom: 32px;">
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">مرحباً دكتور، أهلاً
            بعودتك! 👋</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">إليك نظرة سريعة على إحصائياتك اليومية ونشاط المرضى.</p>
    </div>

    <div class="stats-grid">
        <!-- Daily Patients -->
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-teal">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $dailyPatients }}</h3>
                    <p>عدد المرضى يومياً</p>
                </div>
            </div>
        </div>

        <!-- Emergency Cases -->
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-rose">
                    <i class="fas fa-ambulance"></i>
                </div>
                <div class="stats-info">
                    <h3 style="color: var(--danger);">{{ $emergencyCases }}</h3>
                    <p>الحالات الطارئة</p>
                </div>
            </div>
        </div>

        <!-- Total Appointments (Optional extra) -->
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-amber">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-info">
                    <h3>12</h3>
                    <p>المواعيد القادمة</p>
                </div>
            </div>
        </div>
    </div>

    <div class="glass-card chart-container">
        <div class="chart-header">
            <h2>رسم بياني لعدد المرضى شهرياً</h2>
            <div class="chart-actions">
                <select
                    style="padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem; outline: none;">
                    <option>2023</option>
                    <option>2024</option>
                </select>
            </div>
        </div>
        <canvas id="patientsChart" height="100"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('patientsChart').getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(13, 148, 136, 0.2)');
            gradient.addColorStop(1, 'rgba(13, 148, 136, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($monthlyData['labels']),
                    datasets: [{
                        label: 'عدد المرضى',
                        data: @json($monthlyData['data']),
                        borderColor: '#0D9488',
                        borderWidth: 3,
                        fill: true,
                        backgroundColor: gradient,
                        tension: 0.4,
                        pointBackgroundColor: '#0D9488',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(226, 232, 240, 0.6)',
                                drawBorder: false
                            },
                            ticks: {
                                font: { size: 12, weight: '500' },
                                color: '#64748b'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { size: 12, weight: '500' },
                                color: '#64748b'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection