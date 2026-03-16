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
