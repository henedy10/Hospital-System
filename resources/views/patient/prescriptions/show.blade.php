@extends('layouts.dashboard')
@section('title', 'وصفة طبية #' . $prescription->id)

@section('content')
<div style="max-width: 780px; margin: 0 auto; padding-bottom: 40px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:14px; margin-bottom:28px;">
        <a href="{{ route('patient.prescriptions.index') }}"
           style="color:#64748b; text-decoration:none; width:38px; height:38px; border-radius:10px; background:#f1f5f9; display:flex; align-items:center; justify-content:center;"
           onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div style="flex:1;">
            <h1 style="font-size:1.5rem; font-weight:700; color:#0f172a; margin:0;">وصفة طبية #{{ $prescription->id }}</h1>
            <p style="color:#64748b; margin:3px 0 0 0; font-size:.88rem;">
                د. {{ $prescription->doctor->user->name ?? 'N/A' }} &nbsp;·&nbsp; {{ $prescription->created_at->format('d M Y') }}
            </p>
        </div>
        <a href="{{ route('patient.prescriptions.explain', $prescription) }}"
           style="background:linear-gradient(135deg,#0d9488,#0891b2); color:#fff; padding:10px 20px; border-radius:10px; font-size:.88rem; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:8px; box-shadow:0 4px 14px rgba(13,148,136,.3); white-space:nowrap;">
            <i class="fas fa-robot"></i> شرح الوصفة
        </a>
    </div>

    {{-- Notes --}}
    @if($prescription->notes)
    <div style="background:#fefce8; border:1px solid #fde68a; border-radius:12px; padding:16px 20px; margin-bottom:20px; display:flex; gap:12px; align-items:flex-start;">
        <i class="fas fa-sticky-note" style="color:#d97706; margin-top:2px;"></i>
        <div>
            <div style="font-weight:600; color:#92400e; font-size:.85rem; margin-bottom:3px;">ملاحظات الطبيب</div>
            <div style="color:#78350f; font-size:.9rem;">{{ $prescription->notes }}</div>
        </div>
    </div>
    @endif

    {{-- Medicine Items --}}
    <div style="display:grid; gap:14px;">
        @foreach($prescription->items as $index => $item)
        <div style="background:#fff; border-radius:14px; box-shadow:0 1px 6px rgba(0,0,0,.07); overflow:hidden;">
            <div style="background:linear-gradient(135deg,#0f172a,#1e293b); padding:14px 20px; display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:28px; height:28px; background:rgba(255,255,255,.15); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:.8rem;">{{ $index + 1 }}</div>
                    <span style="color:#fff; font-weight:700; font-size:1rem;">{{ $item->medicine_name }}</span>
                </div>
                <span style="background:rgba(255,255,255,.15); color:#94d2bd; padding:4px 12px; border-radius:20px; font-size:.82rem; font-weight:600;">{{ $item->dosage }}</span>
            </div>
            <div style="padding:16px 20px; display:grid; grid-template-columns:repeat(3,1fr); gap:12px;">
                <div style="text-align:center; padding:12px; background:#f8fafc; border-radius:10px;">
                    <div style="color:#0d9488; font-size:1.2rem; margin-bottom:5px;"><i class="fas fa-clock"></i></div>
                    <div style="font-size:.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.04em;">التكرار</div>
                    <div style="font-weight:700; color:#1e293b; font-size:.9rem; margin-top:2px;">{{ $item->frequency }}x يومياً</div>
                </div>
                <div style="text-align:center; padding:12px; background:#f8fafc; border-radius:10px;">
                    <div style="color:#0d9488; font-size:1.2rem; margin-bottom:5px;"><i class="fas fa-calendar-alt"></i></div>
                    <div style="font-size:.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.04em;">المدة</div>
                    <div style="font-weight:700; color:#1e293b; font-size:.9rem; margin-top:2px;">{{ $item->duration }} يوم</div>
                </div>
                <div style="text-align:center; padding:12px; background:#f8fafc; border-radius:10px;">
                    <div style="color:#0d9488; font-size:1.2rem; margin-bottom:5px;"><i class="fas fa-info-circle"></i></div>
                    <div style="font-size:.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.04em;">التعليمات</div>
                    <div style="font-weight:700; color:#1e293b; font-size:.9rem; margin-top:2px;">{{ $item->instructions ?: '—' }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
