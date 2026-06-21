@extends('layouts.dashboard')

@section('title', 'AI Analysis Results')

@section('content')
    <style>
        .sym-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 15px;
            border-radius: 30px;
            font-size: 0.83rem;
            font-weight: 700;
            transition: transform 0.15s;
        }

        .sym-badge.detected {
            background: rgba(67, 97, 238, 0.12);
            color: #4361EE;
            border: 1px solid rgba(67, 97, 238, 0.3);
        }

        .sym-badge.absent {
            background: #f1f5f9;
            color: #94a3b8;
            border: 1px solid #e2e8f0;
            opacity: 0.6;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 13px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .urgency-pill {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 800;
        }

        @keyframes pulse-red {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(225, 29, 72, .45)
            }

            50% {
                box-shadow: 0 0 0 14px rgba(225, 29, 72, 0)
            }
        }

        .alert-pulse {
            animation: pulse-red 2s infinite;
        }

        @media(max-width:768px) {
            .two-col {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    @php
        $urgency = strtolower($symptomCheck->urgency);
        $aiResponse = $symptomCheck->ai_response ?? [];
        $features = $symptomCheck->symptoms_json['features'] ?? [];

        $symLabels = [
            'fever' => ['Fever', 'fa-thermometer-half'],
            'cough' => ['Cough', 'fa-head-side-cough'],
            'headache' => ['Headache', 'fa-brain'],
            'fatigue' => ['Fatigue', 'fa-battery-quarter'],
            'chest_pain' => ['Chest Pain', 'fa-heart'],
            'shortness_of_breath' => ['Shortness of Breath', 'fa-lungs'],
            'dizziness' => ['Dizziness', 'fa-dizzy'],
            'nausea' => ['Nausea', 'fa-hand-holding-water'],
            'sore_throat' => ['Sore Throat', 'fa-allergies'],
        ];

        $detected = collect($symLabels)->filter(fn($v, $k) => ($features[$k] ?? 0) == 1)->count();

        [$urgBg, $urgColor, $urgIcon, $urgMsg, $urgLabel] = match ($urgency) {
            'high' => [
                '#FFF1F2',
                '#E11D48',
                'fa-biohazard',
                'CRITICAL — Seek immediate emergency care. Do not delay.',
                'HIGH'
            ],
            'medium' => [
                '#FFF8E1',
                '#D97706',
                'fa-shield-alt',
                'MODERATE — Consult a doctor within 24–48 hours.',
                'MEDIUM'
            ],
            default => [
                '#E0F2F1',
                '#00796B',
                'fa-check-circle',
                'LOW — Symptoms appear manageable. Rest and monitor.',
                'LOW'
            ],
        };
    @endphp

    {{-- Page header --}}
    <div
        style="margin-bottom:26px; display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:14px;">
        <div>
            <h1 style="font-size:1.7rem; font-weight:800; color:var(--text-main); margin-bottom:5px;">AI Analysis Report
            </h1>
            <p style="color:var(--text-muted); font-size:0.92rem;">Symptoms cross-referenced with the diagnostic model.</p>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('symptoms.index') }}" class="btn-secondary"
                style="width:auto; padding:9px 18px; text-decoration:none; display:inline-flex; align-items:center; gap:7px;">
                <i class="fas fa-search-plus"></i> New Check
            </a>
            <a href="{{ route('symptoms.history') }}" class="btn-secondary"
                style="width:auto; padding:9px 18px; text-decoration:none; display:inline-flex; align-items:center; gap:7px;">
                <i class="fas fa-list-ul"></i> History
            </a>
        </div>
    </div>

    {{-- Critical Banner --}}
    @if($urgency === 'high')
        <div class="alert-pulse"
            style="background:#FFF1F2; border-left:6px solid #E11D48; border-radius:16px; padding:22px 28px; margin-bottom:26px; display:flex; align-items:center; gap:20px;">
            <div
                style="width:52px;height:52px;background:rgba(225,29,72,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#E11D48;font-size:1.7rem;">
                <i class="fas fa-hospital-user"></i>
            </div>
            <div>
                <h3 style="font-size:1.15rem;font-weight:800;color:#BE123C;margin-bottom:4px;">Immediate Action Required</h3>
                <p style="color:#9F1239;font-size:0.96rem;line-height:1.5;font-weight:600;">{{ $urgMsg }} If symptoms worsen,
                    call emergency services immediately.</p>
            </div>
        </div>
    @endif

    {{-- Main card --}}
    <div class="glass-card" style="padding:36px;border-radius:24px;margin-bottom:28px;">

        {{-- Header row --}}
        <div
            style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:14px;margin-bottom:28px;padding-bottom:24px;border-bottom:1px solid #f1f5f9;">
            <div>
                <p
                    style="font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:7px;">
                    <i class="fas fa-microchip" style="color:var(--primary);"></i> AI Prediction
                </p>
                <h2 style="font-size:1.9rem;font-weight:900;color:var(--text-main);margin-bottom:5px;">
                    {{ $symptomCheck->predicted_disease }}</h2>
                <p style="color:var(--text-muted);font-size:0.87rem;">
                    {{ $symptomCheck->created_at->format('M d, Y \a\t H:i') }}
                </p>
            </div>
            <span class="urgency-pill"
                style="background:{{ $urgBg }};color:{{ $urgColor }};border:1px solid {{ $urgColor }}44;display:inline-flex;align-items:center;gap:9px;flex-shrink:0;">
                <i class="fas {{ $urgIcon }}"></i> {{ $urgLabel }} URGENCY
            </span>
        </div>

        {{-- Two-column: description + care pathway --}}
        <div class="two-col" style="display:grid;grid-template-columns:1fr 1fr;gap:28px;">

            <div>
                {{-- Patient text --}}
                @if(isset($symptomCheck->symptoms_json['text']))
                    <div
                        style="background:#f8fafc;padding:20px;border-radius:14px;border:1px solid #e2e8f0;margin-bottom:18px;">
                        <h4
                            style="font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">
                            <i class="fas fa-quote-left" style="color:var(--primary);"></i> Your Description
                        </h4>
                        <p style="font-style:italic;color:var(--text-main);line-height:1.65;font-size:0.94rem;">
                            "{{ $symptomCheck->symptoms_json['text'] }}"
                        </p>
                    </div>
                @endif

                {{-- Assessment box --}}
                <div style="background:{{ $urgBg }}33;border:1px solid {{ $urgColor }}44;border-radius:14px;padding:18px;">
                    <h4
                        style="font-size:0.78rem;font-weight:700;color:{{ $urgColor }};text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">
                        <i class="fas {{ $urgIcon }}"></i> Assessment
                    </h4>
                    <p style="color:var(--text-main);font-size:0.94rem;line-height:1.6;">{{ $urgMsg }}</p>
                </div>
            </div>

            {{-- Care card --}}
            <div
                style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:1px solid #e2e8f0;border-radius:20px;padding:26px;display:flex;flex-direction:column;justify-content:space-between;">
                <div>
                    <h4
                        style="font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:18px;">
                        Recommended Care Pathway
                    </h4>
                    <div class="info-row">
                        <span style="font-size:0.87rem;color:var(--text-muted);">Predicted Condition</span>
                        <strong style="color:var(--text-main);">{{ $symptomCheck->predicted_disease }}</strong>
                    </div>
                    <div class="info-row">
                        <span style="font-size:0.87rem;color:var(--text-muted);">Consult Specialist</span>
                        <strong style="color:var(--primary);">{{ $symptomCheck->specialization }}</strong>
                    </div>
                    <div class="info-row">
                        <span style="font-size:0.87rem;color:var(--text-muted);">Urgency Level</span>
                        <span class="urgency-pill"
                            style="background:{{ $urgBg }};color:{{ $urgColor }};">{{ $urgLabel }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Matched Specialists --}}
    @if($urgency !== 'high')
        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
                <h3 style="font-size:1.4rem;font-weight:800;color:var(--text-main);">Top Matched Specialists</h3>
                <p style="color:var(--text-muted);font-size:0.88rem;">Specialty:
                    <strong>{{ $symptomCheck->specialization }}</strong></p>
            </div>

            @if($recommendedDoctors && $recommendedDoctors->count() > 0)
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;">
                    @foreach($recommendedDoctors as $doctor)
                        <div class="glass-card"
                            style="padding:26px;text-align:center;border:1px solid #f1f5f9;border-radius:20px;transition:all .3s;"
                            onmouseover="this.style.borderColor='var(--primary)';this.style.transform='translateY(-4px)'"
                            onmouseout="this.style.borderColor='#f1f5f9';this.style.transform='translateY(0)'">
                            <img src="{{ $doctor->user->profile_image ? asset('storage/' . $doctor->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($doctor->user->name) . '&background=4361EE&color=fff&size=128' }}"
                                alt="{{ $doctor->user->name }}"
                                style="width:68px;height:68px;border-radius:16px;object-fit:cover;margin:0 auto 14px;display:block;box-shadow:0 6px 12px rgba(0,0,0,.1);">
                            <h4 style="font-weight:800;color:var(--text-main);font-size:1rem;margin-bottom:6px;">
                                {{ $doctor->user->name }}</h4>
                            <span
                                style="font-size:0.8rem;color:var(--primary);font-weight:700;background:rgba(67,97,238,.08);padding:4px 12px;border-radius:20px;">{{ $doctor->specialty }}</span>
                            <div style="margin-top:18px;">
                                <a href="{{ route('patient.appointments') }}" class="btn-primary"
                                    style="display:inline-flex;gap:6px;align-items:center;padding:9px 18px;font-size:0.85rem;text-decoration:none;border-radius:10px;">
                                    <i class="fas fa-calendar-plus"></i> Book
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="glass-card" style="text-align:center;padding:50px;border-style:dashed;">
                    <i class="fas fa-stethoscope" style="font-size:2.5rem;color:#cbd5e1;margin-bottom:14px;"></i>
                    <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-main);margin-bottom:10px;">No direct specialty
                        match in our system</h3>
                    <p style="color:var(--text-muted);max-width:380px;margin:0 auto 22px;">Our General Practitioners can evaluate
                        and refer you.</p>
                    <a href="{{ route('patient.appointments') }}" class="btn-primary"
                        style="display:inline-flex;gap:8px;align-items:center;text-decoration:none;">
                        <i class="fas fa-user-md"></i> Browse All Doctors
                    </a>
                </div>
            @endif
        </div>
    @endif

@endsection