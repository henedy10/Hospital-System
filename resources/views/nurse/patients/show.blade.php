@extends('layouts.dashboard')

@section('title', 'Patient Profile')

@section('content')
    <div class="profile-header" style="display: flex; gap: 32px; align-items: flex-start; margin-bottom: 32px;">
        <div class="glass-card" style="padding: 24px; text-align: center; width: 250px;">
            <img src="{{ $patient['avatar'] }}"
                style="width: 120px; height: 120px; border-radius: 50%; margin-bottom: 16px; border: 4px solid #f0fdf4;"
                alt="">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">
                {{ $patient['name'] }}</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 16px;">Room: {{ $patient['room'] }}</p>
            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                <span
                    style="background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem;">{{ $patient['blood_type'] }}</span>
                @foreach($patient['allergies'] as $allergy)
                    <span
                        style="background: #fee2e2; color: #ef4444; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem;">{{ $allergy }}</span>
                @endforeach
            </div>
            <hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">
            <a href="{{ route('nurse.vitals.create', $patient['id']) }}" class="btn"
                style="display: block; background: var(--primary); color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: 600;">Record
                Vitals</a>
        </div>

        <div style="flex: 1;">
            <!-- Tabs for Profile -->
            <div class="glass-card" style="margin-bottom: 24px;">
                <div style="display: flex; gap: 24px; border-bottom: 1px solid #e2e8f0; padding: 0 24px;">
                    <a href="#"
                        style="padding: 16px 0; border-bottom: 2px solid var(--primary); color: var(--primary); font-weight: 600; text-decoration: none;">Vitals
                        History</a>
                    <a href="#" style="padding: 16px 0; color: #64748b; font-weight: 500; text-decoration: none;">Medication
                        Schedule</a>
                    <a href="#" style="padding: 16px 0; color: #64748b; font-weight: 500; text-decoration: none;">Nursing
                        Notes</a>
                </div>

                <div style="padding: 24px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                                <th style="padding: 12px 0; color: #64748b; font-weight: 600;">Time</th>
                                <th style="padding: 12px 0; color: #64748b; font-weight: 600;">BP</th>
                                <th style="padding: 12px 0; color: #64748b; font-weight: 600;">Temp</th>
                                <th style="padding: 12px 0; color: #64748b; font-weight: 600;">Pulse</th>
                                <th style="padding: 12px 0; color: #64748b; font-weight: 600;">Oxygen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patient['vitals_history'] as $record)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 16px 0; font-weight: 500;">{{ $record['time'] }}</td>
                                    <td style="padding: 16px 0;">{{ $record['bp'] }}</td>
                                    <td style="padding: 16px 0;">{{ $record['temp'] }}°C</td>
                                    <td style="padding: 16px 0;">{{ $record['pulse'] }} bpm</td>
                                    <td style="padding: 16px 0;">{{ $record['oxygen'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="glass-card">
                <div style="padding: 24px;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;">Upcoming Medication</h3>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach($patient['medication_schedule'] as $med)
                            <div
                                style="display: flex; align-items: center; justify-content: space-between; padding: 16px; border-radius: 12px; background: {{ $med['status'] == 'Pending' ? '#fffbeb' : '#f8fafc' }}; border: 1px solid {{ $med['status'] == 'Pending' ? '#fde68a' : '#e2e8f0' }};">
                                <div style="display: flex; gap: 16px; align-items: center;">
                                    <div
                                        style="width: 40px; height: 40px; border-radius: 8px; background: white; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                        <i class="fas fa-pills"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $med['med'] }}</div>
                                        <div style="font-size: 0.8rem; color: #64748b;">{{ $med['time'] }} • {{ $med['nurse'] }}
                                        </div>
                                    </div>
                                </div>
                                <span
                                    style="font-size: 0.8rem; font-weight: 600; color: {{ $med['status'] == 'Pending' ? '#b45309' : '#64748b' }};">{{ $med['status'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection