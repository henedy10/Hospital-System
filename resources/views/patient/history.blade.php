@extends('layouts.dashboard')

@section('title', 'Medical History')

@section('content')
    @php
        $patient = Auth::user()->patient;
        $allergies = optional($patient)->allergies;
        $latestRecord = $history->first();
        $recordsCount = $history->count();
    @endphp

    <div class="page-header">
        <div class="page-header-row">
            <div>
                <h1>Medical History</h1>
                <p class="muted">A complete record of your visits, reports, and prescriptions.</p>
            </div>

            <div class="history-count">
                <i class="fas fa-notes-medical" aria-hidden="true"></i>
                <span>{{ $recordsCount }} record{{ $recordsCount === 1 ? '' : 's' }}</span>
            </div>
        </div>
    </div>

    <div class="history-grid">
        <div class="history-main">
            <!-- Records Timeline -->
            <div class="timeline">
                @forelse($history as $record)
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $loop->iteration % 2 === 0 ? 'orange' : 'sky' }}">
                            <i class="fas fa-file-medical" aria-hidden="true"></i>
                        </div>

                        <div class="timeline-content">
                            <div class="timeline-head">
                                <div>
                                    <div class="timeline-date">
                                        {{ $record->diagnosis_date ? \Carbon\Carbon::parse($record->diagnosis_date)->format('M d, Y') : $record->created_at->format('M d, Y') }}
                                    </div>

                                    <div class="timeline-type"><strong>Condition:</strong>{{ $record->condition }}</div>
                                </div>

                                <a href="{{ route('patient.history.show', $record->id) }}" class="btn-icon" title="View details">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </a>
                            </div>

                            <p class="timeline-desc">
                                <strong>Doctor:</strong> {{ optional($record->doctor?->user)->name ?? '—' }}<br/>
                                <strong>Department:</strong> {{ $record->doctor && $record->doctor->specialty ? ucfirst($record->doctor->specialty) : '—' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-notes-medical" aria-hidden="true"></i>
                        <p>No medical history records found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="history-sidebar">
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fas fa-heart" aria-hidden="true"></i>
                    <h3>Health Summary</h3>
                </div>

                <div class="summary-list">
                    <div class="summary-item">
                        <span>Blood Type</span>
                        <strong>{{ optional($patient)->blood_type ?? 'N/A' }}</strong>
                    </div>

                    <div class="summary-item">
                        <span>Allergies</span>
                        <div class="allergy-pills">
                            @if(is_array($allergies) && count($allergies) > 0)
                                @foreach($allergies as $allergy)
                                    <span class="allergy-pill">{{ $allergy }}</span>
                                @endforeach
                            @else
                                <span class="allergy-pill allergy-pill--none">None</span>
                            @endif
                        </div>
                    </div>

                    <div class="summary-item">
                        <span>Height</span>
                        <strong>{{ optional($patient)->height ?? '--' }} cm</strong>
                    </div>

                    <div class="summary-item">
                        <span>Weight</span>
                        <strong>{{ optional($patient)->weight ?? '--' }} kg</strong>
                    </div>
                </div>

                @if($latestRecord)
                    <div class="latest-visit">
                        <div class="latest-label">Last Visit</div>
                        <div class="latest-value">
                            {{ $latestRecord->diagnosis_date ? \Carbon\Carbon::parse($latestRecord->diagnosis_date)->format('M d, Y') : $latestRecord->created_at->format('M d, Y') }}
                        </div>
                        <div class="latest-sub">
                            Dr. {{ optional($latestRecord->doctor?->user)->name ?? '—' }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .page-header {
            margin-bottom: 2.5rem;
        }

        .page-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
        }

        .page-header h1 {
            font-size: 1.875rem;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .history-count {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 12px 16px;
            box-shadow: var(--shadow);
            color: var(--primary);
            font-weight: 800;
            white-space: nowrap;
        }

        .history-count i {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            color: var(--primary);
        }

        .history-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        @media (max-width: 900px) {
            .history-grid {
                grid-template-columns: 1fr;
            }
        }

        .history-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .sidebar-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .sidebar-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .sidebar-card-header i {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            color: var(--primary);
        }

        .sidebar-card-header h3 {
            font-size: 1.05rem;
            font-weight: 800;
            margin: 0;
        }

        .summary-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 12px;
        }

        .summary-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .summary-item>span {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 700;
        }

        .summary-item>strong {
            font-size: 0.95rem;
            color: var(--text-main);
            font-weight: 800;
            text-align: right;
        }

        .allergy-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
            max-width: 180px;
        }

        .allergy-pill {
            background: #FEF2F2;
            color: #DC2626;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 800;
            border: 1px solid #FBCACA;
        }

        .allergy-pill--none {
            background: #F1F5F9;
            color: #64748b;
            border: 1px solid #E5E7EB;
            font-weight: 700;
        }

        .timeline-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .empty-state {
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            padding: 28px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            color: var(--text-muted);
            box-shadow: var(--shadow);
        }

        .empty-state p {
            margin: 0;
            font-weight: 700;
        }

        .empty-state i {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
        }

        .latest-visit {
            margin-top: 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
        }

        .latest-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .latest-value {
            margin-top: 6px;
            font-size: 1rem;
            font-weight: 900;
            color: var(--text-main);
        }

        .latest-sub {
            margin-top: 4px;
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 700;
        }
    </style>
@endsection
