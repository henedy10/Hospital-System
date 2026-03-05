@extends('layouts.dashboard')

@section('title', 'ملف المريض السريري')

@section('content')
    <div class="welcome-section"
        style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <a href="{{ route('doctor.patients') }}"
            style="text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.9rem;">
            <i class="fas fa-arrow-right"></i> العودة لقائمة المرضى
        </a>
        <div style="display: flex; gap: 12px;">
            <button class="btn-outline" style="width: auto; padding: 10px 24px;">
                <i class="fas fa-edit"></i> تعديل البيانات
            </button>
            <button class="btn-primary" style="width: auto; padding: 10px 24px; margin-top: 0;">
                <i class="fas fa-calendar-plus"></i> حجز موعد
            </button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px;" dir="rtl">
        <!-- Patient Sidebar (Profile Card) -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="glass-card" style="padding: 32px; text-align: center;">
                <div style="position: relative; display: inline-block; margin-bottom: 20px;">
                    <img src="{{ $patient['avatar'] }}" alt="Patient"
                        style="width: 120px; height: 120px; border-radius: 32px; object-fit: cover; border: 4px solid #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.05);">
                    <span
                        style="position: absolute; bottom: 8px; right: 0; background: var(--primary); color: #fff; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #fff; font-size: 0.8rem; font-weight: 800;">
                        {{ $patient['blood_type'] }}
                    </span>
                </div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 8px;">
                    {{ $patient['name'] }}</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">معرف المريض:
                    #{{ $patient['id'] }}</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; text-align: right;">
                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px;">
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">العمر</span>
                        <span style="font-weight: 700;">{{ $patient['age'] }} سنة</span>
                    </div>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px;">
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">الجنس</span>
                        <span style="font-weight: 700;">{{ $patient['gender'] }}</span>
                    </div>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px;">
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">الوزن</span>
                        <span style="font-weight: 700;">{{ $patient['weight'] }}</span>
                    </div>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px;">
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">الطول</span>
                        <span style="font-weight: 700;">{{ $patient['height'] }}</span>
                    </div>
                </div>
            </div>

            <div class="glass-card" style="padding: 24px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;">الأدوية الحالية 💊</h3>
                @foreach($patient['medications'] as $med)
                    <div class="med-card">
                        <div class="med-info">
                            <h5>{{ $med['name'] }}</h5>
                            <p>{{ $med['dosage'] }} يومياً</p>
                        </div>
                        <span class="med-status {{ strtolower($med['status']) }}">
                            {{ $med['status'] == 'Active' ? 'نشط' : 'متوقف' }}
                        </span>
                    </div>
                @endforeach
                <button class="btn-outline" style="margin-top: 10px;"><i class="fas fa-plus"></i> إضافة دواء</button>
            </div>
        </div>

        <!-- Main Profile Content -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Vitals Tracking -->
            <div class="glass-card" style="padding: 24px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">تطور المؤشرات الحيوية 📈</h3>
                <canvas id="vitalsChart" height="200"></canvas>
            </div>

            <!-- Clinical Timeline -->
            <div class="glass-card" style="padding: 24px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">الجدول الزمني للزيارات (Timeline) ⏳
                </h3>
                <div class="timeline">
                    @foreach($patient['timeline'] as $event)
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $event['color'] }}">
                                <i class="{{ $event['icon'] }}"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-date">{{ $event['date'] }}</div>
                                <div class="timeline-type">{{ $event['type'] }}</div>
                                <p class="timeline-desc">{{ $event['desc'] }}</p>
                                <div style="margin-top: 10px; font-size: 0.8rem; font-weight: 700; color: var(--primary);">
                                    بواسطة: {{ $event['doctor'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Vitals Chart
            const vtCtx = document.getElementById('vitalsChart').getContext('2d');
            new Chart(vtCtx, {
                type: 'line',
                data: {
                    labels: ['25 Jan', '10 Feb', '20 Feb'],
                    datasets: [
                        {
                            label: 'ضغط الدم (Systolic)',
                            data: [138, 140, 135],
                            borderColor: '#0D9488',
                            backgroundColor: 'rgba(13, 148, 136, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'نبض القلب',
                            data: [75, 78, 72],
                            borderColor: '#0EA5E9',
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { font: { family: 'Inter', size: 11 } } }
                    },
                    scales: {
                        y: { beginAtZero: false, grid: { color: '#f1f5f9' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
@endsection