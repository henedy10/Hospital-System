@extends('layouts.dashboard')
@section('title', 'New Prescription')

@section('content')
<div style="max-width: 860px; margin: 0 auto; padding-bottom: 40px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:14px; margin-bottom:28px;">
        <a href="{{ route('doctor.prescriptions.index') }}"
           style="color:#64748b; text-decoration:none; width:38px; height:38px; border-radius:10px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; transition:background .15s;"
           onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-size:1.5rem; font-weight:700; color:#0f172a; margin:0;">New Prescription</h1>
            <p style="color:#64748b; margin:3px 0 0 0; font-size:.88rem;">Fill in the prescription details below</p>
        </div>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
        <div style="background:#fff1f2; border-left:4px solid #e11d48; border-radius:10px; padding:14px 18px; margin-bottom:22px;">
            <div style="font-weight:600; color:#be123c; margin-bottom:6px;"><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</div>
            <ul style="margin:0; padding-left:18px; color:#9f1239; font-size:.88rem;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('doctor.prescriptions.store') }}" method="POST" id="prescriptionForm">
        @csrf

        @if(isset($medical_history))
            <input type="hidden" name="medical_history_id" value="{{ $medical_history->id }}">
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px 18px; margin-bottom:20px; display:flex; gap:10px; align-items:center;">
                <i class="fas fa-link" style="color:#16a34a;"></i>
                <span style="color:#166534; font-size:.9rem; font-weight:600;">Linking this prescription to Medical Report #{{ $medical_history->id }}</span>
            </div>
        @endif

        {{-- Patient & Notes card --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 8px rgba(0,0,0,.07); padding:28px; margin-bottom:22px;">
            <h2 style="font-size:1rem; font-weight:700; color:#0f172a; margin:0 0 20px 0; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-user-injured" style="color:#0d9488;"></i> Patient & General Info
            </h2>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                <div>
                    <label style="display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:6px;">Patient <span style="color:#e11d48;">*</span></label>
                    <select name="patient_id" required {{ isset($medical_history) ? 'readonly style=pointer-events:none;background:#f1f5f9;' : '' }}
                            style="width:100%; padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:.9rem; color:#1e293b; background:#f8fafc; outline:none; appearance:none;">
                        <option value="">-- Select Patient --</option>
                        @foreach($patients as $patient)
                            @php
                                $selected = false;
                                if (old('patient_id') == $patient->id) {
                                    $selected = true;
                                } elseif (isset($medical_history) && $medical_history->patient_id == $patient->id) {
                                    $selected = true;
                                }
                            @endphp
                            <option value="{{ $patient->id }}" {{ $selected ? 'selected' : '' }}>
                                {{ $patient->user->name ?? 'Patient #'.$patient->id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:6px;">Doctor Notes <span style="color:#94a3b8;">(optional)</span></label>
                    <input type="text" name="notes" value="{{ old('notes') }}"
                           placeholder="e.g. Take with plenty of water, follow-up in 2 weeks"
                           style="width:100%; padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:.9rem; color:#1e293b; background:#f8fafc; box-sizing:border-box; outline:none;">
                </div>
            </div>
        </div>

        {{-- Medicine Items --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 8px rgba(0,0,0,.07); padding:28px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h2 style="font-size:1rem; font-weight:700; color:#0f172a; margin:0; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-pills" style="color:#0d9488;"></i> Medicines
                </h2>
                <button type="button" onclick="addMedicineRow()"
                        style="background:linear-gradient(135deg,#0d9488,#0891b2); color:#fff; border:none; cursor:pointer; padding:8px 18px; border-radius:9px; font-size:.85rem; font-weight:600; display:flex; align-items:center; gap:6px;">
                    <i class="fas fa-plus"></i> Add Medicine
                </button>
            </div>

            <div id="medicineRows">
                {{-- Initial row --}}
                <div class="medicine-row" style="border:1.5px solid #e2e8f0; border-radius:12px; padding:18px; margin-bottom:14px; position:relative; background:#fafbfc;">
                    <button type="button" onclick="removeRow(this)"
                            style="position:absolute; top:12px; right:12px; background:#fff1f2; color:#e11d48; border:none; cursor:pointer; width:30px; height:30px; border-radius:8px; font-size:.85rem; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-times"></i>
                    </button>
                    <div style="display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:14px; margin-bottom:14px;">
                        <div>
                            <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Medicine Name <span style="color:#e11d48;">*</span></label>
                            <input type="text" name="items[0][medicine_name]" required placeholder="e.g. Paracetamol"
                                   style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                        </div>
                        <div>
                            <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Dosage <span style="color:#e11d48;">*</span></label>
                            <input type="text" name="items[0][dosage]" required placeholder="e.g. 500mg"
                                   style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                        </div>
                        <div>
                            <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Times/Day <span style="color:#e11d48;">*</span></label>
                            <input type="number" name="items[0][frequency]" required min="1" max="10" placeholder="e.g. 3"
                                   style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                        </div>
                        <div>
                            <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Duration (days) <span style="color:#e11d48;">*</span></label>
                            <input type="number" name="items[0][duration]" required min="1" max="365" placeholder="e.g. 7"
                                   style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                        </div>
                    </div>
                    <div>
                        <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Instructions <span style="color:#94a3b8;">(optional)</span></label>
                        <input type="text" name="items[0][instructions]" placeholder="e.g. after food, before sleep, with water"
                               style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px; padding-top:20px; border-top:1px solid #f1f5f9;">
                <a href="{{ route('doctor.prescriptions.index') }}"
                   style="padding:11px 24px; border-radius:10px; border:1.5px solid #e2e8f0; color:#64748b; text-decoration:none; font-weight:600; font-size:.9rem;">
                    Cancel
                </a>
                <button type="submit"
                        style="background:linear-gradient(135deg,#0d9488,#0891b2); color:#fff; border:none; cursor:pointer; padding:11px 28px; border-radius:10px; font-weight:700; font-size:.9rem; box-shadow:0 4px 14px rgba(13,148,136,.3);">
                    <i class="fas fa-save"></i> Save Prescription
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    let rowIndex = 1;

    function addMedicineRow() {
        const container = document.getElementById('medicineRows');
        const row = document.createElement('div');
        row.className = 'medicine-row';
        row.style.cssText = 'border:1.5px solid #e2e8f0; border-radius:12px; padding:18px; margin-bottom:14px; position:relative; background:#fafbfc; animation: fadeIn .25s ease;';
        row.innerHTML = `
            <button type="button" onclick="removeRow(this)"
                    style="position:absolute; top:12px; right:12px; background:#fff1f2; color:#e11d48; border:none; cursor:pointer; width:30px; height:30px; border-radius:8px; font-size:.85rem; display:flex; align-items:center; justify-content:center;">
                <i class="fas fa-times"></i>
            </button>
            <div style="display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:14px; margin-bottom:14px;">
                <div>
                    <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Medicine Name <span style="color:#e11d48;">*</span></label>
                    <input type="text" name="items[${rowIndex}][medicine_name]" required placeholder="e.g. Amoxicillin"
                           style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                </div>
                <div>
                    <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Dosage <span style="color:#e11d48;">*</span></label>
                    <input type="text" name="items[${rowIndex}][dosage]" required placeholder="e.g. 250mg"
                           style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                </div>
                <div>
                    <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Times/Day <span style="color:#e11d48;">*</span></label>
                    <input type="number" name="items[${rowIndex}][frequency]" required min="1" max="10" placeholder="3"
                           style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                </div>
                <div>
                    <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Duration (days) <span style="color:#e11d48;">*</span></label>
                    <input type="number" name="items[${rowIndex}][duration]" required min="1" max="365" placeholder="7"
                           style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                </div>
            </div>
            <div>
                <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Instructions <span style="color:#94a3b8;">(optional)</span></label>
                <input type="text" name="items[${rowIndex}][instructions]" placeholder="e.g. after food, before sleep"
                       style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
            </div>`;
        container.appendChild(row);
        rowIndex++;
    }

    function removeRow(btn) {
        const rows = document.querySelectorAll('.medicine-row');
        if (rows.length === 1) { alert('At least one medicine is required.'); return; }
        btn.closest('.medicine-row').remove();
    }
</script>

<style>
@keyframes fadeIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
</style>
@endsection
