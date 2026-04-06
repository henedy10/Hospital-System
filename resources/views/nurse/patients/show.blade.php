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

        <div class="grid-2-cols" style="display: grid; grid-template-columns: 2fr 0.9fr; gap: 24px; align-items: flex-start;">
            <!-- Main Content Area -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                
                <!-- Clinical Summary Cards -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                    @php
                        $latest = $patient['vitals_history']->first() ?? [
                            'bp' => '--/--', 'temp' => '--', 'pulse' => '--', 'oxygen' => '--%'
                        ];
                    @endphp
                    <div class="glass-card" style="padding: 20px; border-radius: 20px; background: white; border: 1px solid #f1f5f9; text-align: center;">
                        <i class="fas fa-heartbeat" style="color: #e11d48; font-size: 1.2rem; margin-bottom: 12px;"></i>
                        <div style="font-size: 1.25rem; font-weight: 850; color: #1e293b;">{{ $latest['bp'] }}</div>
                        <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-top: 4px;">BP (mmHg)</div>
                    </div>
                    <div class="glass-card" style="padding: 20px; border-radius: 20px; background: white; border: 1px solid #f1f5f9; text-align: center;">
                        <i class="fas fa-temperature-high" style="color: #f59e0b; font-size: 1.2rem; margin-bottom: 12px;"></i>
                        <div style="font-size: 1.25rem; font-weight: 850; color: #1e293b;">{{ $latest['temp'] }}°C</div>
                        <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-top: 4px;">Temp</div>
                    </div>
                    <div class="glass-card" style="padding: 20px; border-radius: 20px; background: white; border: 1px solid #f1f5f9; text-align: center;">
                        <i class="fas fa-wave-square" style="color: #0ea5e9; font-size: 1.2rem; margin-bottom: 12px;"></i>
                        <div style="font-size: 1.25rem; font-weight: 850; color: #1e293b;">{{ $latest['pulse'] }}</div>
                        <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-top: 4px;">Pulse (bpm)</div>
                    </div>
                    <div class="glass-card" style="padding: 20px; border-radius: 20px; background: white; border: 1px solid #f1f5f9; text-align: center;">
                        <i class="fas fa-lungs" style="color: #10b981; font-size: 1.2rem; margin-bottom: 12px;"></i>
                        <div style="font-size: 1.25rem; font-weight: 850; color: #1e293b;">{{ $latest['oxygen'] }}</div>
                        <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-top: 4px;">SpO2</div>
                    </div>
                </div>

                <!-- Vitals Monitoring History -->
                <div class="glass-card" style="padding: 32px; border-radius: 24px; background: white; border: 1px solid #f1f5f9;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <div>
                            <h2 style="font-size: 1.2rem; font-weight: 850; color: #0f172a; margin: 0;">Clinical Monitoring History</h2>
                            <p style="color: #64748b; font-size: 0.85rem; font-weight: 500; margin-top: 4px;">Past observation records from active shift.</p>
                        </div>
                        <button class="btn" style="padding: 8px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.8rem; font-weight: 700; color: #64748b; cursor: pointer;">
                            <i class="fas fa-filter" style="margin-right: 6px;"></i> Last 24H
                        </button>
                    </div>
                    
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr style="text-align: left;">
                                    <th style="padding: 16px; color: #94a3b8; font-size: 0.7rem; font-weight: 850; text-transform: uppercase; border-bottom: 1px solid #f1f5f9;">Timestamp</th>
                                    <th style="padding: 16px; color: #94a3b8; font-size: 0.7rem; font-weight: 850; text-transform: uppercase; border-bottom: 1px solid #f1f5f9;">Blood Pressure</th>
                                    <th style="padding: 16px; color: #94a3b8; font-size: 0.7rem; font-weight: 850; text-transform: uppercase; border-bottom: 1px solid #f1f5f9;">Temp</th>
                                    <th style="padding: 16px; color: #94a3b8; font-size: 0.7rem; font-weight: 850; text-transform: uppercase; border-bottom: 1px solid #f1f5f9;">HR/Pulse</th>
                                    <th style="padding: 16px; color: #94a3b8; font-size: 0.7rem; font-weight: 850; text-transform: uppercase; border-bottom: 1px solid #f1f5f9;">Oxygen</th>
                                    <th style="padding: 16px; color: #94a3b8; font-size: 0.7rem; font-weight: 850; text-transform: uppercase; border-bottom: 1px solid #f1f5f9;">Nurse</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patient['vitals_history'] as $record)
                                    <tr class="table-row-hover" style="transition: all 0.2s;">
                                        <td style="padding: 16px; font-weight: 800; color: #1e293b; font-size: 0.9rem; border-bottom: 1px solid #f8fafc;">{{ $record['time'] }}</td>
                                        <td style="padding: 16px; font-weight: 700; color: #475569; border-bottom: 1px solid #f8fafc;">{{ $record['bp'] }}</td>
                                        <td style="padding: 16px; font-weight: 700; color: #475569; border-bottom: 1px solid #f8fafc;">{{ $record['temp'] }}°C</td>
                                        <td style="padding: 16px; font-weight: 700; color: #475569; border-bottom: 1px solid #f8fafc;">{{ $record['pulse'] }} <span style="font-size: 0.7rem; color: #94a3b8;">bpm</span></td>
                                        <td style="padding: 16px; border-bottom: 1px solid #f8fafc;">
                                            <span style="padding: 4px 10px; background: #f0fdf4; color: #16a34a; border-radius: 6px; font-size: 0.8rem; font-weight: 800;">{{ $record['oxygen'] }}</span>
                                        </td>
                                        <td style="padding: 16px; font-size: 0.8rem; font-weight: 700; color: #64748b; border-bottom: 1px solid #f8fafc;">
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <div style="width: 24px; height: 24px; background: #ccfbf1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: #0D9488;">{{ substr($record['recorder'], 0, 1) }}</div>
                                                {{ $record['recorder'] }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="padding: 40px; text-align: center; color: #94a3b8; font-weight: 600;">No observations recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Side Sidebar: Medication and Patient Specifics -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <!-- Patient Basics -->
                <div class="glass-card" style="padding: 24px; border-radius: 24px; background: #0f172a; color: white;">
                    <div style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 16px;">Vital Information</div>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #cbd5e1; font-weight: 600; font-size: 0.85rem;">Blood Type</span>
                            <span style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 4px 12px; border-radius: 8px; font-weight: 800;">{{ $patient['blood_type'] }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <span style="color: #cbd5e1; font-weight: 600; font-size: 0.85rem;">Allergies</span>
                            <div style="text-align: right; display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 4px;">
                                @forelse($patient['allergies'] as $allergy)
                                    <span style="background: rgba(255, 255, 255, 0.1); padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">{{ $allergy }}</span>
                                @empty
                                    <span style="color: #94a3b8; font-size: 0.8rem;">NKDA</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medication Timeline -->
                <div class="glass-card" style="padding: 28px; border-radius: 24px; background: white; border: 1px solid #f1f5f9;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <h2 style="font-size: 1.1rem; font-weight: 850; color: #0f172a; margin: 0;">Shift Medications</h2>
                        <span style="background: #f0fdfa; color: #0D9488; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 800;">ACTIVE</span>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; position: relative;">
                        <div style="position: absolute; left: 7px; top: 10px; bottom: 10px; width: 2px; background: #f1f5f9;"></div>
                        
                        @forelse($patient['medication_schedule'] as $med)
                            <div style="display: flex; gap: 16px; margin-bottom: 24px; position: relative;">
                                <div style="z-index: 1; width: 16px; height: 16px; border-radius: 50%; background: white; border: 4px solid {{ $med['status'] == 'Completed' ? '#10b981' : '#0ea5e9' }}; margin-top: 4px;"></div>
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                        <span style="font-size: 0.75rem; font-weight: 800; color: {{ $med['status'] == 'Pending' ? '#b45309' : '#94a3b8' }}; text-transform: uppercase;">{{ $med['time'] }}</span>
                                        @if($med['status'] == 'Pending')
                                            <span style="background: #fffbeb; color: #d97706; font-size: 0.6rem; font-weight: 800; padding: 2px 6px; border-radius: 4px;">DUE</span>
                                        @endif
                                    </div>
                                    <h4 style="margin: 4px 0 0 0; font-size: 0.9rem; font-weight: 800; color: #1e293b;">{{ $med['med'] }}</h4>
                                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 500; margin-top: 2px;">{{ $med['status'] }} • Verified by {{ $med['nurse'] }}</div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #94a3b8; font-size: 0.85rem; padding: 20px 0;">No active medications.</div>
                        @endforelse
                    </div>
                    
                    <button style="width: 100%; margin-top: 8px; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; font-weight: 700; cursor: pointer; transition: all 0.2s; font-size: 0.85rem;">
                        View Full MAR Chart
                    </button>
                </div>
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