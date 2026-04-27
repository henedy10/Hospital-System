@extends('layouts.dashboard')

@section('title', 'Deep AI Analysis Results')

@section('content')
<div class="welcome-section" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
            Optimized Analysis Report
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 600px;">
            Our deep analysis model has cross-referenced your symptoms with its diagnostic database.
        </p>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('symptoms.index') }}" class="btn-secondary" style="width: auto; padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-search-plus"></i> New Check
        </a>
        <a href="{{ route('symptoms.history') }}" class="btn-secondary" style="width: auto; padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-list-ul"></i> History
        </a>
    </div>
</div>

@php
    $urgency = strtolower($symptomCheck->urgency);
    
    $urgencyBg = '#E0F2F1'; 
    $urgencyColor = '#00796B';
    $urgencyIcon = 'fa-check-circle';
    $urgencyMessage = "Low Priority: Your symptoms appear manageable, but monitoring is advised.";
    
    if($urgency === 'high') {
        $urgencyBg = '#FFF1F2';
        $urgencyColor = '#E11D48';
        $urgencyIcon = 'fa-biohazard';
        $urgencyMessage = "CRITICAL: Urgent medical evaluation is required based on high-risk symptom patterns.";
    } elseif($urgency === 'medium') {
        $urgencyBg = '#FFF8E1';
        $urgencyColor = '#D97706';
        $urgencyIcon = 'fa-shield-alt';
        $urgencyMessage = "MODERATE: Persistent symptoms detected. Professional consultation is recommended within 24-48 hours.";
    }
@endphp

@if($urgency === 'high')
<div style="background: #FFF1F2; border-left: 6px solid #E11D48; border-radius: 16px; padding: 28px; margin-bottom: 32px; display: flex; align-items: center; gap: 24px; box-shadow: 0 10px 25px rgba(225, 29, 72, 0.15);">
    <div style="width: 64px; height: 64px; background: rgba(225, 29, 72, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #E11D48; font-size: 2rem;">
        <i class="fas fa-hospital-user"></i>
    </div>
    <div>
        <h3 style="font-size: 1.35rem; font-weight: 800; color: #BE123C; margin-bottom: 6px;">Immediate Action Required</h3>
        <p style="color: #9F1239; font-size: 1.05rem; line-height: 1.5; font-weight: 600;">
            {{ $urgencyMessage }} Seek emergency care if symptoms worsen.
        </p>
    </div>
</div>
@endif

<div class="glass-card" style="margin-bottom: 32px; padding: 40px; border-radius: 24px;">
    <!-- Result Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 24px; border-bottom: 1px solid #f1f5f9;">
        <div>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 8px;">
                {{ $symptomCheck->predicted_disease }}
            </h2>
            <p style="color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-microchip" style="color: var(--primary);"></i> AI Classification Result
            </p>
        </div>
        <div style="text-align: right;">
            <span style="background: {{ $urgencyBg }}; color: {{ $urgencyColor }}; padding: 10px 24px; border-radius: 40px; font-weight: 800; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 10px; border: 1px solid {{ $urgencyColor }}33;">
                <i class="fas {{ $urgencyIcon }}"></i> {{ strtoupper($urgency) }} URGENCY
            </span>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">Analyzed on {{ $symptomCheck->created_at->format('M d, Y H:i') }}</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px;">
        <!-- Symptom Analysis -->
        <div>
            <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px;">Symptoms Processed</h4>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @foreach($symptomCheck->symptoms_json as $key => $value)
                    @if($value)
                        <span style="background: white; border: 2px solid #f1f5f9; padding: 10px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 700; color: var(--text-main); display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='#f1f5f9'">
                            <i class="fas fa-check-circle" style="color: var(--primary);"></i>
                            {{ ucwords(str_replace('_', ' ', $key)) }}
                        </span>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Care Pathway -->
        <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; border-radius: 30px; padding: 32px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; font-size: 8rem; color: rgba(67, 97, 238, 0.03);">
                <i class="fas fa-user-md"></i>
            </div>
            
            <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px;">Recommended Care Pathway</h4>
            <div style="margin-bottom: 32px; position: relative;">
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 8px;">Consultation Specialist:</p>
                <p style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                   {{ $symptomCheck->specialization }}
                </p>
                <p style="color: var(--text-main); line-height: 1.6; font-size: 1rem; margin-top: 12px; font-weight: 500;">
                    {{ $urgencyMessage }}
                </p>
            </div>
            
            <a href="{{ route('patient.appointments') }}" class="btn-primary" style="width: 100%; justify-content: center; padding: 18px; border-radius: 16px; font-weight: 700; font-size: 1.05rem; box-shadow: 0 10px 15px -3px rgba(67, 97, 238, 0.3);">
                <i class="fas fa-calendar-check" style="margin-right: 10px;"></i> Instant Booking
            </a>
        </div>
    </div>
</div>

@if($urgency !== 'high')
<div style="margin-top: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main);">Top Matched Specialists</h3>
        <p style="color: var(--text-muted); font-weight: 500;">Based on predicted specialty: <strong>{{ $symptomCheck->specialization }}</strong></p>
    </div>

    @if($recommendedDoctors && $recommendedDoctors->count() > 0)
        <div class="patient-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
            @foreach($recommendedDoctors as $doctor)
                <div class="glass-card" style="padding: 30px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 1px solid #f1f5f9;" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='scale(1.03)'" onmouseout="this.style.borderColor='#f1f5f9'; this.style.transform='scale(1)'">
                    <div style="text-align: center; margin-bottom: 24px;">
                        <img src="{{ $doctor->user->profile_image ? asset('storage/' . $doctor->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($doctor->user->name) . '&background=4361EE&color=fff&size=128' }}" 
                             alt="{{ $doctor->user->name }}" 
                             style="width: 80px; height: 80px; border-radius: 20px; object-fit: cover; box-shadow: 0 8px 15px rgba(0,0,0,0.1);">
                        <h4 style="font-weight: 800; color: var(--text-main); margin-top: 16px; font-size: 1.1rem;">Dr. {{ $doctor->user->name }}</h4>
                        <span style="font-size: 0.85rem; color: var(--primary); font-weight: 700; background: rgba(67, 97, 238, 0.08); padding: 4px 12px; border-radius: 20px;">{{ $doctor->specialty }}</span>
                    </div>
                    
                    <a href="{{ route('patient.book.appointment', $doctor->id) }}" class="btn-primary" style="width: 100%; justify-content: center; border-radius: 12px; background: var(--text-main);">
                        Book Visit
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="glass-card" style="text-align: center; padding: 60px; background: rgba(248, 250, 252, 0.5); border-style: dashed;">
            <i class="fas fa-stethoscope" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 20px;"></i>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main);">No direct matches for this specialty</h3>
            <p style="color: var(--text-muted); max-width: 400px; margin: 12px auto 32px;">You can book with our General Practitioners who can provide a referral after evaluation.</p>
            <a href="{{ route('patient.appointments') }}" class="btn-primary" style="display: inline-flex;">Browse All Doctors</a>
        </div>
    @endif
</div>
@endif

@endsection
