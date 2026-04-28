@extends('layouts.dashboard')
@section('title', 'My Prescriptions')

@section('content')
<div style="padding-bottom: 40px;">

    <div style="margin-bottom: 28px;">
        <h1 style="font-size:1.6rem; font-weight:700; color:#0f172a; margin:0;">وصفاتي الطبية</h1>
        <p style="color:#64748b; margin:5px 0 0 0; font-size:.92rem;">جميع الوصفات الطبية الصادرة من أطبائك</p>
    </div>

    @if($prescriptions->isEmpty())
        <div style="text-align:center; padding:80px 20px; background:#fff; border-radius:16px; box-shadow:0 1px 8px rgba(0,0,0,.06);">
            <i class="fas fa-file-prescription" style="font-size:3.5rem; color:#cbd5e1; margin-bottom:18px;"></i>
            <h3 style="color:#475569; font-weight:600; margin:0 0 8px 0;">لا توجد وصفات طبية بعد</h3>
            <p style="color:#94a3b8; margin:0;">ستظهر وصفاتك الطبية هنا بعد أن يكتبها طبيبك.</p>
        </div>
    @else
        <div style="display:grid; gap:16px;">
            @foreach($prescriptions as $prescription)
            <div style="background:#fff; border-radius:16px; box-shadow:0 1px 8px rgba(0,0,0,.07); padding:22px 26px; display:flex; align-items:center; gap:20px; transition:box-shadow .2s;"
                 onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.12)'" onmouseout="this.style.boxShadow='0 1px 8px rgba(0,0,0,.07)'">

                {{-- Icon --}}
                <div style="width:54px; height:54px; background:linear-gradient(135deg,#0d9488,#0891b2); border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-prescription-bottle-alt" style="color:#fff; font-size:1.4rem;"></i>
                </div>

                {{-- Info --}}
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:6px;">
                        <span style="font-weight:700; color:#1e293b; font-size:1rem;">وصفة طبية #{{ $prescription->id }}</span>
                        <span style="background:#f0fdf4; color:#16a34a; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600;">
                            {{ $prescription->items->count() }} {{ $prescription->items->count() === 1 ? 'دواء' : 'أدوية' }}
                        </span>
                    </div>
                    <div style="color:#64748b; font-size:.86rem; margin-bottom:8px;">
                        <i class="fas fa-user-md" style="color:#0d9488; margin-right:5px;"></i>
                        د. {{ $prescription->doctor->user->name ?? 'N/A' }}
                        &nbsp;·&nbsp;
                        <i class="fas fa-calendar" style="color:#94a3b8; margin-right:4px;"></i>
                        {{ $prescription->created_at->format('d M Y') }}
                    </div>
                    {{-- Medicine pills preview --}}
                    <div style="display:flex; flex-wrap:wrap; gap:6px;">
                        @foreach($prescription->items->take(4) as $item)
                            <span style="background:#eff6ff; color:#1d4ed8; padding:3px 10px; border-radius:20px; font-size:.77rem; font-weight:500;">
                                {{ $item->medicine_name }}
                            </span>
                        @endforeach
                        @if($prescription->items->count() > 4)
                            <span style="background:#f1f5f9; color:#64748b; padding:3px 10px; border-radius:20px; font-size:.77rem;">
                                +{{ $prescription->items->count() - 4 }} أخرى
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex; flex-direction:column; gap:8px; flex-shrink:0;">
                    <a href="{{ route('patient.prescriptions.explain', $prescription) }}"
                       style="background:linear-gradient(135deg,#0d9488,#0891b2); color:#fff; padding:9px 18px; border-radius:10px; font-size:.84rem; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:7px; box-shadow:0 3px 10px rgba(13,148,136,.3); white-space:nowrap;">
                        <i class="fas fa-robot"></i> شرح الوصفة
                    </a>
                    <a href="{{ route('patient.prescriptions.show', $prescription) }}"
                       style="background:#f8fafc; color:#475569; padding:9px 18px; border-radius:10px; font-size:.84rem; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:7px; border:1.5px solid #e2e8f0; white-space:nowrap;">
                        <i class="fas fa-eye"></i> عرض التفاصيل
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top:20px;">
            {{ $prescriptions->links() }}
        </div>
    @endif
</div>
@endsection
