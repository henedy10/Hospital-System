@extends('layouts.dashboard')

@section('title', 'AI Symptom Checker')

@section('content')
<div class="welcome-section" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
            <i class="fas fa-stethoscope" style="color: var(--primary); margin-right: 8px;"></i>
            AI Symptom Checker 
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 600px;">
            Describe how you feel in your own words. Our AI will analyze your description and suggest the next steps.
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
                Describe Your Symptoms
            </h3>
            
            <div style="position: relative;">
                <textarea 
                    name="symptoms_text" 
                    id="symptoms_text" 
                    class="form-control" 
                    rows="6" 
                    placeholder="Example: I have been feeling very tired lately, and I have a dry cough and a mild fever that started two days ago. I also feel some chest pain when I breathe deeply."
                    required
                    style="width: 100%; border-radius: 16px; border: 1px solid #e2e8f0; padding: 20px; font-size: 1rem; line-height: 1.6; resize: none; background: #f8fafc; transition: all 0.3s ease;"
                ></textarea>
                <div style="margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap;">
                    <span style="font-size: 0.75rem; color: #64748b; background: #f1f5f9; padding: 4px 12px; border-radius: 12px;">Try: "I have a severe headache"</span>
                    <span style="font-size: 0.75rem; color: #64748b; background: #f1f5f9; padding: 4px 12px; border-radius: 12px;">Try: "I feel nauseous and dizzy"</span>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; align-items: center; border-top: 1px solid #e2e8e0; padding-top: 24px; margin-top: 32px;">
            <button type="submit" id="analyzeBtn" class="btn-primary" style="padding: 14px 32px; font-size: 1rem; border-radius: 12px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2); transition: all 0.2s ease;">
                <i class="fas fa-brain" style="margin-right: 8px;"></i> Start AI Analysis
            </button>
        </div>
    </form>
</div>

<div style="text-align: center; margin-top: 32px;">
    <p style="color: var(--text-muted); font-size: 0.85rem; display: flex; justify-content: center; align-items: center; gap: 8px;">
        <i class="fas fa-shield-virus" style="color: var(--primary);"></i>
        Your description is processed securely by our AI model for preliminary triage.
    </p>
</div>

<style>
    textarea:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1) !important;
        background: white !important;
        outline: none;
    }
    #analyzeBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(67, 97, 238, 0.3);
    }
</style>

<script>
    document.getElementById('symptomForm').addEventListener('submit', function() {
        const btn = document.getElementById('analyzeBtn');
        btn.innerHTML = '<i class="fas fa-microchip fa-spin" style="margin-right: 8px;"></i> Analyzing Patterns...';
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';
    });
</script>
@endsection
