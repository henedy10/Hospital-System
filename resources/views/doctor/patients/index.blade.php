@extends('layouts.dashboard')

@section('title', 'Patients List')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding-bottom: 40px;">
    <!-- Welcome Section -->
    <div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">My Patients 🩺</h1>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Manage your patients and quickly access their clinical profiles.</p>
        </div>
    </div>

    <!-- Stats Header -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 32px;">
        @foreach($stats as $stat)
            <div style="background: #fff; border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9;">
                <div class="{{ $stat['color'] }}" style="width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="{{ $stat['icon'] }}"></i>
                </div>
                <div>
                    <p style="font-size: 0.85rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0;">{{ $stat['label'] }}</p>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $stat['value'] }}</h3>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Search Bar -->
    <div style="background: #fff; border-radius: 16px; padding: 16px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9;">
        <form method="GET" action="{{ route('doctor.patients') }}" style="margin: 0; display: flex; gap: 12px; align-items: center;">
            <div style="position: relative; flex: 1;">
                <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search patients by name..." 
                    style="width: 100%; padding: 12px 16px 12px 42px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; color: #1e293b; background: #f8fafc; outline: none; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14,165,233,0.1)';"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
            </div>
            <button type="submit" style="background: #0ea5e9; color: #fff; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: background 0.2s; white-space: nowrap;"
                    onmouseover="this.style.background='#0284c7'" onmouseout="this.style.background='#0ea5e9'">
                Search
            </button>
        </form>
    </div>

    <!-- Patients List -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 16px 24px; font-weight: 600; color: #475569; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Patient Details</th>
                    <th style="padding: 16px 24px; font-weight: 600; color: #475569; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 16px 24px; font-weight: 600; color: #475569; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Last Visit</th>
                    <th style="padding: 16px 24px; font-weight: 600; color: #475569; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                        <td style="padding: 16px 24px;">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <img src="{{ $patient->user->profile_image ? asset('storage/' . $patient->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($patient->user->name) . '&background=0D9488&color=fff' }}" 
                                     alt="{{ $patient->user->name }}" 
                                     style="width: 46px; height: 46px; border-radius: 12px; object-fit: cover; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                <div>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem; margin-bottom: 2px;">{{ $patient->user->name }}</div>
                                    <div style="color: #64748b; font-size: 0.8rem; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-id-card" style="font-size: 0.75rem;"></i> #{{ $patient->patient_id ?? $patient->id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px 24px;">
                            @php
                                $statusStyle = $patient->status === 'Critical' 
                                    ? 'background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2;' 
                                    : ($patient->status === 'Stable' 
                                        ? 'background: #f0fdf4; color: #22c55e; border: 1px solid #dcfce7;' 
                                        : 'background: #f0f9ff; color: #0ea5e9; border: 1px solid #e0f2fe;');
                                $statusIcon = $patient->status === 'Critical' ? 'fa-exclamation-triangle' : ($patient->status === 'Stable' ? 'fa-check-circle' : 'fa-info-circle');
                            @endphp
                            <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 12px; {{ $statusStyle }}">
                                <i class="fas {{ $statusIcon }}"></i>
                                {{ $patient->status ?? 'Stable' }}
                            </span>
                        </td>
                        <td style="padding: 16px 24px;">
                            @php
                                $lastVisit = $patient->appointments->first();
                            @endphp
                            <div style="color: #334155; font-size: 0.9rem; font-weight: 500;">
                                {{ $lastVisit ? \Carbon\Carbon::parse($lastVisit->appointment_date)->format('M d, Y') : '--' }}
                            </div>
                        </td>
                        <td style="padding: 16px 24px; text-align: right;">
                            <a href="{{ route('doctor.patients.show', $patient->user_id) }}" 
                               style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #f1f5f9; color: #0ea5e9; border-radius: 10px; text-decoration: none; transition: all 0.2s;"
                               onmouseover="this.style.background='#0ea5e9'; this.style.color='#fff';"
                               onmouseout="this.style.background='#f1f5f9'; this.style.color='#0ea5e9';"
                               title="View Profile">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 60px 20px;">
                            <div style="background: #f8fafc; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                                <i class="fas fa-user-injured" style="font-size: 1.5rem; color: #94a3b8;"></i>
                            </div>
                            <h3 style="margin: 0 0 8px 0; color: #0f172a; font-size: 1.1rem; font-weight: 700;">No Patients Found</h3>
                            <p style="color: #64748b; font-size: 0.9rem; margin: 0;">Patients will appear here after they book an appointment with you.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($patients->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $patients->links() }}
        </div>
    @endif
</div>
@endsection
