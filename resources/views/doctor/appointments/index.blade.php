@extends('layouts.dashboard')

@section('title', 'Appointments List')

@section('content')
    <div class="welcome-section"
        style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Appointment
                Schedule 📅</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Manage your appointments and daily review sessions
                effectively.</p>
        </div>
        <button class="btn-primary" style="width: auto; padding: 10px 24px; margin-top: 0;">
            <i class="fas fa-plus"></i> Add New Appointment
        </button>
    </div>

    <div class="glass-card">
        <!-- Filters and Search -->
        <form action="{{ route('doctor.appointments') }}" method="GET" class="search-container">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="form-control" placeholder="Search by patient name..."
                    value="{{ request('search') }}">
            </div>
            <select name="status" class="select-control" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <input type="date" name="date" class="form-control" style="width: auto; padding-left: 16px;"
                value="{{ request('date') }}" onchange="this.form.submit()">

            @if(request()->hasAny(['search', 'status', 'date']))
                <a href="{{ route('doctor.appointments') }}" class="btn-secondary" style="margin: 0; padding: 10px 16px;">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </form>

        <!-- Appointments Table -->
        <div style="overflow-x: auto; border-radius: 20px;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #eef2f6; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="padding: 16px 20px; font-weight: 600;">Patient Name</th>
                        <th style="padding: 16px 20px; font-weight: 600;">Time</th>
                        <th style="padding: 16px 20px; font-weight: 600;">Date</th>
                        <th style="padding: 16px 20px; font-weight: 600;">Reason</th>
                        <th style="padding: 16px 20px; font-weight: 600;">Status</th>
                        <th style="padding: 16px 20px; font-weight: 600; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr style="border-bottom: 1px solid #eef2f6; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 16px 20px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="{{ $appointment->user->profile_image ? asset('storage/' . $appointment->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($appointment->user->name) . '&background=0D9488&color=fff' }}"
                                        alt="Patient" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    <span style="font-weight: 700; color: var(--text-main);">{{ $appointment->user->name }}</span>
                                </div>
                            </td>
                            <td style="padding: 16px 20px; font-weight: 600; color: var(--text-main);">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            </td>
                            <td style="padding: 16px 20px; color: var(--text-muted);">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                            </td>
                            <td style="padding: 16px 20px; color: var(--text-muted); font-size: 0.9rem; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $appointment->reason }}">
                                {{ $appointment->reason }}
                            </td>
                            <td style="padding: 16px 20px;">
                                @if($appointment->status == 'upcoming')
                                    <span style="background: #FEF3C7; color: #D97706; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; border: 1px solid #FDE68A; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-clock"></i> Upcoming
                                    </span>
                                @elseif($appointment->status == 'completed')
                                    <span style="background: #D1FAE5; color: #059669; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; border: 1px solid #A7F3D0; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-check-circle"></i> Completed
                                    </span>
                                @else
                                    <span style="background: #FEE2E2; color: #EF4444; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; border: 1px solid #FECACA; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 16px 20px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    @if($appointment->status == 'upcoming')
                                        <form action="{{ route('doctor.appointments.update-status', $appointment) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn-icon" title="Mark as Completed" style="color: #059669; background: #D1FAE5; padding: 8px; border-radius: 12px;">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('doctor.appointments.update-status', $appointment) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn-icon" title="Cancel Appointment" style="color: #EF4444; background: #FEE2E2; padding: 8px; border-radius: 12px;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('doctor.patients.show', $appointment->user_id) }}" class="btn-icon" title="Patient Profile" style="color: var(--primary); background: #f0fdfa; padding: 8px; border-radius: 12px;">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <div style="background: #f8fafc; display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: 50%; margin-bottom: 16px;">
                                    <i class="fas fa-calendar-times" style="font-size: 1.5rem; color: #94A3B8;"></i>
                                </div>
                                <h3 style="margin: 0; font-size: 1.1rem; color: var(--text-main); font-weight: 700;">No Appointments Found</h3>
                                <p style="margin-top: 8px; font-size: 0.9rem;">There are no appointments matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 30px; display: flex; justify-content: center; padding-bottom: 10px;">
            {{ $appointments->links() }}
        </div>
    </div>
@endsection