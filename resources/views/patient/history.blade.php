@extends('layouts.dashboard')

@section('title', 'Medical History')

@section('content')
    <div class="page-header">
        <h1>Medical History</h1>
        <p class="text-muted">A complete record of your visits, reports, and prescriptions.</p>
    </div>

    <div class="history-grid">
        <div class="history-main">
            <!-- Records Timeline -->
            <div class="timeline">
                @forelse($history as $record)
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $loop->first ? 'bg-blue' : 'bg-green' }}"></div>
                            <div class="timeline-content">

                                <div class="timeline-header">
                                    <span class="date">
                                        Date: {{ \Carbon\Carbon::parse($record->diagnosis_date)->format('M d, Y') }}
                                    </span>
                                    <span class="type-badge laboratory">
                                            <a href="{{ route('patient.history.show',$record->id) }}" class="btn-icon"
                                                title="View Details"><i class="fas fa-eye"></i></a>
                                    </span>
                                </div>

                                <h3 style="color: rgb(178, 123, 123); margin-top:8px;">
                                    Condition: {{ $record->condition }}
                                </h3>

                                <div style="margin-top:10px; font-size:14px; color:#64748b; display:flex; flex-direction:column; gap:4px;">
                                    <div>
                                        <strong>Doctor:</strong> {{ $record->doctor->user->name }}
                                    </div>

                                    <div>
                                        <strong>Department:</strong> {{ ucfirst($record->doctor->specialty) }}
                                    </div>
                                </div>
                        </div>
                    </div>
                @empty
                    <p class="text-red-500 font-bold">* No medical history records found.</p>
                @endforelse
            </div>
        </div>

        <div class="history-sidebar">
            <div class="sidebar-card">
                <h3>Health Summary</h3>
                <div class="summary-list">
                    <div class="summary-item">
                        <span>Blood Type</span>
                        <strong>{{ Auth::user()->patient->blood_type ?? 'N/A' }}</strong>
                    </div>
                    <div class="summary-item">
                        <span>Allergies</span>
                        <div style="display: flex; flex-wrap: wrap; gap: 4px; justify-content: flex-end; max-width: 150px;">
                            @if(is_array(Auth::user()->patient->allergies) && count(Auth::user()->patient->allergies) > 0)
                                @foreach(Auth::user()->patient->allergies as $allergy)
                                    <span
                                        style="background: #FEF2F2; color: #DC2626; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">{{ $allergy }}</span>
                                @endforeach
                            @else
                                <strong class="text-red">None</strong>
                            @endif
                        </div>
                    </div>
                    <div class="summary-item">
                        <span>Height</span>
                        <strong>{{ Auth::user()->patient->height ?? (Auth::user()->patient->height ?? '--') }} cm</strong>
                    </div>
                    <div class="summary-item">
                        <span>Weight</span>
                        <strong>{{ Auth::user()->patient->weight ?? (Auth::user()->patient->weight ?? '--') }} kg</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .page-header {
            margin-bottom: 2.5rem;
        }

        .page-header h1 {
            font-size: 1.875rem;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .history-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #E5E7EB;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 3rem;
        }

        .timeline-marker {
            position: absolute;
            left: -2.35rem;
            top: 0.25rem;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #E5E7EB;
            z-index: 1;
        }

        .bg-blue {
            background: #3B82F6;
            box-shadow: 0 0 0 2px #3B82F6;
        }

        .bg-green {
            background: #10B981;
            box-shadow: 0 0 0 2px #10B981;
        }

        .bg-purple {
            background: #8B5CF6;
            box-shadow: 0 0 0 2px #8B5CF6;
        }

        .timeline-content {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .date {
            font-size: 0.875rem;
            color: #184eb9;
            font-weight: 500;
        }

        .type-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .laboratory {
            background: #EFF6FF;
            color: #1E40AF;
        }

        .prescription {
            background: #ECFDF5;
            color: #065F46;
        }

        .visit {
            background: #F5F3FF;
            color: #5B21B6;
        }

        .timeline-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.75rem;
        }

        .timeline-content p {
            font-size: 0.9375rem;
            color: #4B5563;
            line-height: 1.6;
        }

        .attachments {
            margin-top: 1.25rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .file-chip {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .file-chip span {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }

        .text-red {
            color: #EF4444;
        }

        .file-chip button {
            background: none;
            border: none;
            color: #9CA3AF;
            cursor: pointer;
            transition: color 0.2s;
        }

        .file-chip button:hover {
            color: #0D9488;
        }

        .prescription-card {
            margin-top: 1.25rem;
            border: 1px dashed #D1D5DB;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .rx-header {
            background: #F9FAFB;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px dashed #D1D5DB;
        }

        .rx-body {
            padding: 1rem;
        }

        .med {
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .med:last-child {
            margin-bottom: 0;
        }

        .history-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .sidebar-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .sidebar-card h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.75rem;
        }

        .sidebar-card p {
            font-size: 0.875rem;
            color: #6B7280;
            margin-bottom: 1.25rem;
        }

        .btn-block-outline {
            width: 100%;
            padding: 0.75rem;
            background: white;
            color: #0D9488;
            border: 1px solid #0D9488;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-block-outline:hover {
            background: #F0FDFA;
            scale: 1.02;
        }

        .summary-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #F3F4F6;
            padding-bottom: 0.75rem;
        }

        .summary-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .summary-item span {
            font-size: 0.875rem;
            color: #6B7280;
        }

        .summary-item strong {
            font-size: 0.875rem;
            color: #111827;
        }
    </style>
@endsection
