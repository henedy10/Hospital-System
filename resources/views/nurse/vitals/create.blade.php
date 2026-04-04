@extends('layouts.dashboard')

@section('title', 'Record Vitals')

@section('content')
    <div style="max-width: 800px; margin: 0 auto; animation: fadeIn 0.8s ease-out;">
        <div class="welcome-section" style="margin-bottom: 32px; text-align: center;">
            <h1 style="font-size: 1.85rem; font-weight: 850; color: #1e293b; margin-bottom: 8px; letter-spacing: -0.02em;">Vital Signs Registry</h1>
            <p style="color: #64748b; font-size: 1rem; font-weight: 500;">Logging clinical measurements for Patient 
                <span style="color: #0D9488; font-weight: 800;">#{{ $patientId }}</span>
            </p>
        </div>

        <div class="glass-card" style="padding: 40px; border-radius: 28px; background: white; border: 1px solid #f1f5f9; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);">
            <form action="{{ route('nurse.vitals.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patientId }}">

                <!-- Cardiovascular Group -->
                <div style="margin-bottom: 40px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #fff1f2; color: #e11d48; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h3 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0;">Cardiovascular Status</h3>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                        <div>
                            <label style="display: flex; justify-content: space-between; font-weight: 700; margin-bottom: 10px; color: #475569; font-size: 0.95rem;">
                                Blood Pressure
                                <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Ref: 120/80 mmHg</span>
                            </label>
                            <input type="text" name="blood_pressure" placeholder="e.g. 120/80"
                                style="width: 100%; padding: 14px; border-radius: 12px; border: 2px solid #f1f5f9; background: #f8fafc; outline: none; font-weight: 600; font-family: inherit; transition: all 0.2s;"
                                required>
                        </div>
                        <div>
                            <label style="display: flex; justify-content: space-between; font-weight: 700; margin-bottom: 10px; color: #475569; font-size: 0.95rem;">
                                Pulse Rate
                                <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Ref: 60-100 bpm</span>
                            </label>
                            <input type="number" name="heart_rate" placeholder="e.g. 72"
                                style="width: 100%; padding: 14px; border-radius: 12px; border: 2px solid #f1f5f9; background: #f8fafc; outline: none; font-weight: 600; font-family: inherit; transition: all 0.2s;"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Respiratory & Thermal Group -->
                <div style="margin-bottom: 40px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-wind" style="font-size: 0.9rem;"></i>
                        </div>
                        <h3 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0;">Metabolic & Respiratory</h3>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                        <div>
                            <label style="display: flex; justify-content: space-between; font-weight: 700; margin-bottom: 10px; color: #475569; font-size: 0.95rem;">
                                Temperature
                                <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Ref: 36.5-37.5 °C</span>
                            </label>
                            <input type="number" step="0.1" name="temperature" placeholder="e.g. 37.0"
                                style="width: 100%; padding: 14px; border-radius: 12px; border: 2px solid #f1f5f9; background: #f8fafc; outline: none; font-weight: 600; font-family: inherit; transition: all 0.2s;"
                                required>
                        </div>
                        <div>
                            <label style="display: flex; justify-content: space-between; font-weight: 700; margin-bottom: 10px; color: #475569; font-size: 0.95rem;">
                                SpO2 Saturation
                                <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Ref: 95-100 %</span>
                            </label>
                            <input type="number" name="spo2" placeholder="e.g. 98"
                                style="width: 100%; padding: 14px; border-radius: 12px; border: 2px solid #f1f5f9; background: #f8fafc; outline: none; font-weight: 600; font-family: inherit; transition: all 0.2s;"
                                required>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 40px;">
                    <label style="display: block; font-weight: 700; margin-bottom: 12px; color: #475569; font-size: 0.95rem;">Observation Notes</label>
                    <textarea name="notes" rows="4" placeholder="Document any visible distress, mental state, or clinical abnormalities..."
                        style="width: 100%; padding: 16px; border-radius: 16px; border: 2px solid #f1f5f9; background: #f8fafc; outline: none; font-weight: 500; font-family: inherit; resize: none; transition: all 0.2s;"></textarea>
                </div>

                <div style="display: flex; gap: 20px;">
                    <button type="submit"
                        style="flex: 1.5; background: #0D9488; color: white; padding: 16px; border-radius: 16px; border: none; font-weight: 800; font-size: 1rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.2);">
                        Finalize & Save Registry
                    </button>
                    <a href="{{ route('nurse.patients.show', $patientId) }}"
                        style="flex: 1; text-align: center; background: #f1f5f9; color: #64748b; padding: 16px; border-radius: 16px; text-decoration: none; font-weight: 700; font-size: 1rem; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                        Discard
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        input:focus, textarea:focus {
            border-color: #0D9488 !important;
            background: white !important;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection