@extends('layouts.dashboard')

@section('title', 'Patient Care')

@section('content')
    <div class="welcome-section"
        style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Patient Care</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Monitor and manage patients in Ward A.</p>
        </div>
        <button class="btn btn-primary"
            style="background: var(--primary); color: white; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer;">
            <i class="fas fa-plus"></i> Admit New Patient
        </button>
    </div>

    <div class="stats-grid" style="margin-bottom: 32px;">
        @foreach($stats as $stat)
            <div class="glass-card">
                <div class="stats-card">
                    <div class="icon-box {{ $stat['color'] }}">
                        <i class="{{ $stat['icon'] }}"></i>
                    </div>
                    <div class="stats-info">
                        <h3>{{ $stat['value'] }}</h3>
                        <p>{{ $stat['label'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 16px 24px; font-weight: 600; color: #64748b;">Patient</th>
                    <th style="padding: 16px 24px; font-weight: 600; color: #64748b;">Room</th>
                    <th style="padding: 16px 24px; font-weight: 600; color: #64748b;">Status</th>
                    <th style="padding: 16px 24px; font-weight: 600; color: #64748b;">Last Vitals</th>
                    <th style="padding: 16px 24px; font-weight: 600; color: #64748b;">Next Medication</th>
                    <th style="padding: 16px 24px; font-weight: 600; color: #64748b; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 16px 24px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="{{ $patient['avatar'] }}" style="width: 40px; height: 40px; border-radius: 50%;"
                                    alt="">
                                <div>
                                    <div style="font-weight: 600; color: var(--text-main);">{{ $patient['name'] }}</div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $patient['age'] }} yrs,
                                        {{ $patient['gender'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px 24px; color: var(--text-main); font-weight: 500;">{{ $patient['room'] }}</td>
                        <td style="padding: 16px 24px;">
                            <span
                                style="padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                                        {{ $patient['status'] == 'Critical' ? 'background: #fee2e2; color: #ef4444;' : 'background: #f0fdf4; color: #16a34a;' }}">
                                {{ $patient['status'] }}
                            </span>
                        </td>
                        <td style="padding: 16px 24px; color: var(--text-muted);">{{ $patient['last_vitals'] }}</td>
                        <td style="padding: 16px 24px;">
                            <span
                                style="color: {{ $patient['next_dose'] == 'ASAP' ? 'var(--danger)' : 'var(--text-main)' }}; font-weight: {{ $patient['next_dose'] == 'ASAP' ? '700' : '400' }}">
                                {{ $patient['next_dose'] }}
                            </span>
                        </td>
                        <td style="padding: 16px 24px; text-align: center;">
                            <a href="{{ route('nurse.patients.show', $patient['id']) }}"
                                style="color: var(--primary); font-size: 1.1rem; margin: 0 8px;"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('nurse.vitals.create', $patient['id']) }}"
                                style="color: var(--primary); font-size: 1.1rem; margin: 0 8px;"><i
                                    class="fas fa-heartbeat"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection