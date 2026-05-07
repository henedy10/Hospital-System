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
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        {{-- Recent Users --}}
        <div class="glass-card" style="grid-column: 1 / -1;">
            <div class="chart-header">
                <h2>Recently Registered Users</h2>
            </div>
            <div class="table-responsive" style="margin-top: 16px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                            <th style="padding: 12px; color: #64748b;">User</th>
                            <th style="padding: 12px; color: #64748b;">Role</th>
                            <th style="padding: 12px; color: #64748b;">Joined At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px; display: flex; align-items: center; gap: 12px;">
                                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" style="width: 32px; height: 32px; border-radius: 50%;">
                                <div>
                                    <div style="font-weight: 600;">{{ $user->name }}</div>
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $user->email }}</div>
                                </div>
                            </td>
                            <td style="padding: 12px;">
                                <span class="badge" style="text-transform: capitalize;">{{ $user->role }}</span>
                            </td>
                            <td style="padding: 12px; color: #64748b;">{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
