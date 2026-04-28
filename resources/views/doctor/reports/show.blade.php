@extends('layouts.dashboard')

@section('title', 'Medical Report Details')

@section('content')
    <div class="welcome-section"
        style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <a href="{{ $role === 'doctor' ? route('doctor.reports') : route('patient.history') }}"
            style="text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
        <div style="display: flex; gap: 12px;">
            <button class="btn-outline" onclick="window.print()" style="width: auto; padding: 10px 24px;">
                <i class="fas fa-print"></i> Print Report
            </button>
        </div>
    </div>

    <div class="paper-container">
        <!-- Header -->
        <header class="document-header">
            <div class="hospital-brand">
                <div class="icon-box bg-teal" style="width: 50px; height: 50px; border-radius: 12px;">
                    <i class="fas fa-hospital-user"></i>
                </div>
                <div class="brand-info">
                    <h1>Hospital Sys</h1>
                    <p>Modern Specialized Medical Center</p>
                </div>
            </div>
            <div class="report-meta-box">
                <h2>Medical Status Report</h2>
                <p>Report No: #{{ $report['id'] }}</p>
                <p>Date: {{ $report['date'] }}</p>
            </div>
        </header>

        <!-- Patient Info Section -->
        <section class="patient-doc-section">
            <div style="display:flex; flex-direction:column; align-items:center; border-right: 1px solid #e2e8f0; padding-right: 20px; gap:8px;">
                <img src="{{ $report['patient']['avatar'] }}" alt="Patient"
                    style="width: 100px; height: 100px; border-radius: 20px; margin-bottom: 10px;">

                <h3 style="font-size: 1.1rem; font-weight: 700;">
                    {{ $report['patient']['name'] }}
                </h3>

                <p style="font-size: 0.85rem; color: var(--text-muted);">
                    Patient ID: {{ $report['patient']['id'] }}
                </p>
            </div>

            <div>
                <div class="doc-section-title">Essential Patient Data</div>
<div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

    <div class="detail-item" style="display:flex; justify-content:space-evenly; align-items:center;">
        <span class="detail-label">Age:</span>
        <span class="detail-value">{{ $report['patient']['age'] }} Years</span>
    </div>

    <div class="detail-item" style="display:flex; justify-content:space-evenly; align-items:center;">
        <span class="detail-label">Blood Type:</span>
        <span class="detail-value">{{ $report['patient']['blood_type'] }}</span>
    </div>

    <div class="detail-item" style="display:flex; justify-content:space-evenly; align-items:center;">
        <span class="detail-label">Weight:</span>
        <span class="detail-value">{{ $report['patient']['weight'] }}</span>
    </div>

    <div class="detail-item" style="display:flex; justify-content:space-evenly; align-items:center;">
        <span class="detail-label">Department:</span>
        <span class="detail-value">{{ $report['department_en'] }}</span>
    </div>

</div>
            </div>
        </section>

        <!-- Medical Findings -->
        {{-- <section class="content-block">
            <div class="doc-section-title"><i class="fas fa-stethoscope"></i> Vitals</div>
            <div class="vitals-row">
                @foreach($report['vitals'] as $vital)
                    <div class="vital-tag">
                        <label>{{ $vital['label'] }}</label>
                        <span>{{ $vital['value'] }}</span>
                        <p
                            style="font-size: 0.7rem; margin-top: 4px; color: {{ $vital['status'] == 'Normal' ? '#166534' : '#991B1B' }}; font-weight: 700;">
                            {{ $vital['status'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section> --}}

        <section class="content-block">
            <div class="doc-section-title"><i class="fas fa-notes-medical"></i> Medical Diagnosis</div>
            <p style="background: #f8fafc; padding: 20px; border-radius: 8px; border-left: 4px solid var(--primary);">
                {{ $report['diagnosis'] }}
            </p>
        </section>

        <section class="content-block">
            <div class="doc-section-title"><i class="fas fa-pills"></i> Treatment Plan & Recommendations</div>
            <ul style="padding-left: 20px; line-height: 2;">
                @foreach($report['treatment_plan'] as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ul>
        </section>

        <!-- Prescription Section -->
        <section class="content-block">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <div class="doc-section-title" style="margin-bottom:0;"><i class="fas fa-prescription-bottle-alt"></i> Prescription</div>
                @if($report['prescription'])
                    @if($role === 'patient')
                        <a href="{{ route('patient.prescriptions.explain', $report['prescription']->id) }}" class="btn-outline" style="text-decoration:none; display:flex; align-items:center; gap:6px; color:#0d9488; border-color:#0d9488;">
                            <i class="fas fa-robot"></i> Analyze with AI
                        </a>
                    @endif
                @else
                    @if($role === 'doctor')
                        <a href="{{ route('doctor.prescriptions.create', ['report_id' => $report['id']]) }}" class="btn-outline" style="text-decoration:none; display:flex; align-items:center; gap:6px; color:#0d9488; border-color:#0d9488;">
                            <i class="fas fa-plus"></i> Add Prescription
                        </a>
                    @else
                        <span style="font-size:0.85rem; color:var(--text-muted);">No prescription added</span>
                    @endif
                @endif
            </div>

            @if($report['prescription'])
                <div style="background:#f8fafc; border-radius:8px; padding:20px; border-left:4px solid #0d9488; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    @if($report['prescription']->notes)
                        <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px dashed #cbd5e1;">
                            <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;"><i class="fas fa-clipboard-list" style="color: #0d9488; margin-right: 6px;"></i> Prescription Notes</h4>
                            <p style="font-size: 0.9rem; color: #475569; line-height: 1.5; margin: 0;">{{ $report['prescription']->notes }}</p>
                        </div>
                    @endif
                    <div style="display:flex; flex-wrap:wrap; gap:12px;">
                        @foreach($report['prescription']->items as $item)
                            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:6px; padding:12px; display:flex; flex-direction:column; gap:4px; width:100%;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <i class="fas fa-capsules" style="color:#0d9488;"></i>
                                    <span style="font-weight:600; color:#1e293b; font-size:1rem;">{{ $item->medicine_name }}</span>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-top:8px;">
                                    <span style="font-size:0.85rem; color:#475569;"><strong>Dosage:</strong> <span style="background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $item->dosage }}</span></span>
                                    <span style="font-size:0.85rem; color:#475569;"><strong>Freq:</strong> {{ $item->frequency }} / day</span>
                                    <span style="font-size:0.85rem; color:#475569;"><strong>Duration:</strong> {{ $item->duration }} days</span>
                                </div>
                                @if($item->instructions)
                                    <div style="margin-top:6px; padding-top:6px; border-top:1px dashed #cbd5e1; font-size:0.85rem; color:#64748b;">
                                        <strong>Instructions:</strong> {{ $item->instructions }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <!-- Footer / Signature -->
        <footer class="signature-area">
            <div class="signature-box">
                <div class="sig-line"></div>
                <p style="font-weight: 700;">Dr. {{$report['doctor']->user->name}}</p>
                <p style="font-size: 0.8rem; color: var(--text-muted);">{{Str::ucfirst($report['doctor']->specialty)}} Consultant</p>
            </div>
        </footer>
    </div>

    <style>
        .paper-container li {
            font-size: 0.95rem;
            color: var(--text-main);
        }
    </style>
@endsection
