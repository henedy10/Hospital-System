@extends('layouts.dashboard')

@section('title', 'قائمة المواعيد')

@section('content')
    <div class="welcome-section"
        style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">جدول المواعيد 📅
            </h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">قم بإدارة مواعيدك وعروض المراجعة اليومية بشكل فعال.</p>
        </div>
        <button class="btn-primary" style="width: auto; padding: 10px 24px; margin-top: 0;">
            <i class="fas fa-plus"></i> إضافة موعد جديد
        </button>
    </div>

    <div class="glass-card">
        <!-- Filters and Search -->
        <div class="search-container">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" class="form-control" placeholder="البحث عن اسم المريض...">
            </div>
            <select class="select-control">
                <option value="">كل الحالات</option>
                <option value="Confirmed">تم التأكيد</option>
                <option value="Pending">قيد الانتظار</option>
                <option value="Cancelled">ملغي</option>
            </select>
            <input type="date" class="form-control" style="width: auto; padding-left: 16px;" value="2026-02-28">
        </div>

        <!-- Appointments Table -->
        <div class="table-responsive">
            <table class="custom-table" dir="rtl">
                <thead>
                    <tr>
                        <th>اسم المريض</th>
                        <th>الوقت</th>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td style="font-weight: 600;">{{ $appointment['patient_name'] }}</td>
                            <td>{{ $appointment['time'] }}</td>
                            <td>{{ $appointment['date'] }}</td>
                            <td>{{ $appointment['type'] }}</td>
                            <td>
                                <span class="badge badge-{{ strtolower($appointment['status']) }}">
                                    <i
                                        class="fas fa-{{ $appointment['status'] == 'Confirmed' ? 'check-circle' : ($appointment['status'] == 'Pending' ? 'clock' : 'times-circle') }}"></i>
                                    {{ $appointment['status_ar'] }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="#" class="btn-icon" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn-icon" title="عرض التفاصيل"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn-icon delete" title="إلغاء الموعد"><i
                                            class="fas fa-trash-alt"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection