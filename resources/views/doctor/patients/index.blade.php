@extends('layouts.dashboard')

@section('title', 'Patients List')

@section('content')
    <div class="welcome-section"
        style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">My Patients 🩺</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Patients who have had appointments with you. View profiles and clinical history.</p>
        </div>
    </div>

    <!-- Stats Header -->
    <div class="stats-header-grid">
        @foreach($stats as $stat)
            <div class="glass-card" style="padding: 20px; display: flex; align-items: center; gap: 20px;">
                <div class="icon-box {{ $stat['color'] }}" style="width: 50px; height: 50px; border-radius: 14px;">
                    <i class="{{ $stat['icon'] }}"></i>
                </div>
                <div>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 4px;">{{ $stat['label'] }}</p>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">{{ $stat['value'] }}</h3>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Improved Filters -->
    <div class="glass-card" style="margin-bottom: 32px; padding: 20px;">
        <form method="GET" action="{{ route('doctor.patients') }}" class="search-container" style="margin-bottom: 0;">
            <div class="search-input-wrapper" style="flex: 2;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search by patient name...">
            </div>
            {{-- <select class="select-control" style="flex: 1; opacity: 0.5;" disabled title="Coming soon">
                <option value="">All Departments (Coming Soon)</option>
                <option value="Cardiology">Cardiology</option>
                <option value="Orthopedics">Orthopedics</option>
            </select> --}}
            <button type="submit" class="btn-primary" style="width: auto; padding: 0 20px; margin-top: 0; outline: none;"><i
                    class="fas fa-search"></i> Search</button>
        </form>
    </div>

    <!-- Enhanced Patients Table -->
    <div class="glass-card" style="overflow-x: auto; border-radius: 20px;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #eef2f6; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <th style="padding: 16px 20px; font-weight: 600;">Patient</th>
                    <th style="padding: 16px 20px; font-weight: 600;">ID</th>
                    <th style="padding: 16px 20px; font-weight: 600;">Age & Gender</th>
                    <th style="padding: 16px 20px; font-weight: 600;">Blood</th>
                    <th style="padding: 16px 20px; font-weight: 600;">Status</th>
                    <th style="padding: 16px 20px; font-weight: 600;">Last Visit</th>
                    <th style="padding: 16px 20px; font-weight: 600; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr style="border-bottom: 1px solid #eef2f6; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 16px 20px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="{{ $patient->user->profile_image ? asset('storage/' . $patient->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($patient->user->name) . '&background=0D9488&color=fff' }}" alt="{{ $patient->user->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                <span style="font-weight: 700; color: var(--text-main);">{{ $patient->user->name }}</span>
                            </div>
                        </td>
                        <td style="padding: 16px 20px; color: var(--text-muted); font-size: 0.9rem;">
                            #{{ $patient->patient_id ?? $patient->id }}
                        </td>
                        <td style="padding: 16px 20px; color: var(--text-muted); font-size: 0.9rem;">
                            {{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : '--' }} Yrs • {{ $patient->gender ? ucfirst($patient->gender) : '--' }}
                        </td>
                        <td style="padding: 16px 20px;">
                            @if($patient->blood_type)
                                <span style="background: var(--primary); color: #fff; font-size: 0.7rem; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
                                    {{ $patient->blood_type }}
                                </span>
                            @else
                                <span style="color: var(--text-muted); font-size: 0.9rem;">--</span>
                            @endif
                        </td>
                        <td style="padding: 16px 20px;">
                            <span class="status-badge" style="
                                display: inline-flex; 
                                align-items: center; 
                                gap: 6px; 
                                font-size: 0.75rem; 
                                font-weight: 700; 
                                padding: 4px 10px; 
                                border-radius: 12px;
                                {{ $patient->status === 'Critical' ? 'background: #FEE2E2; color: #DC2626;' : 
                                   ($patient->status === 'Stable' ? 'background: #ECFDF5; color: #059669;' : 'background: #EFF6FF; color: #3B82F6;') }}
                            ">
                                <i class="fas {{ $patient->status === 'Critical' ? 'fa-exclamation-triangle' : 
                                               ($patient->status === 'Stable' ? 'fa-check-circle' : 'fa-info-circle') }}" 
                                   style="font-size: 0.65rem;"></i>
                                {{ $patient->status ?? 'Stable' }}
                            </span>
                        </td>
                        <td style="padding: 16px 20px; color: var(--text-muted); font-size: 0.9rem;">
                            @php
                                $lastVisit = $patient->appointments->first();
                            @endphp
                            {{ $lastVisit ? \Carbon\Carbon::parse($lastVisit->appointment_date)->format('M d, Y') : '--' }}
                        </td>
                        <td style="padding: 16px 20px; text-align: right;">
                            <a href="{{ route('doctor.patients.show', $patient->user_id) }}" class="btn-primary-sm" style="text-decoration: none; padding: 6px 12px; font-size: 0.8rem;">Profile</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="color:red;text-align: center; padding: 40px; ">
                            No patients with appointments yet. Patients will appear here after they book an appointment with you.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $patients->links() }}
    </div>
@endsection
