@extends('layouts.dashboard')

@section('title', 'Patient Care')

@section('content')
    <div class="welcome-section" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; animation: fadeIn 0.8s ease-out;">
        <div>
            <h1 style="font-size: 1.85rem; font-weight: 850; color: #1e293b; margin-bottom: 8px; letter-spacing: -0.02em;">Patient Care Center</h1>
            <p style="color: #64748b; font-size: 1rem; font-weight: 500;">Monitor health status and manage active duty patients in your ward.</p>
        </div>
        <button class="btn btn-primary" style="background: #0D9488; color: white; padding: 12px 24px; border-radius: 12px; border: none; cursor: pointer; font-weight: 700; display: flex; align-items: center; gap: 10px; box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.2); transition: all 0.3s ease;">
            <i class="fas fa-plus"></i> Admit New Patient
        </button>
    </div>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px;">
        @foreach($stats as $stat)
            <div class="glass-card stat-card-hover" style="padding: 24px; border-radius: 20px; transition: all 0.3s ease; border: 1px solid rgba(226, 232, 240, 0.8); background: white;">
                <div class="stats-card" style="display: flex; align-items: center; gap: 20px;">
                    <div class="icon-box {{ $stat['color'] }}" style="width: 54px; height: 54px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; box-shadow: 0 8px 16px -4px rgba(0,0,0,0.1);">
                        <i class="{{ $stat['icon'] }}"></i>
                    </div>
                    <div class="stats-info">
                        <h3 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $stat['value'] }}</h3>
                        <p style="color: #64748b; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; margin-top: 2px;">{{ $stat['label'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="patients-list" style="display: flex; flex-direction: column; gap: 16px;">
        @foreach($patients as $patient)
            <div class="glass-card patient-row-hover" style="padding: 20px 28px; border-radius: 20px; background: white; border: 1px solid #f1f5f9; transition: all 0.2s ease; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; align-items: center; gap: 32px;">
                <!-- Patient Info -->
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="position: relative;">
                        <img src="{{ $patient['avatar'] }}" style="width: 52px; height: 52px; border-radius: 16px; object-fit: cover; border: 2px solid #f8fafc;" alt="">
                        <div style="position: absolute; bottom: -2px; right: -2px; width: 14px; height: 14px; border-radius: 50%; background: {{ $patient['status'] == 'Critical' ? '#ef4444' : '#10b981' }}; border: 2px solid white;"></div>
                    </div>
                    <div>
                        <div style="font-weight: 800; color: #1e293b; font-size: 1.05rem;">{{ $patient['name'] }}</div>
                        <div style="font-size: 0.85rem; color: #64748b; font-weight: 500; margin-top: 2px;">
                            <span style="color: #94a3b8;">{{ $patient['age'] }}y</span> • <span>{{ $patient['gender'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Room -->
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Location</div>
                    <div style="font-weight: 700; color: #334155; font-size: 0.95rem;">
                        <i class="fas fa-bed" style="color: #cbd5e1; margin-right: 6px; font-size: 0.8rem;"></i>Room {{ $patient['room'] }}
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Care Status</div>
                    @if($patient['status'] == 'Critical')
                        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: #fff1f2; color: #e11d48; border-radius: 8px; font-size: 0.8rem; font-weight: 700; border: 1px solid rgba(225, 29, 72, 0.1);">
                            <div style="width: 6px; height: 6px; border-radius: 50%; background: #e11d48; animation: pulse-red 2s infinite;"></div>
                            Critical
                        </div>
                    @else
                        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: #f0fdf4; color: #16a34a; border-radius: 8px; font-size: 0.8rem; font-weight: 700; border: 1px solid rgba(22, 163, 74, 0.1);">
                            Stable Condition
                        </div>
                    @endif
                </div>

                <!-- Last Vitals -->
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Last Vitals</div>
                    <div style="font-weight: 600; color: #475569; font-size: 0.9rem;">{{ $patient['last_vitals'] }}</div>
                </div>

                <!-- Next Task -->
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Next Event</div>
                    <div style="font-weight: 600; color: {{ $patient['next_dose'] == 'ASAP' ? '#e11d48' : '#334155' }}; font-size: 0.9rem;">
                        {{ $patient['next_dose'] }}
                    </div>
                </div>

                <!-- Actions -->
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('nurse.patients.show', $patient['id']) }}" class="action-btn" title="View Profile"
                        style="width: 38px; height: 38px; border-radius: 10px; background: #f8fafc; color: #64748b; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s;">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('nurse.vitals.create', $patient['id']) }}" class="action-btn" title="Record Vitals"
                        style="width: 38px; height: 38px; border-radius: 10px; background: #f0fdfa; color: #0D9488; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s;">
                        <i class="fas fa-heartbeat"></i>
                    </a>
                </div>
            </div>
        @endforeach
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