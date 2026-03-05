@extends('layouts.dashboard')

@section('title', 'تفاصيل التقرير الطبي')

@section('content')
    <div class="welcome-section"
        style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <a href="{{ route('doctor.reports') }}"
            style="text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.9rem;">
            <i class="fas fa-arrow-right"></i> العودة للتقارير
        </a>
        <div style="display: flex; gap: 12px;">
            <button class="btn-outline" onclick="window.print()" style="width: auto; padding: 10px 24px;">
                <i class="fas fa-print"></i> طباعة الوثيقة
            </button>
            <button class="btn-primary" style="width: auto; padding: 10px 24px; margin-top: 0;">
                <i class="fas fa-file-pdf"></i> تحميل PDF
            </button>
        </div>
    </div>

    <div class="paper-container" dir="rtl">
        <!-- Header -->
        <header class="document-header">
            <div class="hospital-brand">
                <div class="icon-box bg-teal" style="width: 50px; height: 50px; border-radius: 12px;">
                    <i class="fas fa-hospital-user"></i>
                </div>
                <div class="brand-info">
                    <h1>Hospital Sys</h1>
                    <p>المركز الطبي التخصصي الحديث</p>
                </div>
            </div>
            <div class="report-meta-box">
                <h2>تقرير حالة طبية</h2>
                <p>رقم التقرير: #{{ $report['id'] }}</p>
                <p>بتاريخ: {{ $report['date'] }}</p>
            </div>
        </header>

        <!-- Patient Info Section -->
        <section class="patient-doc-section">
            <div style="text-align: center; border-left: 1px solid #e2e8f0; padding-left: 20px;">
                <img src="{{ $report['patient']['avatar'] }}" alt="Patient"
                    style="width: 100px; height: 100px; border-radius: 20px; margin-bottom: 15px;">
                <h3 style="font-size: 1.1rem; font-weight: 700;">{{ $report['patient']['name'] }}</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted);">رقم المريض: {{ $report['patient']['id'] }}</p>
            </div>
            <div>
                <div class="doc-section-title">بيانات المريض الأساسية</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="detail-item"><span class="detail-label">العمر:</span> <span
                            class="detail-value">{{ $report['patient']['age'] }} عاماً</span></div>
                    <div class="detail-item"><span class="detail-label">فصيلة الدم:</span> <span
                            class="detail-value">{{ $report['patient']['blood_type'] }}</span></div>
                    <div class="detail-item"><span class="detail-label">الوزن:</span> <span
                            class="detail-value">{{ $report['patient']['weight'] }}</span></div>
                    <div class="detail-item"><span class="detail-label">القسم:</span> <span
                            class="detail-value">{{ $report['department_ar'] }}</span></div>
                </div>
            </div>
        </section>

        <!-- Medical Findings -->
        <section class="content-block">
            <div class="doc-section-title"><i class="fas fa-stethoscope"></i> المؤشرات الحيوية</div>
            <div class="vitals-row">
                @foreach($report['vitals'] as $vital)
                    <div class="vital-tag">
                        <label>{{ $stat['label'] ?? $vital['label'] }}</label>
                        <span>{{ $vital['value'] }}</span>
                        <p
                            style="font-size: 0.7rem; margin-top: 4px; color: {{ $vital['status'] == 'Normal' ? '#166534' : '#991B1B' }}; font-weight: 700;">
                            {{ $vital['status'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="content-block">
            <div class="doc-section-title"><i class="fas fa-notes-medical"></i> التشخيص الطبي</div>
            <p style="background: #f8fafc; padding: 20px; border-radius: 8px; border-right: 4px solid var(--primary);">
                {{ $report['diagnosis'] }}
            </p>
        </section>

        <section class="content-block">
            <div class="doc-section-title"><i class="fas fa-user-md"></i> ملاحظات الطبيب المعالج</div>
            <p>{{ $report['clinical_notes'] }}</p>
        </section>

        <section class="content-block">
            <div class="doc-section-title"><i class="fas fa-pills"></i> الخطة العلاجية والتوصيات</div>
            <ul style="padding-right: 20px; line-height: 2;">
                @foreach($report['treatment_plan'] as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ul>
        </section>

        <!-- Footer / Signature -->
        <footer class="signature-area">
            <div class="signature-box">
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 40px;">تم التوقيع إلكترونياً بواسطة
                </p>
                <div class="sig-line"></div>
                <p style="font-weight: 700;">د. جون دو</p>
                <p style="font-size: 0.8rem; color: var(--text-muted);">استشاري جراحة القلب</p>
            </div>
        </footer>
    </div>

    <style>
        /* Final touch for the document view */
        .paper-container li {
            font-size: 0.95rem;
            color: var(--text-main);
        }
    </style>
@endsection