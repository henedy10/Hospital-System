@extends('layouts.dashboard')

@section('title', 'Patient Profile')

@section('content')
    <div class="patient-profile-wrapper" style="animation: fadeIn 0.8s ease-out;">
        <!-- Header Section -->
        <div class="glass-card" style="padding: 32px; border-radius: 24px; margin-bottom: 24px; display: flex; gap: 40px; align-items: center; background: white; border: 1px solid #f1f5f9;">
            <div style="position: relative;">
                <img src="{{ $patient['avatar'] }}" 
                    style="width: 140px; height: 140px; border-radius: 32px; object-fit: cover; border: 4px solid #f8fafc; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);" alt="">
                <div style="position: absolute; bottom: 10px; right: 10px; width: 24px; height: 24px; border-radius: 50%; background: #10b981; border: 4px solid white;"></div>
            </div>
            
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h1 style="font-size: 2rem; font-weight: 850; color: #1e293b; margin-bottom: 8px; letter-spacing: -0.02em;">{{ $patient['name'] }}</h1>
                        <div style="display: flex; gap: 16px; align-items: center;">
                            <span style="font-weight: 700; color: #64748b; font-size: 0.95rem;">
                                <i class="fas fa-id-card" style="color: #cbd5e1; margin-right: 6px;"></i>{{ $patient['age'] }} Years • {{ $patient['gender'] }}
                            </span>
                            <span style="width: 4px; height: 4px; border-radius: 50%; background: #cbd5e1;"></span>
                            <span style="font-weight: 700; color: #64748b; font-size: 0.95rem;">
                                <i class="fas fa-bed" style="color: #cbd5e1; margin-right: 6px;"></i>Room {{ $patient['room'] }}
                            </span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Clinical Condition</div>
                        @php
                            $statusColors = [
                                'Critical' => ['bg' => '#fff1f2', 'text' => '#e11d48', 'dot' => '#e11d48'],
                                'Stable' => ['bg' => '#f0fdf4', 'text' => '#16a34a', 'dot' => '#16a34a'],
                                'Under Observation' => ['bg' => '#fffbeb', 'text' => '#d97706', 'dot' => '#d97706'],
                                'Recovering' => ['bg' => '#f0f9ff', 'text' => '#0284c7', 'dot' => '#0284c7'],
                                'Discharged' => ['bg' => '#f8fafc', 'text' => '#64748b', 'dot' => '#64748b'],
                            ];
                            $c = $statusColors[$patient['status']] ?? $statusColors['Stable'];
                        @endphp
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                            <span style="padding: 6px 16px; background: {{ $c['bg'] }}; color: {{ $c['text'] }}; border-radius: 12px; font-size: 0.9rem; font-weight: 700; border: 1px solid rgba(0,0,0,0.05);">
                                {{ $patient['status'] }}
                            </span>
                            <form action="{{ route('nurse.patients.update-status', $patient['id']) }}" method="POST" style="display: flex; gap: 4px;">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" style="font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; font-weight: 600; color: #64748b; background: white; cursor: pointer;">
                                    @foreach(array_keys($statusColors) as $status)
                                        <option value="{{ $status }}" {{ $patient['status'] == $status ? 'selected' : '' }}>Set Status: {{ $status }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <a href="{{ route('nurse.vitals.create', $patient['id']) }}" class="btn btn-primary" style="background: #0D9488; color: white; padding: 10px 24px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 0.95rem; display: flex; align-items: center; gap: 10px; transition: all 0.2s;">
                        <i class="fas fa-plus"></i> Record Vitals
                    </a>
                    <button class="btn" style="background: white; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; color: #475569;">
                        Nursing Notes
                    </button>
                    <button class="btn" style="background: white; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; color: #475569;">
                        Lab Results
                    </button>
                </div>
            </div>
        </div>

        <div class="grid-2-cols" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: flex-start;">
            <!-- Vitals and History -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="glass-card" style="padding: 28px; border-radius: 24px; background: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <h2 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0;">Vitals Monitoring</h2>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #64748b;">Past 24 Hours</span>
                    </div>
                    
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="text-align: left; border-bottom: 1px solid #f1f5f9;">
                                    <th style="padding: 12px 16px; color: #94a3b8; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">Time Recorded</th>
                                    <th style="padding: 12px 16px; color: #94a3b8; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">BP</th>
                                    <th style="padding: 12px 16px; color: #94a3b8; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">Temp</th>
                                    <th style="padding: 12px 16px; color: #94a3b8; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">Pulse</th>
                                    <th style="padding: 12px 16px; color: #94a3b8; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">SpO2</th>
                                    <th style="padding: 12px 16px; color: #94a3b8; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">Recorded By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patient['vitals_history'] as $record)
                                    <tr class="table-row-hover" style="border-bottom: 1px solid #f8fafc; transition: all 0.2s;">
                                        <td style="padding: 16px; font-weight: 800; color: #1e293b; font-size: 0.95rem;">{{ $record['time'] }}</td>
                                        <td style="padding: 16px; font-weight: 600; color: #475569;">{{ $record['bp'] }}</td>
                                        <td style="padding: 16px; font-weight: 600; color: #475569;">{{ $record['temp'] }}°C</td>
                                        <td style="padding: 16px; font-weight: 600; color: #475569;">{{ $record['pulse'] }} <span style="font-size: 0.75rem; color: #94a3b8;">bpm</span></td>
                                        <td style="padding: 16px; font-weight: 600; color: #475569;">
                                            <span style="padding: 4px 8px; background: #f0fdf4; color: #16a34a; border-radius: 6px; font-size: 0.85rem;">{{ $record['oxygen'] }}</span>
                                        </td>
                                        <td style="padding: 16px; font-size: 0.8rem; font-weight: 700; color: #64748b;">
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <i class="fas fa-user-nurse" style="color: #cbd5e1;"></i>
                                                {{ $record['recorder'] }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Medication Schedule -->
            <div class="glass-card" style="padding: 28px; border-radius: 24px; background: white;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                    <h2 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0;">Medications</h2>
                    <i class="fas fa-pills" style="color: #cbd5e1;"></i>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($patient['medication_schedule'] as $med)
                        <div style="padding: 16px; border-radius: 16px; border: 1px solid #f1f5f9; background: #fafafa; position: relative;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                <span style="font-size: 0.75rem; font-weight: 800; color: #94a3b8;">{{ $med['time'] }}</span>
                                @if($med['status'] == 'Pending')
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #b45309; background: #fffbeb; padding: 2px 8px; border-radius: 6px;">DUE NOW</span>
                                @endif
                            </div>
                            <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #1e293b;">{{ $med['med'] }}</h4>
                            <p style="margin: 6px 0 0 0; font-size: 0.8rem; font-weight: 600; color: #64748b; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-user-nurse" style="font-size: 0.7rem;"></i>{{ $med['nurse'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
                
                <button style="width: 100%; margin-top: 32px; padding: 14px; border-radius: 14px; border: 2px dashed #e2e8f0; background: transparent; color: #94a3b8; font-weight: 700; cursor: pointer; transition: all 0.2s; font-family: inherit;">
                    + View Full Schedule
                </button>
            </div>
        </div>
    </div>

    <style>
        .table-row-hover:hover {
            background-color: #f8fafc !important;
            transform: scale(1.01);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection