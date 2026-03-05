@extends('layouts.dashboard')

@section('title', 'Record Vitals')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div class="welcome-section" style="margin-bottom: 32px;">
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Record Patient
                Vitals</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Enter the latest measurements for patient ID:
                {{ $patientId }}</p>
        </div>

        <div class="glass-card" style="padding: 32px;">
            <form action="{{ route('nurse.vitals.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patientId }}">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Blood
                            Pressure</label>
                        <input type="text" name="bp" placeholder="e.g. 120/80"
                            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none;"
                            required>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Heart
                            Rate (bpm)</label>
                        <input type="number" name="pulse" placeholder="e.g. 72"
                            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none;"
                            required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div>
                        <label
                            style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Temperature
                            (°C)</label>
                        <input type="number" step="0.1" name="temp" placeholder="e.g. 37.0"
                            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none;"
                            required>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Oxygen
                            Saturation (%)</label>
                        <input type="number" name="oxygen" placeholder="e.g. 98"
                            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none;"
                            required>
                    </div>
                </div>

                <div style="margin-bottom: 32px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Nursing
                        Notes</label>
                    <textarea name="notes" rows="4" placeholder="Any clinical observations..."
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none;"></textarea>
                </div>

                <div style="display: flex; gap: 16px;">
                    <button type="submit"
                        style="flex: 1; background: var(--primary); color: white; padding: 14px; border-radius: 8px; border: none; font-weight: 700; cursor: pointer;">Save
                        Records</button>
                    <a href="{{ route('nurse.patients.show', $patientId) }}"
                        style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 14px; border-radius: 8px; text-decoration: none; font-weight: 700;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection