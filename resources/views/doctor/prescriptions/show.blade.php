@extends('layouts.dashboard')
@section('title', 'Prescription #' . $prescription->id)

@section('content')
<div style="max-width: 820px; margin: 0 auto; padding-bottom: 40px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:14px; margin-bottom:28px;">
        <a href="{{ route('doctor.prescriptions.index') }}"
           style="color:#64748b; text-decoration:none; width:38px; height:38px; border-radius:10px; background:#f1f5f9; display:flex; align-items:center; justify-content:center;"
           onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-size:1.5rem; font-weight:700; color:#0f172a; margin:0;">Prescription #{{ $prescription->id }}</h1>
            <p style="color:#64748b; margin:3px 0 0 0; font-size:.88rem;">{{ $prescription->created_at->format('d F Y') }}</p>
        </div>
        <form action="{{ route('doctor.prescriptions.destroy', $prescription) }}" method="POST"
              onsubmit="return confirm('Delete this prescription?')" style="margin-left:auto;">
            @csrf @method('DELETE')
            <button type="submit"
                    style="background:#fff1f2; color:#e11d48; border:none; cursor:pointer; padding:9px 18px; border-radius:9px; font-weight:600; font-size:.85rem; display:flex; align-items:center; gap:6px;">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>

    {{-- Patient & Doctor Info --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
        <div style="background:#fff; border-radius:14px; padding:20px; box-shadow:0 1px 6px rgba(0,0,0,.06);">
            <div style="font-size:.78rem; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; font-weight:600; margin-bottom:10px;">Patient</div>
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:44px; height:44px; background:linear-gradient(135deg,#0d9488,#0891b2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:1rem;">
                    {{ strtoupper(substr($prescription->patient->user->name ?? 'P', 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:700; color:#1e293b;">{{ $prescription->patient->user->name ?? 'N/A' }}</div>
                    <div style="color:#94a3b8; font-size:.82rem;">Patient ID: {{ $prescription->patient_id }}</div>
                </div>
            </div>
        </div>
        <div style="background:#fff; border-radius:14px; padding:20px; box-shadow:0 1px 6px rgba(0,0,0,.06);">
            <div style="font-size:.78rem; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; font-weight:600; margin-bottom:10px;">Notes</div>
            <p style="color:#475569; margin:0; font-size:.9rem; line-height:1.6;">
                {{ $prescription->notes ?: '—' }}
            </p>
        </div>
    </div>

    {{-- Medicine Items --}}
    <div style="background:#fff; border-radius:16px; box-shadow:0 1px 8px rgba(0,0,0,.07); overflow:hidden;">
        <div style="padding:20px 24px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px;">
            <i class="fas fa-pills" style="color:#0d9488;"></i>
            <h2 style="font-size:1rem; font-weight:700; color:#0f172a; margin:0;">Medicines ({{ $prescription->items->count() }})</h2>
        </div>
        <div style="padding:20px 24px;">
            @foreach($prescription->items as $index => $item)
            <div style="border:1.5px solid #e2e8f0; border-radius:12px; padding:18px; margin-bottom:14px; {{ $loop->last ? '' : 'margin-bottom:14px;' }}">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px; height:32px; background:#eff6ff; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; font-weight:700; font-size:.8rem;">{{ $index + 1 }}</div>
                        <span style="font-weight:700; color:#1e293b; font-size:1rem;">{{ $item->medicine_name }}</span>
                    </div>
                    <span style="background:#f0fdf4; color:#16a34a; padding:4px 12px; border-radius:20px; font-size:.78rem; font-weight:600;">{{ $item->dosage }}</span>
                </div>
                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px;">
                    <div style="background:#f8fafc; border-radius:9px; padding:10px 14px;">
                        <div style="font-size:.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.04em; margin-bottom:3px;">Frequency</div>
                        <div style="font-weight:600; color:#1e293b; font-size:.88rem;">{{ $item->frequency }}x per day</div>
                    </div>
                    <div style="background:#f8fafc; border-radius:9px; padding:10px 14px;">
                        <div style="font-size:.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.04em; margin-bottom:3px;">Duration</div>
                        <div style="font-weight:600; color:#1e293b; font-size:.88rem;">{{ $item->duration }} days</div>
                    </div>
                    <div style="background:#f8fafc; border-radius:9px; padding:10px 14px;">
                        <div style="font-size:.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.04em; margin-bottom:3px;">Instructions</div>
                        <div style="font-weight:600; color:#1e293b; font-size:.88rem;">{{ $item->instructions ?: '—' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
