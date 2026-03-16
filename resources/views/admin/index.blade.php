@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="welcome-section" style="margin-bottom: 32px;">
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
            Admin Control Panel 🛡️
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">System-wide overview and statistics.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 32px;">
        {{-- Doctors --}}
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="stats-info">
                    <h3 style="color: #6366f1;">{{ $totalDoctors }}</h3>
                    <p>Doctors</p>
                </div>
            </div>
        </div>
        {{-- Nurses --}}
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #ec4899, #db2777);">
                    <i class="fas fa-user-nurse"></i>
                </div>
                <div class="stats-info">
                    <h3 style="color: #ec4899;">{{ $totalNurses }}</h3>
                    <p>Nurses</p>
                </div>
            </div>
        </div>
        {{-- Patients --}}
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-sky">
                    <i class="fas fa-user-injured"></i>
                </div>
                <div class="stats-info">
                    <h3 style="color: var(--primary);">{{ $totalPatients }}</h3>
                    <p>Patients</p>
                </div>
            </div>
        </div>
        {{-- Today's Appointments --}}
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-amber">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $todayAppointments }}</h3>
                    <p>Today's Appts</p>
                </div>
            </div>
        </div>
        {{-- Pending Appointments --}}
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-teal">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $pendingAppointments }}</h3>
                    <p>Pending Appts</p>
                </div>
            </div>
        </div>
        {{-- Total Appointments --}}
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-info">
                    <h3 style="color: #10b981;">{{ $totalAppointments }}</h3>
                    <p>Total Appts</p>
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        {{-- Chart --}}
        <div class="glass-card chart-container" style="grid-column: 1 / -1;">
            <div class="chart-header">
                <h2>Monthly Appointments ({{ date('Y') }})</h2>
            </div>
            <canvas id="appointmentsChart" height="90"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('appointmentsChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($monthlyData['labels']),
                    datasets: [{
                        label: 'Appointments',
                        data: @json($monthlyData['data']),
                        backgroundColor: gradient,
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(226,232,240,0.6)', drawBorder: false },
                            ticks: { font: { size: 12 }, color: '#64748b' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 12 }, color: '#64748b' }
                        }
                    }
                }
            });
        });
    </script>
@endsection
