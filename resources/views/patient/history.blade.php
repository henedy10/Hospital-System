@extends('layouts.dashboard')

@section('title', 'Medical History')

@section('content')
    @php
        $patient = Auth::user()->patient;
        $allergies = optional($patient)->allergies;
        $latestRecord = $history->first();
        $recordsCount = $history->count();
    @endphp

    <div class="history-wrapper">
        <header class="page-header animate-fade-down">
            <div class="header-container">
                <div class="header-main">
                    <h1 class="premium-title">Medical History</h1>
                    <p class="text-muted">Explore your comprehensive health journey and clinical milestones.</p>
                </div>
                <div class="stats-badge glass-card">
                    <div class="icon-pulse">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-count">{{ $recordsCount }}</span>
                        <span class="stat-label">Total Records</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="history-grid">
            <main class="timeline-section">
                @forelse($history as $index => $record)
                    <div class="timeline-item animate-stagger" style="--order: {{ $index }}">
                        <div class="timeline-connector">
                            <div class="timeline-dot"></div>
                            <div class="timeline-line"></div>
                        </div>
                        
                        <div class="timeline-content glass-card">
                            <div class="record-header">
                                <div class="date-badge">
                                    <i class="fas fa-calendar-day"></i>
                                    {{ $record->diagnosis_date ? \Carbon\Carbon::parse($record->diagnosis_date)->format('F d, Y') : $record->created_at->format('F d, Y') }}
                                </div>
                                <div class="action-buttons">
                                    <a href="{{ route('patient.history.show', $record->id) }}" class="btn-icon-glass" title="View Full Report">
                                        <i class="fas fa-expand-alt"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="record-body">
                                <div class="diagnosis-info">
                                    <h2 class="condition-title">{{ $record->condition }}</h2>
                                    <div class="doctor-badge">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($record->doctor?->user->name ?? 'D') }}&background=0D9488&color=fff&size=40" alt="Dr.">
                                        <span>{{ optional($record->doctor?->user)->name ?? 'Clinical Specialist' }}</span>
                                        <span class="dot-separator"></span>
                                        <span class="specialty-text">{{ $record->doctor && $record->doctor->specialty ? ucfirst($record->doctor->specialty) : 'Department' }}</span>
                                    </div>
                                </div>
                                
                                @if($record->treatment)
                                    <div class="treatment-box">
                                        <div class="treatment-icon">
                                            <i class="fas fa-hand-holding-medical"></i>
                                        </div>
                                        <div class="treatment-text">
                                            <span class="label">Primary Treatment</span>
                                            <p>{{ Str::limit($record->treatment, 120) }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state glass-card animate-fade-in">
                        <div class="empty-icon-wrapper">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h2>No Records Yet</h2>
                        <p>Your medical clinical history is currently being compiled.</p>
                    </div>
                @endforelse
            </main>

            <aside class="sidebar-section">
                <div class="health-summary-card glass-card animate-fade-right">
                    <div class="sidebar-header">
                        <div class="header-icon-box">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h3>Health Summary</h3>
                    </div>

                    <div class="vitals-grid">
                        <div class="vital-item">
                            <span class="label">Blood Type</span>
                            <div class="vital-value blood-type">{{ optional($patient)->blood_type ?? 'N/A' }}</div>
                        </div>
                        <div class="vital-item">
                            <span class="label">Weight</span>
                            <div class="vital-value">{{ optional($patient)->weight ?? '--' }} <span class="unit">kg</span></div>
                        </div>
                        <div class="vital-item">
                            <span class="label">Height</span>
                            <div class="vital-value">{{ optional($patient)->height ?? '--' }} <span class="unit">cm</span></div>
                        </div>
                    </div>

                    <div class="allergy-section">
                        <h4>Allergies & Sensitivities</h4>
                        <div class="allergy-clouds">
                            @if(is_array($allergies) && count($allergies) > 0)
                                @foreach($allergies as $allergy)
                                    <span class="allergy-cloud-pill">{{ $allergy }}</span>
                                @endforeach
                            @else
                                <div class="no-allergies">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>No recorded allergies</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($latestRecord)
                        <div class="last-clinical-note">
                            <div class="note-header">
                                <i class="fas fa-history"></i>
                                <span>Recent Clinical History</span>
                            </div>
                            <div class="note-body">
                                <p class="note-date">{{ $latestRecord->diagnosis_date ? \Carbon\Carbon::parse($latestRecord->diagnosis_date)->format('M d, Y') : $latestRecord->created_at->format('M d, Y') }}</p>
                                <p class="note-condition">{{ $latestRecord->condition }}</p>
                                <p class="note-doctor">Dr. {{ optional($latestRecord->doctor?->user)->name ?? '—' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="support-card glass-card animate-fade-right" style="animation-delay: 0.2s">
                    <div class="support-header">
                        <i class="fas fa-headset"></i>
                        <h4>Need Assistance?</h4>
                    </div>
                    <p>If you notice any discrepancies in your medical history, please contact our support team.</p>
                    <button class="btn-support-glass">Contact Medical Admin</button>
                </div>
            </aside>
        </div>
    </div>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
            --secondary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.5);
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        .history-wrapper {
            max-width: 1300px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .premium-title {
            font-size: 2.75rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 3.5rem;
            gap: 2rem;
        }

        .stats-badge {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.25rem 1.75rem;
            border-radius: 20px;
        }

        .icon-pulse {
            width: 50px;
            height: 50px;
            background: var(--primary-gradient);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3); }
            50% { transform: scale(1.05); box-shadow: 0 15px 25px -5px rgba(13, 148, 136, 0.4); }
            100% { transform: scale(1); box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3); }
        }

        .stat-info .stat-count {
            display: block;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
        }

        .stat-info .stat-label {
            font-size: 0.813rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .history-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 3rem;
        }

        .timeline-section {
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
            padding-left: 2rem;
        }

        .timeline-item {
            position: relative;
            display: flex;
            gap: 2.5rem;
        }

        .timeline-connector {
            position: absolute;
            left: -2rem;
            top: 0;
            bottom: -2.5rem;
            width: 2px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .timeline-dot {
            width: 16px;
            height: 16px;
            background: white;
            border: 3px solid #0d9488;
            border-radius: 50%;
            z-index: 2;
            box-shadow: 0 0 0 5px rgba(13, 148, 136, 0.1);
        }

        .timeline-line {
            flex: 1;
            width: 2px;
            background: linear-gradient(to bottom, #e2e8f0, #e2e8f0 70%, transparent);
        }

        .timeline-item:last-child .timeline-line {
            background: linear-gradient(to bottom, #e2e8f0, transparent);
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-content:hover {
            transform: translateX(8px);
            border-color: rgba(13, 148, 136, 0.3);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .record-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .date-badge {
            background: #f1f5f9;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 700;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            border: 1px solid #e2e8f0;
        }

        .date-badge i { color: #0d9488; }

        .btn-icon-glass {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: var(--text-muted);
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-icon-glass:hover {
            color: #0d9488;
            border-color: #0d9488;
            background: rgba(13, 148, 136, 0.05);
        }

        .condition-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.75rem;
            letter-spacing: -0.01em;
        }

        .doctor-badge {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            margin-bottom: 1.75rem;
        }

        .doctor-badge img {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            object-fit: cover;
        }

        .doctor-badge span {
            font-size: 0.938rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .dot-separator {
            width: 4px;
            height: 4px;
            background: #cbd5e1;
            border-radius: 50%;
        }

        .specialty-text {
            color: var(--text-muted) ! abolitionist;
            font-weight: 500 !important;
        }

        .treatment-box {
            background: rgba(13, 148, 136, 0.04);
            border: 1px solid rgba(13, 148, 136, 0.1);
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            gap: 1.25rem;
            align-items: flex-start;
        }

        .treatment-icon {
            width: 44px;
            height: 44px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            color: #0d9488;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
        }

        .treatment-text .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 800;
            color: #0d9488;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
            display: block;
        }

        .treatment-text p {
            font-size: 0.938rem;
            color: var(--text-main);
            line-height: 1.6;
            font-weight: 500;
        }

        /* Sidebar Sidebar */
        .sidebar-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .header-icon-box {
            width: 44px;
            height: 44px;
            background: rgba(13, 148, 136, 0.1);
            color: #0d9488;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .sidebar-header h3 {
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 0;
        }

        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .vital-item {
            text-align: center;
            padding: 1rem 0.5rem;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
        }

        .vital-item .label {
            font-size: 0.688rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.025em;
            margin-bottom: 0.5rem;
            display: block;
        }

        .vital-value {
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .vital-value .unit {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .blood-type {
            color: #ef4444;
            font-size: 1.25rem;
        }

        .allergy-section h4 {
            font-size: 0.938rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1rem;
        }

        .allergy-clouds {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .allergy-cloud-pill {
            background: rgba(239, 68, 68, 0.08);
            color: #ef4444;
            padding: 0.5rem 0.875rem;
            border-radius: 12px;
            font-size: 0.813rem;
            font-weight: 700;
            border: 1px solid rgba(239, 68, 68, 0.15);
        }

        .no-allergies {
            width: 100%;
            padding: 1rem;
            background: rgba(16, 185, 129, 0.05);
            border: 1px dashed rgba(16, 185, 129, 0.3);
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #059669;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .last-clinical-note {
            background: #1e293b;
            border-radius: 18px;
            padding: 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .last-clinical-note::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            filter: blur(50px);
            opacity: 0.3;
        }

        .note-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.813rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.6);
        }

        .note-date { font-size: 1.125rem; font-weight: 800; margin-bottom: 0.25rem; }
        .note-condition { font-size: 0.938rem; color: rgba(255, 255, 255, 0.8); margin-bottom: 0.5rem; }
        .note-doctor { font-size: 0.813rem; color: #10b981; font-weight: 700; }

        .support-card p {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .btn-support-glass {
            width: 100%;
            background: white;
            border: 1px solid #e2e8f0;
            padding: 0.75rem;
            border-radius: 12px;
            font-weight: 700;
            color: var(--text-main);
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-support-glass:hover {
            border-color: #0d9488;
            color: #0d9488;
            background: rgba(13, 148, 136, 0.05);
        }

        /* Animations */
        @keyframes fadeDown { from { opacity: 0; transform: translateY(-15px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeRight { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes staggerIn { from { opacity: 0; transform: translateY(25px); } to { opacity: 1; transform: translateY(0); } }

        .animate-fade-down { animation: fadeDown 0.6s ease-out; }
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        .animate-fade-right { animation: fadeRight 0.6s ease-out both; }
        .animate-stagger {
            animation: staggerIn 0.5s ease-out both;
            animation-delay: calc(var(--order) * 0.1s);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .history-grid { grid-template-columns: 1fr; }
            .sidebar-section { order: -1; }
            .header-container { flex-direction: column; align-items: flex-start; }
            .vitals-grid { grid-template-columns: repeat(3, 1fr); }
        }

        @media (max-width: 640px) {
            .timeline-section { padding-left: 1rem; }
            .timeline-connector { left: -1rem; }
            .header-main h1 { font-size: 2.25rem; }
            .vitals-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
@endsection
