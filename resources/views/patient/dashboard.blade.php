@extends('layouts.dashboard')

@section('title', 'Patient Dashboard')

@section('content')
    @php
        $firstName = explode(' ', $user->name)[0] ?? $user->name;
    @endphp
    <div class="welcome-section" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
                Welcome back, {{ $firstName }} 👋
            </h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">
                Here’s an overview of your appointments and health info.
            </p>
        </div>
        <a href="{{ route('patient.appointments') }}" class="btn-primary" style="width: auto; padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-calendar-plus"></i> Book appointment
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-teal">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $nextAppointment ? \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('M d') : '—' }}</h3>
                    <p>Next appointment</p>
                    @if($nextAppointment)
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">{{ $nextAppointment->doctor_name }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-sky" style="background: #E0F7FA; color: #0288D1;">
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
                <div class="icon-box bg-amber">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $medicalRecordsCount }}</h3>
                    <p>Medical records</p>
                </div>
            </div>
        </div>
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-rose" style="background: #FCE7F3; color: #DB2777;">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="stats-info">
                    @if($latestVitals)
                        <h3 style="font-size: 1rem;">{{ $latestVitals->blood_pressure ?? '—' }}</h3>
                        <p>Latest vitals (BP)</p>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Temp {{ $latestVitals->temperature ?? '—' }}° · HR {{ $latestVitals->heart_rate ?? '—' }}</p>
                    @else
                        <h3>—</h3>
                        <p>Latest vitals</p>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">No readings yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;">
        <!-- Upcoming Appointments -->
        <div class="glass-card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0;">
                    <i class="fas fa-calendar-alt" style="color: var(--primary); margin-right: 8px;"></i>
                    Upcoming appointments
                </h2>
                <a href="{{ route('patient.appointments') }}" style="font-size: 0.85rem; color: var(--primary); font-weight: 600; text-decoration: none;">View all</a>
            </div>
            @if($upcomingAppointments->isEmpty())
                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0; padding: 12px 0;">No upcoming appointments. <a href="{{ route('patient.appointments') }}" style="color: var(--primary);">Book one</a>.</p>
            @else
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($upcomingAppointments as $apt)
                        <li style="border-bottom: 1px solid #eef2f6; padding: 14px 0; display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span style="font-size: 1rem; font-weight: 700; color: var(--text-main); line-height: 1.1;">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('d') }}</span>
                                <span style="font-size: 0.65rem; font-weight: 600; color: var(--text-muted);">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M') }}</span>
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <span style="font-weight: 600; color: var(--text-main); display: block;">{{ "Reason: ".$apt->reason }}</span>
                                <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $apt->doctor->user->name }} · {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</span>
                            </div>
                            <span class="status-badge status-{{ $apt->status }}">{{ ucfirst($apt->status) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Recent Medical History -->
        <div class="glass-card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0;">
                    <i class="fas fa-notes-medical" style="color: var(--primary); margin-right: 8px;"></i>
                    Recent history
                </h2>
                <a href="{{ route('patient.history') }}" style="font-size: 0.85rem; color: var(--primary); font-weight: 600; text-decoration: none;">View history</a>
            </div>
            @if($recentMedicalHistory->isEmpty())
                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0; padding: 12px 0;">No medical records yet.</p>
            @else
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($recentMedicalHistory as $record)
                        <li style="border-bottom:1px solid #eef2f6; padding:14px 0; display:flex; align-items:center; gap:12px;">

                            <!-- Icon -->
                            <div style="width:40px; height:40px; border-radius:50%; background:#EFF6FF; color:#3B82F6; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i class="fas fa-notes-medical" style="font-size:0.9rem;"></i>
                            </div>

                            <!-- Record Info -->
                            <div style="flex:1; min-width:0; display:flex; flex-direction:column; gap:2px;">

                                <span style="font-weight:600; color:var(--text-main);">
                                    {{"Condition: ".$record->condition }}
                                </span>

                                <span style="font-size:0.85rem; color:var(--text-muted);">
                                    {{ "Dr. ".$record->doctor->user->name }} -
                                    {{ \Carbon\Carbon::parse($record->diagnosis_date)->format('M d, Y') }}
                                </span>

                            </div>

                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <style>
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
        }
        .status-upcoming { background: #ECFDF5; color: #059669; }
        .status-completed { background: #E0F2F1; color: #0D9488; }
        .status-cancelled { background: #FEE2E2; color: #DC2626; }
        @media (max-width: 1024px) {
            div[style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection
