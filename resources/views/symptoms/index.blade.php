@extends('layouts.dashboard')

@section('title', 'AI Symptom Checker')

@section('content')
<div class="welcome-section" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
            <i class="fas fa-stethoscope" style="color: var(--primary); margin-right: 8px;"></i>
            AI Symptom Checker (Optimized)
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 600px;">
            Select all the symptoms you are experiencing. Our high-accuracy model will analyze patterns and suggest the next steps.
        </p>
    </div>
    <a href="{{ route('symptoms.history') }}" class="btn-secondary" style="width: auto; padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-history"></i> View History
    </a>
</div>

<div class="glass-card" style="max-width: 900px; margin: 0 auto; padding: 32px;">
    <form action="{{ route('symptoms.analyze') }}" method="POST" id="symptomForm">
        @csrf
        
        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 16px; border-left: 4px solid var(--primary); padding-left: 12px;">
                Identify Your Symptoms
            </h3>
            
            <div class="symptom-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px;">
                @php
                    $symptoms = [
                        'fever' => ['label' => 'Fever', 'icon' => 'fa-temperature-high'],
                        'cough' => ['label' => 'Cough', 'icon' => 'fa-pills'],
                        'headache' => ['label' => 'Headache', 'icon' => 'fa-head-side-virus'],
                        'fatigue' => ['label' => 'Fatigue', 'icon' => 'fa-bed'],
                        'chest_pain' => ['label' => 'Chest Pain', 'icon' => 'fa-heart-broken'],
                        'shortness_of_breath' => ['label' => 'Shortness of Breath', 'icon' => 'fa-wind'],
                        'dizziness' => ['label' => 'Dizziness', 'icon' => 'fa-redo-alt'],
                        'nausea' => ['label' => 'Nausea', 'icon' => 'fa-stomach'],
                        'sore_throat' => ['label' => 'Sore Throat', 'icon' => 'fa-mouth']
                    ];
                @endphp

                @foreach ($symptoms as $key => $data)
                <label class="symptom-card" style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: all 0.2s ease; position: relative;">
                    <input type="checkbox" name="{{ $key }}" style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">{{ $data['label'] }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; align-items: center; border-top: 1px solid #e2e8e0; padding-top: 24px; margin-top: 32px;">
            <button type="submit" id="analyzeBtn" class="btn-primary" style="padding: 14px 32px; font-size: 1rem; border-radius: 12px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);">
                <i class="fas fa-brain" style="margin-right: 8px;"></i> Deep AI Analysis
            </button>
        </div>
    </form>
</div>

<div style="text-align: center; margin-top: 32px;">
    <p style="color: var(--text-muted); font-size: 0.85rem; display: flex; justify-content: center; align-items: center; gap: 8px;">
        <i class="fas fa-shield-virus" style="color: var(--primary);"></i>
        Our model is optimized for preliminary triage. In case of severe respiratory distress, call emergency services.
    </p>
</div>

<script>
    document.getElementById('symptomForm').addEventListener('submit', function() {
        const btn = document.getElementById('analyzeBtn');
        btn.innerHTML = '<i class="fas fa-microchip fa-spin" style="margin-right: 8px;"></i> Processing Patterns...';
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';
    });

    document.querySelectorAll('.symptom-card').forEach(card => {
        const checkbox = card.querySelector('input');
        
        card.addEventListener('click', (e) => {
            if (e.target !== checkbox) {
                checkbox.checked = !checkbox.checked;
            }
            updateStyles();
        });

        function updateStyles() {
            if (checkbox.checked) {
                card.style.borderColor = 'var(--primary)';
                card.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
                card.style.boxShadow = '0 4px 12px rgba(67, 97, 238, 0.1)';
            } else {
                card.style.borderColor = '#e2e8f0';
                card.style.backgroundColor = 'transparent';
                card.style.boxShadow = 'none';
            }
        }
    });
</script>
@endsection
