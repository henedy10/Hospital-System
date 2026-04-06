@extends('layouts.dashboard')

@section('title', 'Patient Care')

@section('content')
    <div class="welcome-section" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end; animation: fadeIn 0.8s ease-out;">
        <div>
            <h1 style="font-size: 1.85rem; font-weight: 850; color: #1e293b; margin-bottom: 8px; letter-spacing: -0.02em;">Patient Care Center</h1>
            <p style="color: #64748b; font-size: 1rem; font-weight: 500;">Monitor health status and manage active duty patients in your ward.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <button class="btn" style="background: white; border: 1px solid #e2e8f0; padding: 12px 20px; border-radius: 12px; font-weight: 700; color: #64748b; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-export"></i> Export Report
            </button>
            <button class="btn btn-primary" style="background: #0D9488; color: white; padding: 12px 24px; border-radius: 12px; border: none; cursor: pointer; font-weight: 700; display: flex; align-items: center; gap: 10px; box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.2); transition: all 0.3s ease;">
                <i class="fas fa-plus"></i> Admit New Patient
            </button>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="glass-card" style="padding: 16px 24px; border-radius: 20px; background: white; border: 1px solid #f1f5f9; margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center; gap: 24px;">
        <div style="flex: 1; position: relative;">
            <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            <input type="text" placeholder="Search patients by name, room, or condition..." 
                style="width: 100%; padding: 12px 12px 12px 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-family: inherit; font-size: 0.95rem; outline: none; transition: all 0.2s;">
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; margin-right: 8px;">Filter:</span>
            <button style="padding: 8px 16px; border-radius: 10px; background: #0f172a; color: white; border: none; font-size: 0.85rem; font-weight: 700; cursor: pointer;">All</button>
            <button style="padding: 8px 16px; border-radius: 10px; background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; font-size: 0.85rem; font-weight: 700; cursor: pointer;">Critical</button>
            <button style="padding: 8px 16px; border-radius: 10px; background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; font-size: 0.85rem; font-weight: 700; cursor: pointer;">Stable</button>
        </div>
    </div>

    <div class="patients-list" style="display: grid; grid-template-columns: 1fr; gap: 16px;">
        @forelse($patients as $patient)
            <div class="glass-card patient-row-hover" style="padding: 24px; border-radius: 24px; background: white; border: 1px solid #f1f5f9; transition: all 0.2s ease; display: grid; grid-template-columns: 1.2fr 0.8fr 1fr 1.5fr 0.8fr; align-items: center; gap: 40px;">
                <!-- Patient Info -->
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="position: relative;">
                        <img src="{{ $patient['avatar'] }}" style="width: 60px; height: 60px; border-radius: 18px; object-fit: cover; border: 2px solid #f1f5f9;" alt="">
                        <div style="position: absolute; bottom: -4px; right: -4px; width: 18px; height: 18px; border-radius: 50%; background: {{ $patient['status'] == 'Critical' ? '#ef4444' : '#10b981' }}; border: 3px solid white;"></div>
                    </div>
                    <div>
                        <a href="{{ route('nurse.patients.show', $patient['id']) }}" style="text-decoration: none;">
                            <div style="font-weight: 850; color: #1e293b; font-size: 1.1rem; margin-bottom: 4px;">{{ $patient['name'] }}</div>
                        </a>
                        <div style="font-size: 0.85rem; color: #64748b; font-weight: 600;">
                            <i class="fas fa-bed" style="color: #cbd5e1; margin-right: 6px;"></i>Room {{ $patient['room'] }}
                        </div>
                    </div>
                </div>

                <!-- Basic Stats -->
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Demographics</div>
                    <div style="font-weight: 700; color: #475569; font-size: 0.95rem;">
                        {{ $patient['age'] }}y • {{ $patient['gender'] }}
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Care Status</div>
                    @php
                        $statusColors = [
                            'Critical' => ['bg' => '#fff1f2', 'text' => '#e11d48', 'dot' => '#e11d48', 'pulse' => true],
                            'Stable' => ['bg' => '#f0fdf4', 'text' => '#16a34a', 'dot' => '#16a34a', 'pulse' => false],
                            'Under Observation' => ['bg' => '#fffbeb', 'text' => '#d97706', 'dot' => '#d97706', 'pulse' => true],
                            'Recovering' => ['bg' => '#f0f9ff', 'text' => '#0284c7', 'dot' => '#0284c7', 'pulse' => false],
                            'Discharged' => ['bg' => '#f8fafc', 'text' => '#64748b', 'dot' => '#64748b', 'pulse' => false],
                        ];
                        $c = $statusColors[$patient['status']] ?? $statusColors['Stable'];
                    @endphp
                    <div style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; background: {{ $c['bg'] }}; color: {{ $c['text'] }}; border-radius: 10px; font-size: 0.85rem; font-weight: 750; border: 1px solid rgba(0,0,0,0.05);">
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $c['dot'] }}; {{ $c['pulse'] ? 'animation: pulse-dot 2s infinite;' : '' }}"></div>
                        {{ $patient['status'] }}
                    </div>
                </div>

                <!-- Vitals Preview -->
                <div style="display: flex; gap: 24px;">
                    <div>
                        <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 6px;">Blood Pressure</div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-heartbeat" style="color: #ef4444; font-size: 0.8rem;"></i>
                            <span style="font-weight: 800; color: #1e293b; font-size: 0.95rem;">{{ $patient['last_bp'] }}</span>
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 6px;">Heart Rate</div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-pulse" style="color: #0ea5e9; font-size: 0.8rem;"></i>
                            <span style="font-weight: 800; color: #1e293b; font-size: 0.95rem;">{{ $patient['last_hr'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <a href="{{ route('nurse.patients.show', $patient['id']) }}" class="action-btn" title="View Profile"
                        style="width: 44px; height: 44px; border-radius: 12px; background: #f8fafc; color: #64748b; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s; border: 1px solid #e2e8f0;">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="{{ route('nurse.vitals.create', $patient['id']) }}" class="action-btn" title="Record Vitals"
                        style="width: 44px; height: 44px; border-radius: 12px; background: #f0fdfa; color: #0D9488; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s; border: 1px solid #ccfbf1;">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 60px 0; background: white; border-radius: 24px; border: 2px dashed #e2e8f0;">
                <div style="width: 80px; height: 80px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas fa-user-slash" style="color: #cbd5e1; font-size: 2rem;"></i>
                </div>
                <h3 style="color: #1e293b; font-weight: 800; margin-bottom: 8px;">No Patients Assigned</h3>
                <p style="color: #64748b; font-weight: 500;">You currenty don't have any active patients in this ward.</p>
            </div>
        @endforelse
    </div>

    <style>
        .patient-row-hover:hover {
            transform: translateX(5px);
            border-color: #cbd5e1;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
        .action-btn:hover {
            background: var(--primary) !important;
            color: white !important;
            transform: scale(1.1);
        }
        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(225, 29, 72, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(225, 29, 72, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(225, 29, 72, 0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection