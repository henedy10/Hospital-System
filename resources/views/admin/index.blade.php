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

        {{-- Recent Users --}}
        <div class="glass-card" style="grid-column: 1 / -1; padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 1.1rem; font-weight: 600; color: var(--text-main);">Recent Registrations</h2>
                <a href="{{ route('admin.users.index') }}"
                    style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 500;">
                    View All <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead>
                        <tr
                            style="border-bottom: 1px solid #e2e8f0; color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">
                            <th style="padding: 10px 12px; text-align: left; font-weight: 600;">Name</th>
                            <th style="padding: 10px 12px; text-align: left; font-weight: 600;">Email</th>
                            <th style="padding: 10px 12px; text-align: left; font-weight: 600;">Role</th>
                            <th style="padding: 10px 12px; text-align: left; font-weight: 600;">Joined</th>
                            <th style="padding: 10px 12px; text-align: left; font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $u)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;"
                                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                                <td style="padding: 12px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <img src="{{ $u->profile_image ? asset('storage/' . $u->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=0D9488&color=fff&size=36' }}"
                                            alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                                        <span style="font-weight: 500; color: var(--text-main);">{{ $u->name }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px; color: var(--text-muted);">{{ $u->email }}</td>
                                <td style="padding: 12px;">
                                    @php
                                        $roleColors = [
                                            'admin' => ['bg' => '#fef3c7', 'text' => '#d97706'],
                                            'doctor' => ['bg' => '#ede9fe', 'text' => '#7c3aed'],
                                            'nurse' => ['bg' => '#fce7f3', 'text' => '#be185d'],
                                            'patient' => ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                                        ];
                                        $rc = $roleColors[$u->role] ?? ['bg' => '#e5e7eb', 'text' => '#374151'];
                                    @endphp
                                    <span
                                        style="padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; background: {{ $rc['bg'] }}; color: {{ $rc['text'] }};">
                                        {{ ucfirst($u->role) }}
                                    </span>
                                </td>
                                <td style="padding: 12px; color: var(--text-muted);">
                                    {{ $u->created_at->format('M d, Y') }}
                                </td>
                                <td style="padding: 12px;">
                                    @unless($u->isAdmin())
                                        <a href="{{ route('admin.users.edit', $u) }}"
                                            style="color: #6366f1; text-decoration: none; font-size: 0.8rem; font-weight: 500;">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endunless
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 24px; text-align: center; color: var(--text-muted);">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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