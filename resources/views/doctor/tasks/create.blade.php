@extends('layouts.dashboard')

@section('title', 'Assign New Task')

@section('content')
<div style="animation: fadeIn 0.6s ease-out; max-width: 860px; margin: 0 auto;">

    {{-- Header --}}
    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 32px;">
        <a href="{{ route('doctor.tasks.index') }}"
           style="width: 40px; height: 40px; border-radius: 12px; background: white; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: #64748b; text-decoration: none; transition: all 0.2s; flex-shrink: 0;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 850; color: #1e293b; margin-bottom: 4px; letter-spacing: -0.02em;">Assign New Task</h1>
            <p style="color: #64748b; font-size: 0.9rem; font-weight: 500;">Create a task and assign it to a nurse with a defined priority level.</p>
        </div>
    </div>

    @if($errors->any())
    <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 14px; padding: 16px 20px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
            <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
            <strong style="color: #dc2626; font-size: 0.9rem;">Please fix the following errors:</strong>
        </div>
        <ul style="margin: 0; padding-left: 20px; color: #dc2626; font-size: 0.88rem;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('doctor.tasks.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 320px; gap: 24px; align-items: start;">

            {{-- Left Column --}}
            <div style="display: flex; flex-direction: column; gap: 20px;">

                {{-- Title --}}
                <div class="glass-card" style="padding: 24px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
                        Task Title <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        placeholder="e.g., Administer medication — Ward 3, Bed 7"
                        style="width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.95rem; font-weight: 500; color: #1e293b; box-sizing: border-box; transition: border 0.2s; outline: none;">

                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; margin-top: 20px;">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                        placeholder="Detailed instructions for the nurse..."
                        style="width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; font-family: inherit; color: #1e293b; resize: vertical; box-sizing: border-box; transition: border 0.2s; outline: none;">{{ old('description') }}</textarea>

                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; margin-top: 20px;">
                        Internal Notes (optional)
                    </label>
                    <textarea name="notes" id="notes" rows="2"
                        placeholder="Any clinical context or special considerations..."
                        style="width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; font-family: inherit; color: #1e293b; resize: vertical; box-sizing: border-box; transition: border 0.2s; outline: none;">{{ old('notes') }}</textarea>
                </div>

                {{-- Priority --}}
                <div class="glass-card" style="padding: 24px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 16px;">
                        Priority Level <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                        @php
                        $priorities = [
                            'High'   => ['icon' => 'fa-arrow-up',    'bg' => '#fef2f2', 'border' => '#fecaca', 'text' => '#ef4444', 'active_bg' => '#fee2e2', 'desc' => 'Urgent — immediate action'],
                            'Medium' => ['icon' => 'fa-equals',      'bg' => '#fffbeb', 'border' => '#fde68a', 'text' => '#f59e0b', 'active_bg' => '#fef3c7', 'desc' => 'Standard care task'],
                            'Low'    => ['icon' => 'fa-arrow-down',  'bg' => '#eff6ff', 'border' => '#bfdbfe', 'text' => '#3b82f6', 'active_bg' => '#dbeafe', 'desc' => 'Routine / administrative'],
                        ];
                        $selectedPriority = old('priority', 'Medium');
                        @endphp
                        @foreach($priorities as $key => $p)
                        <label for="priority_{{ $key }}" class="priority-card" data-priority="{{ $key }}"
                            style="cursor: pointer; border: 2px solid {{ $selectedPriority === $key ? $p['border'] : '#f1f5f9' }}; border-radius: 14px; padding: 16px; background: {{ $selectedPriority === $key ? $p['active_bg'] : 'white' }}; transition: all 0.2s; text-align: center; display: block;">
                            <input type="radio" name="priority" id="priority_{{ $key }}" value="{{ $key }}" 
                                {{ $selectedPriority === $key ? 'checked' : '' }} style="display: none;">
                            <div style="width: 40px; height: 40px; border-radius: 12px; background: {{ $p['bg'] }}; color: {{ $p['text'] }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 1rem;">
                                <i class="fas {{ $p['icon'] }}"></i>
                            </div>
                            <div style="font-weight: 800; font-size: 0.9rem; color: {{ $p['text'] }}; margin-bottom: 4px;">{{ $key }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">{{ $p['desc'] }}</div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Category + Due Date --}}
                <div class="glass-card" style="padding: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label for="category" style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
                            Category <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="category" id="category"
                            style="width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; font-weight: 600; color: #475569; background: white; cursor: pointer; outline: none; transition: border 0.2s; box-sizing: border-box;">
                            <option value="Clinical"        {{ old('category') == 'Clinical'        ? 'selected' : '' }}>🏥 Clinical</option>
                            <option value="General"         {{ old('category', 'General') == 'General'  ? 'selected' : '' }}>📋 General</option>
                            <option value="Administrative"  {{ old('category') == 'Administrative'  ? 'selected' : '' }}>📁 Administrative</option>
                        </select>
                    </div>
                    <div>
                        <label for="due_at" style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
                            Due Date & Time <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="datetime-local" name="due_at" id="due_at" value="{{ old('due_at') }}"
                            min="{{ now()->format('Y-m-d\TH:i') }}"
                            style="width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; font-weight: 500; color: #475569; background: white; cursor: pointer; outline: none; transition: border 0.2s; box-sizing: border-box;">
                    </div>
                </div>

            </div>

            {{-- Right Column --}}
            <div style="display: flex; flex-direction: column; gap: 20px;">

                {{-- Nurse Assignment --}}
                <div class="glass-card" style="padding: 24px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">
                        <i class="fas fa-user-nurse" style="margin-right: 6px; color: #0D9488;"></i>Assign to Nurse <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="display: flex; flex-direction: column; gap: 8px; max-height: 260px; overflow-y: auto; padding-right: 4px;">
                        @forelse($nurses as $nurse)
                        <label for="nurse_{{ $nurse->id }}" class="nurse-option"
                            style="display: flex; align-items: center; gap: 12px; padding: 12px; border: 1.5px solid #f1f5f9; border-radius: 12px; cursor: pointer; transition: all 0.2s; {{ old('nurse_id') == $nurse->id ? 'border-color: #0D9488; background: #f0fdfa;' : '' }}">
                            <input type="radio" name="nurse_id" id="nurse_{{ $nurse->id }}" value="{{ $nurse->id }}"
                                {{ old('nurse_id') == $nurse->id ? 'checked' : '' }} style="display: none;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($nurse->name) }}&background=0D9488&color=fff"
                                 style="width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;" alt="">
                            <div>
                                <div style="font-weight: 700; font-size: 0.9rem; color: #1e293b;">{{ $nurse->name }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">{{ $nurse->department ?? 'General Ward' }}</div>
                            </div>
                        </label>
                        @empty
                        <p style="color: #94a3b8; text-align: center; padding: 20px 0; font-size: 0.9rem;">No nurses registered yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Patient (Optional) --}}
                <div class="glass-card" style="padding: 24px;">
                    <label for="patient_id" style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
                        <i class="fas fa-user-injured" style="margin-right: 6px; color: #64748b;"></i>Related Patient <span style="color: #94a3b8; font-weight: 500;">(optional)</span>
                    </label>
                    <select name="patient_id" id="patient_id"
                        style="width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; font-weight: 500; color: #475569; background: white; cursor: pointer; outline: none; transition: border 0.2s; box-sizing: border-box;">
                        <option value="">— No specific patient —</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->user->name ?? 'Patient #'.$patient->id }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit --}}
                <button type="submit" id="submitBtn"
                    style="width: 100%; padding: 14px; background: linear-gradient(135deg, #0D9488, #0f766e); color: white; border: none; border-radius: 14px; font-weight: 800; font-size: 1rem; cursor: pointer; letter-spacing: 0.02em; box-shadow: 0 8px 20px -4px rgba(13,148,136,0.4); transition: all 0.2s;">
                    <i class="fas fa-paper-plane" style="margin-right: 8px;"></i>Assign Task to Nurse
                </button>
                <a href="{{ route('doctor.tasks.index') }}" 
                    style="display: block; text-align: center; padding: 12px; color: #64748b; font-weight: 600; text-decoration: none; border: 1.5px solid #e2e8f0; border-radius: 14px; transition: all 0.2s; font-size: 0.95rem;">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    input:focus, select:focus, textarea:focus {
        border-color: #0D9488 !important;
        box-shadow: 0 0 0 3px rgba(13,148,136,0.1);
    }
    .priority-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px -2px rgba(0,0,0,0.08); }
    .nurse-option:hover { border-color: #0D9488 !important; background: #f0fdfa !important; }
    #submitBtn:hover { transform: translateY(-1px); box-shadow: 0 12px 24px -4px rgba(13,148,136,0.5); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Priority card selection
    document.querySelectorAll('.priority-card').forEach(card => {
        const pColors = {
            High:   { border: '#fecaca', bg: '#fee2e2' },
            Medium: { border: '#fde68a', bg: '#fef3c7' },
            Low:    { border: '#bfdbfe', bg: '#dbeafe' },
        };
        card.addEventListener('click', function () {
            document.querySelectorAll('.priority-card').forEach(c => {
                c.style.borderColor = '#f1f5f9';
                c.style.background  = 'white';
                c.style.transform   = '';
            });
            const p = this.dataset.priority;
            this.style.borderColor = pColors[p].border;
            this.style.background  = pColors[p].bg;
            this.querySelector('input[type=radio]').checked = true;
        });
    });

    // Nurse card selection
    document.querySelectorAll('.nurse-option').forEach(label => {
        label.addEventListener('click', function () {
            document.querySelectorAll('.nurse-option').forEach(l => {
                l.style.borderColor = '#f1f5f9';
                l.style.background  = 'white';
            });
            this.style.borderColor = '#0D9488';
            this.style.background  = '#f0fdfa';
        });
    });
});
</script>
@endsection
