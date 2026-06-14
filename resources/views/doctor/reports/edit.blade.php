@extends('layouts.dashboard')
@section('title', 'Edit Report & Prescription')

@section('content')
<div style="max-width: 860px; margin: 0 auto; padding-bottom: 40px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:14px; margin-bottom:28px;">
        <a href="{{ route('doctor.reports') }}"
           style="color:#64748b; text-decoration:none; width:38px; height:38px; border-radius:10px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; transition:background .15s;"
           onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-size:1.5rem; font-weight:700; color:#0f172a; margin:0;">Edit Report & Prescription</h1>
            <p style="color:#64748b; margin:3px 0 0 0; font-size:.88rem;">Update medical details for Report #{{ $history->id }}</p>
        </div>
    </div>

    @if($errors->any())
        <div style="background:#fff1f2; border-left:4px solid #e11d48; border-radius:10px; padding:14px 18px; margin-bottom:22px;">
            <div style="font-weight:600; color:#be123c; margin-bottom:6px;"><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</div>
            <ul style="margin:0; padding-left:18px; color:#9f1239; font-size:.88rem;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('doctor.medical-history.update', $history->id) }}" method="POST" id="editReportForm">
        @csrf
        @method('PUT')

        {{-- Medical Report Info --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 8px rgba(0,0,0,.07); padding:28px; margin-bottom:22px;">
            <h2 style="font-size:1rem; font-weight:700; color:#0f172a; margin:0 0 20px 0; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-file-medical" style="color:#0ea5e9;"></i> Medical Condition
            </h2>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom: 18px;">
                <div>
                    <label style="display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:6px;">Patient</label>
                    <input type="text" value="{{ $history->patient->user->name ?? 'Patient #'.$history->patient_id }}" readonly
                           style="width:100%; padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:.9rem; color:#1e293b; background:#f1f5f9; outline:none; cursor:not-allowed;">
                </div>
                <div>
                    <label style="display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:6px;">Diagnosis Date <span style="color:#e11d48;">*</span></label>
                    <input type="date" name="diagnosis_date" required value="{{ old('diagnosis_date', $history->diagnosis_date ? $history->diagnosis_date->format('Y-m-d') : '') }}"
                           style="width:100%; padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:.9rem; color:#1e293b; background:#f8fafc; outline:none;">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:6px;">Medical Condition / Diagnosis <span style="color:#e11d48;">*</span></label>
                <input type="text" name="condition" required value="{{ old('condition', $history->condition) }}"
                       style="width:100%; padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:.9rem; color:#1e293b; background:#f8fafc; outline:none;">
            </div>

            <div>
                <label style="display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:6px;">Treatment / Notes <span style="color:#94a3b8;">(optional)</span></label>
                <textarea name="treatment" rows="4"
                          style="width:100%; padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:.9rem; color:#1e293b; background:#f8fafc; outline:none; resize:vertical;">{{ old('treatment', $history->treatment) }}</textarea>
            </div>
        </div>

        {{-- Prescription Notes --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 8px rgba(0,0,0,.07); padding:28px; margin-bottom:22px;">
            <h2 style="font-size:1rem; font-weight:700; color:#0f172a; margin:0 0 20px 0; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-clipboard-list" style="color:#0d9488;"></i> Prescription Info
            </h2>
            
            <div>
                <label style="display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:6px;">General Prescription Notes <span style="color:#94a3b8;">(optional)</span></label>
                <input type="text" name="prescription_notes" value="{{ old('prescription_notes', optional($history->prescription)->notes) }}" placeholder="e.g. Follow-up in 2 weeks"
                       style="width:100%; padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:.9rem; color:#1e293b; background:#f8fafc; outline:none;">
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
                {{-- Dynamic rows will be injected here via JavaScript based on existing data --}}
            </div>

            {{-- Submit --}}
            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px; padding-top:20px; border-top:1px solid #f1f5f9;">
                <a href="{{ route('doctor.reports') }}"
                   style="padding:11px 24px; border-radius:10px; border:1.5px solid #e2e8f0; color:#64748b; text-decoration:none; font-weight:600; font-size:.9rem;">
                    Cancel
                </a>
                <button type="submit"
                        style="background:linear-gradient(135deg,#0ea5e9,#2563eb); color:#fff; border:none; cursor:pointer; padding:11px 28px; border-radius:10px; font-weight:700; font-size:.9rem; box-shadow:0 4px 14px rgba(14,165,233,.3);">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

<script>


    let rowIndex = 0;
    let labRowIndex = 0;

    function addLabRow() {
        const container = document.getElementById('labRows');
        let testOpts = '<option value="">Select test</option>';
        labTestsOptions.forEach(t => { testOpts += `<option value="${t.id}">${t.name}</option>`; });
        let labOpts = '<option value="">Any lab</option>';
        labsOptions.forEach(l => {
            labOpts += `<option value="${l.id}">${l.name}${l.doctor ? ' (Dr. ' + l.doctor + ')' : ''}</option>`;
        });
        const row = document.createElement('div');
        row.style.cssText = 'border:1.5px solid #bfdbfe; border-radius:12px; padding:16px; margin-bottom:12px; position:relative; background:#f8fafc;';
        row.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" style="position:absolute; top:10px; right:10px; background:#fee2e2; color:#dc2626; border:none; width:28px; height:28px; border-radius:8px; cursor:pointer;"><i class="fas fa-times"></i></button>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div><label style="font-size:.82rem; font-weight:600;">Lab Test *</label><select name="lab_items[${labRowIndex}][lab_test_id]" required style="width:100%; padding:9px; border-radius:9px; border:1px solid #e2e8f0;">${testOpts}</select></div>
                <div><label style="font-size:.82rem; font-weight:600;">Preferred Lab</label><select name="lab_items[${labRowIndex}][lab_id]" style="width:100%; padding:9px; border-radius:9px; border:1px solid #e2e8f0;">${labOpts}</select></div>
            </div>
            <div style="margin-top:10px;"><label style="font-size:.82rem; font-weight:600;">Notes</label><input type="text" name="lab_items[${labRowIndex}][notes]" placeholder="e.g. Fasting required" style="width:100%; padding:9px; border-radius:9px; border:1px solid #e2e8f0;"></div>`;
        container.appendChild(row);
        labRowIndex++;
    }

    function addMedicineRow(medicine = '', dosage = '', frequency = '', duration = '', instructions = '') {
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
                    <input type="text" name="items[${rowIndex}][medicine_name]" required value="${medicine}" placeholder="e.g. Paracetamol"
                           style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                </div>
                <div>
                    <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Dosage <span style="color:#e11d48;">*</span></label>
                    <input type="text" name="items[${rowIndex}][dosage]" required value="${dosage}" placeholder="e.g. 500mg"
                           style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                </div>
                <div>
                    <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Times/Day <span style="color:#e11d48;">*</span></label>
                    <input type="number" name="items[${rowIndex}][frequency]" required min="1" max="10" value="${frequency}" placeholder="3"
                           style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                </div>
                <div>
                    <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Duration (days) <span style="color:#e11d48;">*</span></label>
                    <input type="number" name="items[${rowIndex}][duration]" required min="1" max="365" value="${duration}" placeholder="7"
                           style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
                </div>
            </div>
            <div>
                <label style="display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:5px;">Instructions <span style="color:#94a3b8;">(optional)</span></label>
                <input type="text" name="items[${rowIndex}][instructions]" value="${instructions}" placeholder="e.g. after food, before sleep"
                       style="width:100%; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.88rem; box-sizing:border-box; outline:none; background:#fff;">
            </div>`;
        container.appendChild(row);
        rowIndex++;
    }

    function removeRow(btn) {
        btn.closest('.medicine-row').remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const existingItems = @json(optional($history->prescription)->items ?? []);
        
        if (existingItems && existingItems.length > 0) {
            existingItems.forEach(item => {
                addMedicineRow(item.medicine_name, item.dosage, item.frequency, item.duration, item.instructions || '');
            });
        } else {
            // Optional: Start with an empty row if they want to add a prescription
            // addMedicineRow();
        }
    });
</script>

<style>
@keyframes fadeIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
</style>
@endsection
