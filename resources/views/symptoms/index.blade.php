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
            Describe how you're feeling, and our advanced AI will provide a preliminary analysis and guide you to the right specialist.
        </p>
    </div>
    <a href="{{ route('symptoms.history') }}" class="btn-secondary" style="width: auto; padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-history"></i> View History
    </a>
</div>

<div class="glass-card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('symptoms.analyze') }}" method="POST" id="symptomForm" style="display: flex; flex-direction: column; gap: 24px;">
        @csrf
        
        <div>
            <label for="symptoms" style="display: block; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">
                What are your symptoms?
            </label>
            <div style="position: relative;">
                <textarea id="symptoms" name="symptoms" rows="6" 
                    class="form-control @error('symptoms') error-border @enderror" 
                    style="width: 100%; resize: vertical; padding: 16px; border-radius: 12px; background: #fff; font-family: inherit; font-size: 0.95rem; line-height: 1.5;"
                    placeholder="e.g., severe headache, mild fever, and dry cough for the past 3 days..."
                    required>{{ old('symptoms') }}</textarea>
                
                <div style="position: absolute; bottom: 16px; right: 16px; color: var(--text-muted);">
                    <i class="fas fa-keyboard"></i>
                </div>
            </div>
            
            @error('symptoms')
                <p style="color: var(--danger); font-size: 0.85rem; margin-top: 8px; font-weight: 500;">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </p>
            @enderror
            
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 12px; display: flex; align-items: flex-start; gap: 8px;">
                <i class="fas fa-info-circle" style="margin-top: 2px;"></i>
                <span>Please be as descriptive as possible. Include duration, severity, and any other relevant details.</span>
            </p>
        </div>

        <div style="display: flex; justify-content: flex-end; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 24px;">
            <button type="submit" id="analyzeBtn" class="btn-primary" style="padding: 14px 28px; font-size: 1rem;">
                <i class="fas fa-cogs"></i> Analyze Symptoms
            </button>
        </div>
    </form>
</div>

<div style="text-align: center; margin-top: 24px;">
    <p style="color: var(--text-muted); font-size: 0.85rem; display: flex; justify-content: center; align-items: center; gap: 8px;">
        <i class="fas fa-shield-alt" style="color: var(--primary);"></i>
        For informational purposes only. Do not use for medical emergencies.
    </p>
</div>

<script>
    document.getElementById('symptomForm').addEventListener('submit', function() {
        const btn = document.getElementById('analyzeBtn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Analyzing...';
        btn.style.opacity = '0.75';
        btn.style.cursor = 'not-allowed';
    });
</script>

<style>
    .error-border {
        border-color: var(--danger) !important;
    }
</style>
@endsection
