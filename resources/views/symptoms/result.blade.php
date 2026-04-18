@extends('layouts.dashboard')

@section('title', 'Analysis Results')

@section('content')
<div class="welcome-section" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
            AI Analysis Results
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 600px;">
            Review your preliminary diagnosis and recommended specialists below.
        </p>
    </div>
    <a href="{{ route('symptoms.index') }}" class="btn-secondary" style="width: auto; padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-arrow-left"></i> Back to Checker
    </a>
</div>

@php
    $urgency = strtolower($symptomCheck->urgency_level);
    
    // Default styling (medium/low)
    $urgencyBg = '#E0F2F1'; 
    $urgencyColor = '#00796B';
    $urgencyIcon = 'fa-check-circle';
    
    if($urgency === 'high') {
        $urgencyBg = '#FFF1F2';
        $urgencyColor = '#E11D48';
        $urgencyIcon = 'fa-exclamation-triangle';
    } elseif($urgency === 'medium') {
        $urgencyBg = '#FFF8E1';
        $urgencyColor = '#FFA000';
        $urgencyIcon = 'fa-bell';
    }
@endphp

@if($urgency === 'high')
<div style="background: #FFF1F2; border-left: 6px solid #E11D48; border-radius: 12px; padding: 24px; margin-bottom: 32px; display: flex; align-items: flex-start; gap: 20px; box-shadow: var(--shadow);">
    <div style="width: 48px; height: 48px; background: rgba(225, 29, 72, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #E11D48; font-size: 1.5rem;">
        <i class="fas fa-ambulance"></i>
    </div>
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: #BE123C; margin-bottom: 8px; text-transform: uppercase;">Medical Emergency Warning</h3>
        <p style="color: #9F1239; font-size: 0.95rem; line-height: 1.5; font-weight: 500;">
            Based on your symptoms, this could be a severe condition requiring immediate medical attention. 
            Please go to the nearest emergency room or call emergency services immediately.
        </p>
    </div>
</div>
@endif

<div class="glass-card" style="margin-bottom: 32px;">
    <!-- Header with Urgency Badge -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;">
        <h2 style="font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-file-medical-alt" style="color: var(--primary);"></i> Report Summary
        </h2>
        <span style="background: {{ $urgencyBg }}; color: {{ $urgencyColor }}; padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
            <i class="fas {{ $urgencyIcon }}"></i> {{ strtoupper($urgency) }} URGENCY
        </span>
    </div>

    <!-- Patient Symptoms -->
    <div style="margin-bottom: 32px;">
        <h3 style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">You reported</h3>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; font-style: italic; color: var(--text-main); line-height: 1.6;">
            "{{ $symptomCheck->symptoms_text }}"
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <!-- Possible Diseases -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; color: var(--primary);">
                <div style="width: 40px; height: 40px; background: var(--primary-light); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fas fa-viruses"></i>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">Possible Conditions</h3>
            </div>
            
            <ul style="list-style: none;">
                @if(!empty($symptomCheck->ai_response['possible_diseases']))
                    @foreach($symptomCheck->ai_response['possible_diseases'] as $disease)
                        <li style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                            <i class="fas fa-dot-circle" style="color: var(--secondary); font-size: 0.8rem; margin-top: 4px;"></i>
                            <span style="font-weight: 500; font-size: 0.95rem; color: var(--text-main);">{{ $disease }}</span>
                        </li>
                    @endforeach
                @else
                    <li style="color: var(--text-muted); font-style: italic;">No specific conditions identified.</li>
                @endif
            </ul>
        </div>

        <!-- Medical Advice -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; color: var(--secondary);">
                <div style="width: 40px; height: 40px; background: #e0f2fe; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fas fa-comment-medical"></i>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">Recommendation</h3>
            </div>
            <p style="color: var(--text-main); line-height: 1.6; font-size: 0.95rem;">
                {{ $symptomCheck->ai_response['medical_advice'] ?? 'Please consult a doctor for a proper diagnosis.' }}
            </p>
        </div>
    </div>

    <!-- Recommended Specialization -->
    <div style="background: linear-gradient(135deg, var(--primary-light), rgba(14, 165, 233, 0.1)); border: 1px solid var(--primary); border-radius: 16px; padding: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Recommended Specialist</h3>
            <p style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                {{ $symptomCheck->ai_response['recommended_specialization'] ?? 'General Practitioner' }}
            </p>
        </div>
        <div style="font-size: 3rem; color: rgba(13, 148, 136, 0.3);">
            <i class="fas fa-user-md"></i>
        </div>
    </div>
</div>

@if($urgency !== 'high')
<div class="welcome-section" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end;">
    <h2 style="font-size: 1.25rem; font-weight: 700;">Available Specialists</h2>
    <a href="{{ route('patient.appointments') }}" class="btn-secondary" style="padding: 8px 16px; font-size: 0.85rem;">View All Doctors</a>
</div>

@if($recommendedDoctors && $recommendedDoctors->count() > 0)
    <div class="patient-grid">
        @foreach($recommendedDoctors as $doctor)
            <div class="glass-card patient-card" style="padding-top: 50px;">
                <div class="patient-avatar-wrapper" style="width: 80px; height: 80px; top: -40px;">
                    <img class="patient-avatar" src="{{ $doctor->user->profile_image ? asset('storage/' . $doctor->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($doctor->user->name) . '&background=0D9488&color=fff' }}" alt="{{ $doctor->user->name }}">
                </div>
                
                <h3 class="patient-name">{{ $doctor->user->name }}</h3>
                <p class="patient-meta" style="color: var(--primary); font-weight: 600;">{{ $doctor->specialty }}</p>
                
                <div style="margin-top: 24px;">
                    <a href="{{ route('patient.appointments') }}" class="btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fa-regular fa-calendar-alt"></i> Book Appointment
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="glass-card" style="text-align: center; padding: 48px;">
        <div style="width: 64px; height: 64px; background: #f1f5f9; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; color: var(--text-muted); font-size: 1.5rem;">
            <i class="fas fa-search-minus"></i>
        </div>
        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 8px;">No specific specialists found</h3>
        <p style="color: var(--text-muted); margin-bottom: 24px;">We couldn't find a doctor specifically matching this specialty right now.</p>
        <a href="{{ route('patient.appointments') }}" class="btn-primary">Browse All Providers</a>
    </div>
@endif

@endif

@endsection
