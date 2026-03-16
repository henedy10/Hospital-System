@extends('layouts.dashboard')

@section('title', 'Doctor Dashboard')

@section('content')
    @php
        $doctorName = $doctor->name;
        $firstName = explode(' ', $doctorName)[0] ?? $doctorName;
    @endphp
    <div class="welcome-section" style="margin-bottom: 32px;">
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
            Welcome back, {{ $firstName }} 👋
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">
            Here’s your overview for today: schedule, stats, and upcoming appointments.
        </p>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-teal">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $dailyPatients }}</h3>
                    <p>Today’s Appointments</p>
                </div>
            </div>
        </div>
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-sky" style="background: #E0F7FA; color: #0288D1;">
                    <i class="fas fa-user-injured"></i>
                </div>
                <div class="stats-info">
                    <h3 style="color: var(--primary);">{{ $totalPatients }}</h3>
                    <p>Total Patients</p>
                </div>
            </div>
        </div>
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-amber">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $upcomingCount }}</h3>
                    <p>Upcoming</p>
                </div>
            </div>
        </div>
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-rose" style="background: #E8F5E9; color: #2E7D32;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $completedToday }}</h3>
                    <p>Completed Today</p>
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start; margin-bottom: 32px;">
        <!-- Chart -->
        <div class="glass-card chart-container" style="margin-bottom: 0;">
            <div class="chart-header">
                <h2>Appointments in {{ $monthlyData['year'] }}</h2>
            </div>
            <canvas id="patientsChart" height="100"></canvas>
        </div>

        <!-- Right column: Today + Upcoming -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Today's schedule -->
            <div class="glass-card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0;">
                        <i class="fas fa-calendar-day" style="color: var(--primary); margin-right: 8px;"></i>
                        Today’s Schedule
                    </h2>
                    @if($todayAppointments->isNotEmpty())
                        <a href="{{ route('doctor.appointments') }}?date={{ now()->format('Y-m-d') }}" style="font-size: 0.85rem; color: var(--primary); font-weight: 600; text-decoration: none;">View all</a>
                    @endif
                </div>
                @if($todayAppointments->isEmpty())
                    <p style="color:red; font-size: 0.9rem; margin: 0; padding: 12px 0;">No appointments scheduled for today.</p>
                @else
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($todayAppointments as $apt)
                            <li style="border-bottom: 1px solid #eef2f6; padding: 14px 0; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                                <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                                    <img src="{{ $apt->user->profile_image ? asset('storage/' . $apt->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($apt->user->name) . '&background=0D9488&color=fff' }}" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                                    <div style="min-width: 0;">
                                        <span style="font-weight: 600; color: var(--text-main); display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $apt->user->name }}</span>
                                        <span style="font-size: 0.8rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('doctor.patients.show', $apt->user_id) }}" class="btn-primary-sm" style="text-decoration: none; padding: 6px 12px; font-size: 0.75rem; white-space: nowrap;">Profile</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Upcoming -->
            <div class="glass-card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0;">
                        <i class="fas fa-forward" style="color: var(--primary); margin-right: 8px;"></i>
                        Upcoming
                    </h2>
                    <a href="{{ route('doctor.appointments') }}?status=upcoming" style="font-size: 0.85rem; color: var(--primary); font-weight: 600; text-decoration: none;">View all</a>
                </div>
                @if($upcomingAppointments->isEmpty())
                    <p style="color: red; font-size: 0.9rem; margin: 0; padding: 12px 0;">No upcoming appointments.</p>
                @else
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($upcomingAppointments as $apt)
                            <li style="border-bottom: 1px solid #eef2f6; padding: 14px 0; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                                <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                                    <img src="{{ $apt->patient->user->profile_image ? asset('storage/' . $apt->patient->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($apt->patient->user->name) . '&background=0D9488&color=fff' }}" alt="" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                                    <div style="min-width: 0;">
                                        <span style="font-weight: 600; color: var(--text-main); display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $apt->patient->user->name }}</span>
                                        <span style="font-size: 0.8rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M j') }} · {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</span>
                                    </div>
                                </div>
                                <a href="#" class="btn-primary-sm" style="text-decoration: none; padding: 5px 10px; font-size: 0.75rem; white-space: nowrap;">Profile</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
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
                        label: 'Appointments',
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
                        legend: { display: false },
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
                            grid: { display: true, color: 'rgba(226, 232, 240, 0.6)', drawBorder: false },
                            ticks: { font: { size: 12, weight: '500' }, color: '#64748b' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 12, weight: '500' }, color: '#64748b' }
                        }
                    }
                }
            });
        });
    </script>

    <style>
        @media (max-width: 1024px) {
            div[style*="grid-template-columns: 1fr 380px"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
