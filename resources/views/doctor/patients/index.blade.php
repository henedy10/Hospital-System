@extends('layouts.dashboard')

@section('title', 'قائمة المرضى')

@section('content')
    <div class="welcome-section"
        style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">سجل المرضى
                المتقدم 🩺</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">إدارة شاملة لملفات المرضى والتحليلات السريرية.</p>
        </div>
        <button class="btn-primary" style="width: auto; padding: 10px 24px; margin-top: 0;">
            <i class="fas fa-plus"></i> إضافة مريض جديد
        </button>
    </div>

    <!-- Stats Header -->
    <div class="stats-header-grid">
        @foreach($stats as $stat)
            <div class="glass-card" style="padding: 20px; display: flex; align-items: center; gap: 20px;">
                <div class="icon-box {{ $stat['color'] }}" style="width: 50px; height: 50px; border-radius: 14px;">
                    <i class="{{ $stat['icon'] }}"></i>
                </div>
                <div>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 4px;">{{ $stat['label'] }}</p>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">{{ $stat['value'] }}</h3>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Improved Filters -->
    <div class="glass-card" style="margin-bottom: 32px; padding: 20px;">
        <div class="search-container" style="margin-bottom: 0;">
            <div class="search-input-wrapper" style="flex: 2;">
                <i class="fas fa-search"></i>
                <input type="text" class="form-control" placeholder="البحث عن اسم المريض، رقم الهوية، أو الحالة...">
            </div>
            <select class="select-control" style="flex: 1;">
                <option value="">جميع الأقسام</option>
                <option value="Cardiology">القلب</option>
                <option value="Orthopedics">العظام</option>
            </select>
            <button class="btn-outline" style="width: auto; padding: 0 20px;"><i class="fas fa-sliders-h"></i> فلاتر
                متقدمة</button>
        </div>
    </div>

    <!-- Enhanced Patients Grid -->
    <div class="patient-grid" dir="rtl">
        @forelse($patients as $patient)
            <div class="glass-card patient-card" style="transition: transform 0.3s ease; cursor: pointer;"
                onclick="window.location='{{ route('doctor.patients.show', $patient->id) }}'">
                <div class="patient-avatar-wrapper" style="margin-bottom: 15px;">
                    <img src="{{ $patient->profile_image ? asset('storage/' . $patient->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($patient->name) . '&background=0D9488&color=fff' }}"
                        alt="{{ $patient->name }}" class="patient-avatar" style="width: 70px; height: 70px;">
                    @if($patient->blood_type)
                        <span
                            style="position: absolute; bottom: 0; left: 50%; transform: translate(-50%, 50%); background: var(--primary); color: #fff; font-size: 0.65rem; padding: 2px 8px; border-radius: 10px; font-weight: 700;">
                            {{ $patient->blood_type }}
                        </span>
                    @endif
                </div>
                <div class="patient-name" style="font-size: 1.1rem; margin-bottom: 4px;">{{ $patient->name }}</div>
                <div class="patient-meta" style="margin-bottom: 20px;">
                    <i class="fas fa-venus-mars" style="font-size: 0.7rem; opacity: 0.7;"></i> ذكر •
                    {{ \Carbon\Carbon::parse($patient->dob)->age }} عاماً
                </div>

                <div class="patient-details"
                    style="background: #f8fafc; padding: 12px; border-radius: 12px; margin-bottom: 20px;">
                    <div class="detail-item" style="border-bottom: 1px solid #eef2f6; padding-bottom: 8px; margin-bottom: 8px;">
                        <span class="detail-label"><i class="far fa-calendar-alt"></i> آخر زيارة</span>
                        <span
                            class="detail-value">{{ $patient->medicalHistories->first() ? $patient->medicalHistories->first()->diagnosis_date : '--' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-diagnoses"></i> الحالة</span>
                        <span class="detail-value"
                            style="color: var(--primary); font-weight: 700;">{{ $patient->medicalHistories->first() ? $patient->medicalHistories->first()->condition : 'لا يوجد سجل' }}</span>
                    </div>
                </div>

                <div class="patient-actions" style="gap: 10px;">
                    <a href="{{ route('doctor.patients.show', $patient->id) }}" class="btn-primary-sm"
                        style="flex: 1; text-decoration: none;">الملف السريري</a>
                    <a href="#" class="btn-icon" style="border-radius: 10px;"><i class="fas fa-ellipsis-h"></i></a>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <p style="color: var(--text-muted);">لا يوجد مرضى مسجلين حالياً.</p>
            </div>
        @endforelse
    </div>
@endsection