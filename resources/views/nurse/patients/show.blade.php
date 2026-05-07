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
                    <a href="#nursing-notes" class="btn" style="background: white; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; color: #475569; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-notes-medical"></i> Nursing Notes
                    </a>
                    <a href="#lab-results" class="btn" style="background: white; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; color: #475569; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-flask"></i> Lab Results
                    </a>
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

                <!-- Nursing Notes -->
                <div id="nursing-notes" class="glass-card" style="padding: 32px; border-radius: 24px; background: white; border: 1px solid #f1f5f9;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <div>
                            <h2 style="font-size: 1.2rem; font-weight: 850; color: #0f172a; margin: 0;">Nursing Care Notes</h2>
                            <p style="color: #64748b; font-size: 0.85rem; font-weight: 500; margin-top: 4px;">Record daily observations and shift handovers.</p>
                        </div>
                        <button onclick="document.getElementById('note-form').style.display = 'block'; this.style.display='none'" class="btn" style="padding: 10px 20px; background: #f0f9ff; color: #0284c7; border: 1px solid #e0f2fe; border-radius: 12px; font-size: 0.9rem; font-weight: 700; cursor: pointer;">
                            <i class="fas fa-pen-nib" style="margin-right: 8px;"></i> Add Note
                        </button>
                    </div>

                    <div id="note-form" style="display: none; margin-bottom: 32px; padding: 24px; background: #f8fafc; border-radius: 20px; border: 1px solid #e2e8f0;">
                        <form action="{{ route('nurse.patients.notes.store', $patient['id']) }}" method="POST">
                            @csrf
                            <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #475569; margin-bottom: 12px;">NEW OBSERVATION NOTE</label>
                            <textarea name="content" rows="4" style="width: 100%; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-family: inherit; font-size: 0.95rem; margin-bottom: 16px; outline: none; focus: border-color: #0ea5e9;" placeholder="Describe patient status, changes, or treatments administered..."></textarea>
                            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                                <button type="button" onclick="document.getElementById('note-form').style.display = 'none'; document.querySelector('[onclick*=\'note-form\']').style.display='block'" style="padding: 8px 16px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; color: #64748b; font-weight: 700; cursor: pointer;">Cancel</button>
                                <button type="submit" style="padding: 8px 24px; background: #0ea5e9; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Save Note</button>
                            </div>
                        </form>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        @forelse($patient['nursing_notes'] as $note)
                            <div style="padding: 24px; border-radius: 20px; background: #f8fafc; border: 1px solid #f1f5f9; position: relative;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 32px; height: 32px; background: #e0f2fe; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: #0284c7; font-weight: 800;">
                                            {{ substr($note['nurse'], 0, 1) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 800; color: #1e293b; font-size: 0.9rem;">{{ $note['nurse'] }}</div>
                                            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Registered Nurse</div>
                                        </div>
                                    </div>
                                    <div style="font-size: 0.75rem; font-weight: 700; color: #94a3b8;">{{ $note['time'] }}</div>
                                </div>
                                <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; margin: 0; font-weight: 500;">
                                    {{ $note['content'] }}
                                </p>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 40px; color: #94a3b8; font-weight: 600; background: #f8fafc; border-radius: 20px; border: 1px dashed #e2e8f0;">
                                No nursing notes recorded for this patient.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Lab Results -->
                <div id="lab-results" class="glass-card" style="padding: 32px; border-radius: 24px; background: white; border: 1px solid #f1f5f9;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <div>
                            <h2 style="font-size: 1.2rem; font-weight: 850; color: #0f172a; margin: 0;">Laboratory Investigations</h2>
                            <p style="color: #64748b; font-size: 0.85rem; font-weight: 500; margin-top: 4px;">Latest diagnostic reports and blood work.</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                        @forelse($patient['lab_results'] as $lab)
                            <div style="padding: 20px 24px; border-radius: 16px; border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; transition: all 0.2s; cursor: pointer; background: #fcfcfc;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fcfcfc'">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #fff7ed; color: #c2410c; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                        <i class="fas fa-vial"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; color: #1e293b; font-size: 1rem;">{{ $lab['test'] }}</div>
                                        <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Report Date: {{ $lab['date'] }}</div>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 1.1rem; font-weight: 900; color: #0f172a;">{{ $lab['result'] }}</div>
                                    <div style="font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Ref Range: {{ $lab['range'] }}</div>
                                </div>
                                <div style="margin-left: 24px;">
                                    <span style="padding: 4px 10px; border-radius: 8px; font-size: 0.7rem; font-weight: 800; background: {{ $lab['status'] == 'Final' ? '#f0fdf4' : '#fffbeb' }}; color: {{ $lab['status'] == 'Final' ? '#16a34a' : '#d97706' }};">
                                        {{ $lab['status'] }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 40px; color: #94a3b8; font-weight: 600; background: #f8fafc; border-radius: 20px; border: 1px dashed #e2e8f0;">
                                No laboratory results available.
                            </div>
                        @endforelse
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