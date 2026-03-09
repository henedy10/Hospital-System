@extends('layouts.dashboard')

@section('title', 'Appointments Overview')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Appointments Overview</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">All appointments across the system.</p>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="glass-card" style="padding: 6px; margin-bottom: 24px; display: inline-flex; gap: 4px; border-radius: 12px;">
        @foreach(['all' => 'All', 'upcoming' => 'Upcoming', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
            @php
                $isActive = $status === $key;
                $badgeBgs = ['upcoming' => '#ede9fe', 'completed' => '#d1fae5', 'cancelled' => '#fee2e2', 'all' => '#e2e8f0'];
                $badgeTexts = ['upcoming' => '#7c3aed', 'completed' => '#059669', 'cancelled' => '#dc2626', 'all' => '#475569'];
            @endphp
            <a href="{{ route('admin.appointments', array_merge(request()->query(), ['status' => $key])) }}"
                style="padding: 8px 18px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: {{ $isActive ? '600' : '500' }}; color: {{ $isActive ? '#fff' : 'var(--text-muted)' }}; background: {{ $isActive ? 'linear-gradient(135deg,#6366f1,#4f46e5)' : 'transparent' }}; transition: all 0.15s;">
                {{ $label }}
                <span style="display: inline-block; margin-left: 5px; background: {{ $isActive ? 'rgba(255,255,255,0.25)' : $badgeBgs[$key] }}; color: {{ $isActive ? '#fff' : $badgeTexts[$key] }}; border-radius: 999px; padding: 1px 7px; font-size: 0.7rem;">
                    {{ $counts[$key] }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="glass-card" style="padding: 20px; margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.appointments') }}"
            style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
            <input type="hidden" name="status" value="{{ $status }}">

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">Doctor</label>
                <select name="doctor_id"
                    style="width: 100%; padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.875rem; outline: none; background: #fff; color: var(--text-main);">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}" {{ $doctorId == $doc->id ? 'selected' : '' }}>{{ $doc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="min-width: 180px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">Date</label>
                <input type="date" name="date" value="{{ $date }}"
                    style="width: 100%; padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.875rem; outline: none; background: #fff; color: var(--text-main); box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit"
                    style="padding: 9px 20px; background: linear-gradient(135deg,#6366f1,#4f46e5); color: #fff; border: none; border-radius: 10px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                    <i class="fas fa-filter" style="margin-right: 5px;"></i> Filter
                </button>
                <a href="{{ route('admin.appointments') }}"
                    style="padding: 9px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; color: var(--text-muted); text-decoration: none; font-size: 0.875rem; font-weight: 500; display: inline-flex; align-items: center;">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Appointments Table --}}
    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">#</th>
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Patient</th>
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Doctor</th>
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Date & Time</th>
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Reason</th>
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appt)
                        @php
                            $statusCfg = [
                                'upcoming'  => ['bg' => '#ede9fe', 'text' => '#7c3aed', 'icon' => 'fa-clock'],
                                'completed' => ['bg' => '#d1fae5', 'text' => '#059669', 'icon' => 'fa-check-circle'],
                                'cancelled' => ['bg' => '#fee2e2', 'text' => '#dc2626', 'icon' => 'fa-times-circle'],
                            ];
                            $sc = $statusCfg[$appt->status] ?? ['bg' => '#e5e7eb', 'text' => '#374151', 'icon' => 'fa-circle'];
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;"
                            onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                            <td style="padding: 14px 20px; color: var(--text-muted); font-size: 0.8rem;">{{ $appt->id }}</td>
                            <td style="padding: 14px 20px;">
                                <div style="font-weight: 500; color: var(--text-main);">{{ optional($appt->user)->name ?? '—' }}</div>
                            </td>
                            <td style="padding: 14px 20px;">
                                @if($appt->doctor)
                                    <div style="font-weight: 500; color: var(--text-main);">{{ $appt->doctor->name }}</div>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td style="padding: 14px 20px;">
                                <div style="font-weight: 500; color: var(--text-main);">
                                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    {{ $appt->appointment_time ? \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') : '—' }}
                                </div>
                            </td>
                            <td style="padding: 14px 20px; color: var(--text-muted); max-width: 200px;">
                                <span style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $appt->reason ?? '—' }}
                                </span>
                            </td>
                            <td style="padding: 14px 20px;">
                                <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; background: {{ $sc['bg'] }}; color: {{ $sc['text'] }};">
                                    <i class="fas {{ $sc['icon'] }}" style="font-size: 0.65rem;"></i>
                                    {{ ucfirst($appt->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 48px; text-align: center; color: var(--text-muted);">
                                <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 12px; display: block; opacity: 0.3;"></i>
                                No appointments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($appointments->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
@endsection
