@extends('layouts.dashboard')
@section('title', 'Prescriptions')

@section('content')
<div style="padding: 0 0 30px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 700; color: #0f172a; margin: 0;">Prescriptions</h1>
            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 0.92rem;">Manage all prescriptions you have written</p>
        </div>
        <a href="{{ route('doctor.prescriptions.create') }}"
           style="background: linear-gradient(135deg,#0d9488,#0891b2); color:#fff; padding: 10px 22px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(13,148,136,.3); transition: transform .2s;"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-plus"></i> New Prescription
        </a>
    </div>

    @if($prescriptions->isEmpty())
        <div style="text-align:center; padding: 80px 20px; background: #fff; border-radius: 16px; box-shadow: 0 1px 8px rgba(0,0,0,.06);">
            <i class="fas fa-prescription-bottle-alt" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px;"></i>
            <h3 style="color: #475569; font-weight: 600; margin: 0 0 8px 0;">No prescriptions yet</h3>
            <p style="color: #94a3b8; margin: 0;">Create your first prescription for a patient.</p>
        </div>
    @else
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 8px rgba(0,0,0,.06); overflow:hidden;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg,#0f172a,#1e293b); color:#fff;">
                        <th style="padding:14px 20px; text-align:left; font-size:.82rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">#</th>
                        <th style="padding:14px 20px; text-align:left; font-size:.82rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">Patient</th>
                        <th style="padding:14px 20px; text-align:left; font-size:.82rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">Medicines</th>
                        <th style="padding:14px 20px; text-align:left; font-size:.82rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">Date</th>
                        <th style="padding:14px 20px; text-align:left; font-size:.82rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescriptions as $prescription)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background .15s;"
                        onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px; color:#64748b; font-size:.9rem;">{{ $prescription->id }}</td>
                        <td style="padding:14px 20px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:36px; height:36px; background:linear-gradient(135deg,#0d9488,#0891b2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:.85rem; flex-shrink:0;">
                                    {{ strtoupper(substr($prescription->patient->user->name ?? 'P', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600; color:#1e293b; font-size:.9rem;">{{ $prescription->patient->user->name ?? 'N/A' }}</div>
                                    <div style="color:#94a3b8; font-size:.78rem;">Patient ID: {{ $prescription->patient_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:14px 20px;">
                            <div style="display:flex; flex-wrap:wrap; gap:6px;">
                                @foreach($prescription->items->take(3) as $item)
                                    <span style="background:#eff6ff; color:#1d4ed8; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:500;">
                                        {{ $item->medicine_name }}
                                    </span>
                                @endforeach
                                @if($prescription->items->count() > 3)
                                    <span style="background:#f1f5f9; color:#64748b; padding:3px 10px; border-radius:20px; font-size:.78rem;">
                                        +{{ $prescription->items->count() - 3 }} more
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:14px 20px; color:#64748b; font-size:.88rem;">
                            {{ $prescription->created_at->format('d M Y') }}
                        </td>
                        <td style="padding:14px 20px;">
                            <div style="display:flex; gap:8px;">
                                <a href="{{ route('doctor.prescriptions.show', $prescription) }}"
                                   style="background:#eff6ff; color:#1d4ed8; padding:7px 14px; border-radius:8px; font-size:.82rem; font-weight:500; text-decoration:none; display:flex; align-items:center; gap:5px; transition:background .15s;"
                                   onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form action="{{ route('doctor.prescriptions.destroy', $prescription) }}" method="POST"
                                      onsubmit="return confirm('Delete this prescription?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="background:#fff1f2; color:#e11d48; border:none; cursor:pointer; padding:7px 14px; border-radius:8px; font-size:.82rem; font-weight:500; display:flex; align-items:center; gap:5px; transition:background .15s;"
                                            onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff1f2'">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $prescriptions->links() }}
        </div>
    @endif
</div>
@endsection
