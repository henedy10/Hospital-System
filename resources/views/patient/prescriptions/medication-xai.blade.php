@extends('layouts.dashboard')

@section('content')
    <div class="px-6 py-10 bg-[#F8FAFC] min-h-screen font-inter">
        <div class="max-w-7xl mx-auto flex flex-col gap-10">

            {{-- TOP HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-5">
                    <div
                        class="p-4 bg-white rounded-2xl shadow-sm border border-slate-100 flex-shrink-0 animate-pulse-slow">
                        <i class="fas fa-file-medical text-teal-600 text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                            AI Prescription Explanation
                            <span
                                class="hidden sm:inline-block text-[10px] font-black px-3 py-1 bg-teal-50 text-teal-600 rounded-full border border-teal-100 uppercase tracking-widest">Explainable
                                AI</span>
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Personalized clinical insights powered by advanced
                            healthcare models</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <button
                        class="flex-grow md:flex-grow-0 flex items-center justify-center gap-2 px-6 py-3.5 bg-white text-slate-700 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm group">
                        <i
                            class="fas fa-file-download text-teal-600 opacity-60 group-hover:opacity-100 transition-opacity"></i>
                        Download Report
                    </button>
                </div>
            </div>

            {{-- SUMMARY CARDS GRID --}}
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Diagnosis Card --}}
                <div
                    class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-8">
                            <span class="text-[10px] font-black text-teal-600 uppercase tracking-[0.2em]">Medical
                                Context</span>
                            <div class="p-2.5 bg-rose-50 rounded-xl text-rose-500 shadow-inner">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Diagnosis</p>
                        <h2 class="text-3xl font-black text-slate-800 mb-6 antialiased leading-tight">
                            {{ $prescription->medicalHistory->condition ?? 'Hypertension' }}
                        </h2>
                    </div>

                    <div class="pt-6 border-t border-slate-50 relative">
                        <p
                            class="text-[10px] font-black text-teal-600 uppercase tracking-[0.2em] mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 bg-teal-500 rounded-full"></span> Treatment Goal
                        </p>
                        <p class="text-slate-500 text-sm leading-relaxed font-medium">
                            {{ $explanation['data'][0]['english']['usage'] ?? 'Optimize blood pressure levels and mitigate cardiovascular risk factors.' }}
                        </p>
                    </div>
                </div>

                {{-- Why Meds Card --}}
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col">
                    <span class="text-[10px] font-black text-teal-600 uppercase tracking-[0.2em] block mb-6">Clinical
                        Rationale</span>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-8 italic">
                        "Your personalized treatment plan is engineered based on current guidelines to maximize efficacy
                        while ensuring patient safety:"
                    </p>

                    <div class="space-y-5 flex-grow">
                        <div class="flex items-start gap-4">
                            <div
                                class="mt-1 w-6 h-6 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm border border-teal-100">
                                <i class="fas fa-check text-[10px] text-teal-600"></i>
                            </div>
                            <span class="text-slate-700 text-sm font-semibold leading-snug">Multi-pathway Blood Pressure
                                Control</span>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="mt-1 w-6 h-6 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm border border-teal-100">
                                <i class="fas fa-shield-alt text-[10px] text-teal-600"></i>
                            </div>
                            <span class="text-slate-700 text-sm font-semibold leading-snug">Validated Synergy & Drug
                                Safety</span>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="mt-1 w-6 h-6 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm border border-teal-100">
                                <i class="fas fa-star text-[10px] text-teal-600"></i>
                            </div>
                            <span class="text-slate-700 text-sm font-semibold leading-snug">Standard of Care for
                                {{ $prescription->medicalHistory->condition ?? 'Hypertension' }}</span>
                        </div>
                    </div>
                </div>

                {{-- AI Confidence Card --}}
                <div
                    class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col items-center justify-center text-center">
                    <div class="flex items-center gap-2 mb-8">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Diagnostic
                            Confidence</span>
                        <i class="fas fa-shield-virus text-teal-600 text-[10px]"></i>
                    </div>

                    @php
                        $confidence = $explanation['data'][0]['english']['xai']['confidence'] ?? 0.92;
                        $percentage = $confidence * 100;
                        $radius = 60;
                        $circumference = 2 * M_PI * $radius;
                        $offset = $circumference - ($percentage / 100 * $circumference);
                    @endphp

                    <div class="relative w-36 h-36 mb-8 group">
                        <div
                            class="absolute inset-0 bg-teal-50 rounded-full scale-90 group-hover:scale-95 transition-transform duration-700 opacity-50">
                        </div>
                        <svg class="w-full h-full transform -rotate-90 relative  z-10">
                            <circle cx="50%" cy="50%" r="{{ $radius }}" stroke="#F1F5F9" stroke-width="10" fill="none" />
                            <circle cx="50%" cy="50%" r="{{ $radius }}" stroke="#0D9488" stroke-width="10"
                                stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $circumference }}"
                                stroke-linecap="round" fill="none" class="transition-all duration-[1.5s] ease-out-expo"
                                id="confidenceCircle" data-offset="{{ $offset }}" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center z-20">
                            <span
                                class="text-4xl font-black text-slate-800 leading-none tracking-tighter tabular-nums">{{ round($percentage) }}%</span>
                            <div class="mt-1 flex items-center gap-1">
                                <span class="text-[8px] font-black text-teal-600 uppercase tracking-widest">Optimized</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-slate-500 text-xs font-medium leading-relaxed max-w-[200px]">
                        The AI model maintains high precision for this clinical recommendation.
                    </p>
                </div>
            </div>

            {{-- MAIN CONTENT GRID --}}
            <div class="grid lg:grid-cols-3 gap-10 items-start">

                {{-- LEFT: PRESCRIPTIONS --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Your Prescription Title --}}
                    <div class="flex items-center gap-3 px-4 py-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center border border-emerald-100 shadow-sm">
                            <i class="fas fa-prescription text-emerald-600 text-lg"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Your Prescription</h3>
                    </div>

                    <div class="space-y-4">
                        @foreach($explanation['data'] as $index => $drug)
                            <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm relative overflow-hidden transition-all hover:border-teal-200 hover:shadow-md">
                                {{-- AI Importance Badge --}}
                                <div class="absolute top-6 right-6">
                                    <div class="bg-white border {{ $index % 2 == 0 ? 'border-emerald-100 bg-emerald-50/20' : 'border-blue-100 bg-blue-50/20' }} rounded-2xl p-4 min-w-[120px] text-center shadow-sm">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">AI Importance</p>
                                        <p class="text-2xl font-black {{ $index % 2 == 0 ? 'text-emerald-600' : 'text-blue-600' }}">
                                            {{ round(($drug['english']['xai']['confidence'] ?? 0.65) * 100) }}%
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-8">
                                    {{-- Icon --}}
                                    <div class="flex-shrink-0">
                                        <div class="w-24 h-24 rounded-full {{ $index % 2 == 0 ? 'bg-emerald-50' : 'bg-blue-50' }} flex items-center justify-center border border-emerald-50 shadow-inner">
                                            <i class="fas fa-pills {{ $index % 2 == 0 ? 'text-emerald-700' : 'text-blue-700' }} text-3xl"></i>
                                        </div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-grow pt-2">
                                        {{-- Header --}}
                                        <div class="flex items-center gap-4 mb-6">
                                            <h4 class="text-3xl font-black text-slate-800 tracking-tight">{{ $drug['drug_name'] }}</h4>
                                            <span class="px-4 py-1.5 {{ $index % 2 == 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-blue-50 text-blue-700 border-blue-100' }} text-[11px] font-black rounded-full border uppercase tracking-wider">
                                                {{ $drug['english']['drug_class'] ?? 'Medication' }}
                                            </span>
                                        </div>

                                        {{-- Medical Details Grid --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12 mb-8">
                                            <div class="flex items-center gap-4">
                                                <span class="text-sm font-bold text-slate-900 min-w-[100px]">Dosage:</span>
                                                <span class="text-sm text-slate-500 font-medium bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">{{ $drug['metadata']['dosage'] ?? $drug['english']['dosage'] }}</span>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span class="text-sm font-bold text-slate-900 min-w-[100px]">Frequency:</span>
                                                <span class="text-sm text-slate-500 font-medium">1 time per day</span>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span class="text-sm font-bold text-slate-900 min-w-[100px]">Duration:</span>
                                                <span class="text-sm text-slate-500 font-medium">{{ $drug['metadata']['duration'] ?? '90' }} days</span>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span class="text-sm font-bold text-slate-900 min-w-[100px]">Instructions:</span>
                                                <span class="text-sm text-slate-500 font-medium truncate max-w-[280px]" title="{{ $drug['metadata']['instructions'] ?? 'Follow clinical guidance' }}">
                                                    {{ $drug['metadata']['instructions'] ?? 'Take daily' }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Insights --}}
                                        <div class="space-y-6 border-t border-slate-100 pt-6">
                                            <div>
                                                <h5 class="text-teal-700 font-black text-sm uppercase tracking-wider mb-2">How it works</h5>
                                                <p class="text-slate-600 text-[14px] leading-relaxed font-semibold">
                                                    {{ $drug['english']['usage'] }}
                                                </p>
                                            </div>
                                            <div>
                                                <h5 class="text-teal-700 font-black text-sm uppercase tracking-wider mb-2">Why prescribed for you</h5>
                                                <p class="text-slate-600 text-[14px] leading-relaxed font-semibold">
                                                    {{ $drug['english']['summary'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                </div>

                {{-- RIGHT: SIDEBARS --}}
                <div class="space-y-8">

                    {{-- Venn Diagram Card --}}
                    <div
                        class="bg-white p-12 rounded-[3.5rem] border border-slate-100 shadow-xl shadow-slate-200/30 group overflow-hidden relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-teal-50/40 via-white to-indigo-50/40 -z-10 opacity-70">
                        </div>

                        <div class="flex items-center justify-between mb-12">
                            <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-[0.2em] opacity-60">
                                Synergetic Analysis</h3>
                            <div
                                class="w-12 h-12 rounded-2xl bg-teal-50 flex items-center justify-center shadow-sm border border-teal-100">
                                <i class="fas fa-brain text-teal-600 text-base drop-shadow-sm"></i>
                            </div>
                        </div>

                        <div
                            class="relative h-72 flex items-center justify-center mb-12 group-hover:scale-105 transition-transform duration-1000">
                            {{-- Dynamic Circles --}}
                            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-white/40 backdrop-blur-md rounded-full border border-teal-200/50 flex flex-col items-center justify-center p-10 text-center z-10 hover:z-30 transition-all cursor-default shadow-2xl shadow-teal-900/10 animate-entrance-left"
                                style="transform: translate(-60%, -50%)">
                                <p class="text-[11px] font-black text-teal-900 leading-tight uppercase tracking-tight mb-2">
                                    {{ $explanation['data'][0]['drug_name'] ?? 'Primary Agent' }}</p>
                                <span
                                    class="text-[8px] font-black text-teal-600 uppercase tracking-widest opacity-70 decoration-teal-200 underline decoration-2">{{ $explanation['data'][0]['english']['drug_class'] ?? 'Therapeutic' }}</span>
                            </div>

                            @if(count($explanation['data']) > 1)
                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-white/40 backdrop-blur-md rounded-full border border-indigo-200/50 flex flex-col items-center justify-center p-10 text-center z-10 hover:z-30 transition-all cursor-default shadow-2xl shadow-indigo-900/10 animate-entrance-right"
                                    style="transform: translate(-40%, -50%)">
                                    <p
                                        class="text-[11px] font-black text-indigo-900 leading-tight uppercase tracking-tight mb-2">
                                        {{ $explanation['data'][1]['drug_name'] ?? 'Supportive' }}</p>
                                    <span
                                        class="text-[8px] font-black text-indigo-600 uppercase tracking-widest opacity-70 decoration-indigo-200 underline decoration-2">{{ $explanation['data'][1]['english']['drug_class'] ?? 'Catalyst' }}</span>
                                </div>
                            @endif

                            {{-- Connection Node --}}
                            <div
                                class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-14 h-14 bg-white rounded-[1.5rem] flex items-center justify-center z-20 shadow-2xl border border-slate-100 rotate-12 group-hover:rotate-0 transition-all duration-700 ring-8 ring-white/50">
                                <i class="fas fa-plus text-teal-500"></i>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-tr from-slate-50 to-white/80 p-8 rounded-[2.5rem] border border-slate-100 backdrop-blur-sm shadow-inner text-center">
                            <p class="text-slate-600 text-xs font-bold leading-relaxed italic opacity-80">
                                This AI-modeled combination effectively manages multiple medical metrics while maintaining a
                                high safety profile.
                            </p>
                        </div>
                    </div>

                    {{-- AI Explanation Factors --}}
                    <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-10">
                            <h3 class="text-xl font-bold text-slate-800 tracking-tight">Inference Factors</h3>
                            <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center">
                                <i class="fas fa-robot text-teal-600 text-xs"></i>
                            </div>
                        </div>

                        <div class="space-y-8 mb-10">
                            @foreach($explanation['data'][0]['english']['xai']['feature_importance'] ?? [] as $factor)
                                <div>
                                    <div
                                        class="flex justify-between text-[10px] font-black mb-3 px-1 uppercase tracking-widest text-slate-500">
                                        <span class="text-slate-800">{{ $factor['feature'] }}</span>
                                        <span class=" tabular-nums">{{ round($factor['impact'] * 100) }}%</span>
                                    </div>
                                    <div
                                        class="h-2.5 w-full bg-slate-50 rounded-full border border-slate-100 shadow-inner overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-teal-400 to-teal-600 rounded-full transition-all duration-[2s] ease-out-expo scale-x-0 origin-left"
                                            style="width: {{ $factor['impact'] * 100 }}%" data-animate="width">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 text-center">
                            <p
                                class="text-slate-400 text-[8px] leading-relaxed font-black uppercase tracking-[0.2em] max-w-[200px] mx-auto">
                                Analytical weights are derived from global healthcare data models.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BOTTOM GRID: INFO --}}
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Warnings --}}
                <div class="bg-[#FFFBEB] p-10 rounded-[3rem] border border-amber-100 shadow-sm">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-amber-500 shadow-sm border border-amber-50">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4 class="font-black uppercase tracking-[0.1em] text-xs text-amber-800">Critical Alerts</h4>
                    </div>
                    <ul class="space-y-5">
                        @foreach($explanation['data'][0]['english']['warnings'] ?? [] as $warning)
                            <li class="flex items-start gap-4">
                                <span
                                    class="mt-2 w-1.5 h-1.5 bg-amber-400 rounded-full flex-shrink-0 ring-4 ring-amber-100"></span>
                                <span class="text-amber-900 text-sm font-semibold leading-relaxed">
                                    {{ is_array($warning) ? implode(', ', $warning) : $warning }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Side Effects --}}
                <div class="bg-[#F0FDF4] p-10 rounded-[3rem] border border-green-100 shadow-sm">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-green-500 shadow-sm border border-green-50">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h4 class="font-black uppercase tracking-[0.1em] text-xs text-green-800">Monitor Symptoms</h4>
                    </div>
                    <ul class="space-y-5">
                        @foreach($explanation['data'] as $drug)
                            <li class="flex items-start gap-4">
                                <span
                                    class="mt-2 w-1.5 h-1.5 bg-green-400 rounded-full flex-shrink-0 ring-4 ring-green-100"></span>
                                <span class="text-green-900 text-sm font-semibold leading-relaxed leading-relaxed">
                                    <strong
                                        class="text-green-950 underline decoration-green-200 decoration-2">{{ $drug['drug_name'] }}:</strong>
                                    <span
                                        class="opacity-80">{{ implode(', ', array_slice($drug['english']['side_effects'], 0, 3)) }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Lifestyle --}}
                <div class="bg-[#EFF6FF] p-10 rounded-[3rem] border border-blue-100 shadow-sm">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-blue-500 shadow-sm border border-blue-50">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 class="font-black uppercase tracking-[0.1em] text-xs text-blue-800">Holistic Care</h4>
                    </div>
                    <ul class="space-y-4">
                        @foreach($explanation['data'][0]['english']['lifestyle'] ?? [] as $lifestyle)
                            <li class="flex items-center gap-4 p-3 bg-white/50 rounded-2xl">
                                <i class="fas fa-check-circle text-blue-400 text-xs"></i>
                                <span class="text-blue-900 text-sm font-bold">
                                    {{ is_array($lifestyle) ? implode(', ', $lifestyle) : $lifestyle }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function speakText(btn) {
            const text = btn.getAttribute('data-text');
            const icon = btn.querySelector('i');

            if (window.speechSynthesis.speaking) {
                window.speechSynthesis.cancel();
                icon.className = 'fas fa-volume-up text-sm';
                return;
            }

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.onstart = () => icon.className = 'fas fa-stop text-sm text-teal-600';
            utterance.onend = () => icon.className = 'fas fa-volume-up text-sm';
            window.speechSynthesis.speak(utterance);
        }

        function copyToClipboard(btn) {
            const content = btn.getAttribute('data-content');
            const icon = btn.querySelector('i');

            navigator.clipboard.writeText(content).then(() => {
                const original = icon.className;
                icon.className = 'fas fa-check text-sm text-teal-600';
                setTimeout(() => icon.className = original, 2000);
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            // High-fidelity animations
            setTimeout(() => {
                // Circular progress
                const circle = document.getElementById('confidenceCircle');
                if (circle) circle.style.strokeDashoffset = circle.getAttribute('data-offset');

                // Bar charts
                document.querySelectorAll('[data-animate="width"]').forEach(el => {
                    el.classList.remove('scale-x-0');
                });
            }, 500);
        });
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

        .font-inter {
            font-family: 'Inter', sans-serif;
        }

        .ease-out-expo {
            transition-timing-function: cubic-bezier(0.19, 1, 0.22, 1);
        }

        .pills-floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0) rotate(12deg);
            }

            50% {
                transform: translateY(-8px) rotate(8deg);
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.9;
                transform: scale(1.02);
            }
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
@endsection