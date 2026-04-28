@extends('layouts.dashboard')
@section('title', 'شرح الوصفة الطبية')

@section('content')
{{-- Full RTL Arabic layout for this page --}}
<div dir="rtl" style="font-family: 'Cairo', 'Segoe UI', sans-serif; max-width: 860px; margin: 0 auto; padding-bottom: 50px;">

    {{-- Google Font: Cairo (Arabic-optimized) --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- Back + TTS Controls --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:12px;">
        <a href="{{ route('patient.prescriptions.index') }}"
           style="color:#64748b; text-decoration:none; display:flex; align-items:center; gap:8px; font-size:.9rem; font-weight:600; background:#f1f5f9; padding:9px 16px; border-radius:10px;"
           onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
            <i class="fas fa-arrow-right"></i> العودة للوصفات
        </a>
        <div style="display:flex; gap:10px; align-items:center;">
            <button id="ttsBtn" onclick="speakExplanation()"
                    style="background:linear-gradient(135deg,#7c3aed,#4f46e5); color:#fff; border:none; cursor:pointer; padding:9px 20px; border-radius:10px; font-size:.88rem; font-weight:700; display:flex; align-items:center; gap:8px; box-shadow:0 4px 14px rgba(124,58,237,.3);">
                <i class="fas fa-volume-up" id="ttsIcon"></i> استمع للشرح
            </button>
            <button onclick="stopSpeaking()"
                    style="background:#f1f5f9; color:#64748b; border:none; cursor:pointer; padding:9px 16px; border-radius:10px; font-size:.88rem; font-weight:600; display:flex; align-items:center; gap:7px;">
                <i class="fas fa-stop"></i> إيقاف
            </button>
        </div>
    </div>

    {{-- Hero Summary Card --}}
    <div style="background:linear-gradient(135deg,#0d9488 0%,#0891b2 50%,#0f172a 100%); border-radius:20px; padding:32px; margin-bottom:28px; color:#fff; position:relative; overflow:hidden;">
        <div style="position:absolute; top:-30px; left:-30px; width:140px; height:140px; background:rgba(255,255,255,.05); border-radius:50%;"></div>
        <div style="position:absolute; bottom:-40px; right:-20px; width:180px; height:180px; background:rgba(255,255,255,.04); border-radius:50%;"></div>
        <div style="position:relative;">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:18px; flex-wrap:wrap;">
                <div style="width:52px; height:52px; background:rgba(255,255,255,.2); border-radius:14px; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-robot" style="font-size:1.5rem;"></i>
                </div>
                <div>
                    <div style="font-size:1.4rem; font-weight:800;">شرح الوصفة الطبية</div>
                    <div style="opacity:.8; font-size:.88rem; margin-top:2px;">
                        وصفة #{{ $prescription->id }} &nbsp;·&nbsp; د. {{ $prescription->doctor->user->name ?? 'طبيبك' }}
                        &nbsp;·&nbsp; {{ $prescription->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>
            <div id="summaryText" style="font-size:.98rem; line-height:1.9; opacity:.95; white-space:pre-line;">{{ $explanation['summary'] }}</div>
        </div>
    </div>

    {{-- Medicine Count Badge --}}
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
        <div style="height:2px; flex:1; background:linear-gradient(90deg,#0d9488,transparent);"></div>
        <span style="background:#0d9488; color:#fff; padding:6px 18px; border-radius:20px; font-size:.88rem; font-weight:700;">
            <i class="fas fa-pills" style="margin-left:6px;"></i>
            {{ count($explanation['items']) }} {{ count($explanation['items']) === 1 ? 'دواء' : 'أدوية' }}
        </span>
        <div style="height:2px; flex:1; background:linear-gradient(270deg,#0d9488,transparent);"></div>
    </div>

    {{-- Per-Medicine Cards --}}
    <div id="medicineCards" style="display:grid; gap:20px;">
        @foreach($explanation['items'] as $index => $item)
        <div class="medicine-card" style="background:#fff; border-radius:18px; box-shadow:0 2px 12px rgba(0,0,0,.08); overflow:hidden; animation: slideUp .4s ease {{ $index * 0.1 }}s both;">

            {{-- Card Header --}}
            <div style="background:linear-gradient(135deg,#0f172a,#1e293b); padding:18px 24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:42px; height:42px; background:linear-gradient(135deg,#0d9488,#0891b2); border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:1rem;">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <div style="color:#fff; font-weight:800; font-size:1.05rem;">{{ $item['medicine'] }}</div>
                        @if(!empty($item['category']))
                            <div style="color:#94d2bd; font-size:.78rem; font-weight:600; margin-top:2px;">{{ $item['category'] }}</div>
                        @endif
                    </div>
                </div>
                <div style="display:flex; gap:8px; align-items:center;">
                    @if($item['is_unknown'] ?? false)
                        <span style="background:#7c3aed; color:#fff; padding:4px 12px; border-radius:20px; font-size:.76rem; font-weight:700;">
                            <i class="fas fa-question-circle" style="margin-left:4px;"></i> غير معروف
                        </span>
                    @endif
                    <span style="background:rgba(255,255,255,.15); color:#94d2bd; padding:5px 14px; border-radius:20px; font-size:.84rem; font-weight:700;">
                        {{ $item['dosage'] ?? '' }}
                    </span>
                </div>
            </div>

            <div style="padding:22px 24px;">
                {{-- Purpose --}}
                <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-radius:12px; padding:16px 18px; margin-bottom:16px; border-right:4px solid #16a34a;">
                    <div style="font-size:.78rem; font-weight:700; color:#15803d; text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px;">
                        <i class="fas fa-info-circle" style="margin-left:5px;"></i> الغرض من الدواء
                    </div>
                    <div style="color:#166534; font-size:.95rem; line-height:1.7; font-weight:600;">
                        {{ $item['purpose'] ?? '' }}
                    </div>
                </div>

                {{-- Dosage / Frequency / Duration grid --}}
                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:16px;">
                    <div style="background:#eff6ff; border-radius:12px; padding:14px; text-align:center;">
                        <div style="color:#1d4ed8; font-size:1.3rem; margin-bottom:6px;"><i class="fas fa-pills"></i></div>
                        <div style="font-size:.72rem; color:#3b82f6; font-weight:700; text-transform:uppercase; letter-spacing:.04em; margin-bottom:3px;">الجرعة</div>
                        <div style="font-weight:800; color:#1e40af; font-size:.92rem;">{{ $item['dosage'] ?? '—' }}</div>
                    </div>
                    <div style="background:#fef3c7; border-radius:12px; padding:14px; text-align:center;">
                        <div style="color:#d97706; font-size:1.3rem; margin-bottom:6px;"><i class="fas fa-clock"></i></div>
                        <div style="font-size:.72rem; color:#d97706; font-weight:700; text-transform:uppercase; letter-spacing:.04em; margin-bottom:3px;">التكرار</div>
                        <div style="font-weight:800; color:#92400e; font-size:.88rem;">{{ ($item['frequency'] ?? '') }}x يومياً</div>
                    </div>
                    <div style="background:#f3e8ff; border-radius:12px; padding:14px; text-align:center;">
                        <div style="color:#7c3aed; font-size:1.3rem; margin-bottom:6px;"><i class="fas fa-calendar-alt"></i></div>
                        <div style="font-size:.72rem; color:#7c3aed; font-weight:700; text-transform:uppercase; letter-spacing:.04em; margin-bottom:3px;">المدة</div>
                        <div style="font-weight:800; color:#4c1d95; font-size:.88rem;">{{ ($item['duration'] ?? '') }} يوم</div>
                    </div>
                </div>

                {{-- Instructions --}}
                @if(!empty($item['instructions']))
                <div style="background:#fafafa; border-radius:10px; padding:12px 16px; margin-bottom:14px; display:flex; gap:10px; align-items:flex-start; border:1px solid #e5e7eb;">
                    <i class="fas fa-bookmark" style="color:#0d9488; margin-top:3px; flex-shrink:0;"></i>
                    <div style="color:#374151; font-size:.9rem; line-height:1.6;">
                        <strong style="color:#0f172a;">تعليمات التناول:</strong> {{ $item['instructions'] }}
                    </div>
                </div>
                @endif

                {{-- Explanation text --}}
                <div style="background:#f8fafc; border-radius:10px; padding:14px 18px; font-size:.9rem; color:#475569; line-height:1.85; white-space:pre-line; border-right:3px solid #0d9488;">
                    {{ $item['explanation'] }}
                </div>

                {{-- Warnings --}}
                @if(!empty($item['warnings']))
                    <div style="margin-top:14px; display:grid; gap:8px;">
                        @foreach($item['warnings'] as $warning)
                        <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:10px; padding:12px 16px; display:flex; gap:10px; align-items:flex-start;">
                            <i class="fas fa-exclamation-triangle" style="color:#e11d48; flex-shrink:0; margin-top:2px;"></i>
                            <div style="color:#9f1239; font-size:.88rem; font-weight:600; line-height:1.6;">{{ $warning }}</div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Footer note --}}
    <div style="margin-top:30px; background:#f0fdf4; border-radius:14px; padding:18px 22px; display:flex; gap:12px; align-items:flex-start; border:1px solid #bbf7d0;">
        <i class="fas fa-shield-alt" style="color:#16a34a; font-size:1.2rem; margin-top:2px; flex-shrink:0;"></i>
        <div style="color:#166534; font-size:.9rem; line-height:1.7;">
            <strong>تذكير مهم:</strong> هذا الشرح مُولَّد تلقائياً لمساعدتك على فهم وصفتك الطبية. يُرجى دائماً الرجوع إلى طبيبك أو الصيدلاني لأي استفسار حول أدويتك. لا تتوقف عن تناول أي دواء دون استشارة طبيبك.
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>

<script>
    // Collect the full explanation text for TTS
    const summaryText = document.getElementById('summaryText')?.innerText ?? '';

    let utterance = null;
    let speaking   = false;

    function buildFullText() {
        let text = summaryText + '\n\n';
        @foreach($explanation['items'] as $index => $item)
            text += 'الدواء {{ $index + 1 }}: {{ $item["medicine"] }}.\n';
            text += '{{ $item["purpose"] ?? "" }}\n';
            text += 'الجرعة: {{ $item["dosage"] ?? "" }}.\n';
            text += '{{ $item["explanation"] }}\n';
            @if(!empty($item['warnings']))
                @foreach($item['warnings'] as $w)
                    text += 'تحذير: {{ $w }}\n';
                @endforeach
            @endif
            text += '\n';
        @endforeach
        return text;
    }

    function speakExplanation() {
        if (!('speechSynthesis' in window)) {
            alert('متصفحك لا يدعم ميزة تحويل النص إلى كلام.');
            return;
        }
        stopSpeaking();
        const text    = buildFullText();
        utterance     = new SpeechSynthesisUtterance(text);
        utterance.lang = 'ar-SA';
        utterance.rate = 0.9;
        utterance.pitch = 1;

        // Pick Arabic voice if available
        const voices = window.speechSynthesis.getVoices();
        const arVoice = voices.find(v => v.lang.startsWith('ar'));
        if (arVoice) utterance.voice = arVoice;

        utterance.onstart = () => {
            speaking = true;
            document.getElementById('ttsIcon').className = 'fas fa-volume-up fa-beat';
            document.getElementById('ttsBtn').style.background = 'linear-gradient(135deg,#4f46e5,#7c3aed)';
        };
        utterance.onend = utterance.onerror = () => {
            speaking = false;
            document.getElementById('ttsIcon').className = 'fas fa-volume-up';
            document.getElementById('ttsBtn').style.background = 'linear-gradient(135deg,#7c3aed,#4f46e5)';
        };

        window.speechSynthesis.speak(utterance);
    }

    function stopSpeaking() {
        if (window.speechSynthesis) {
            window.speechSynthesis.cancel();
            speaking = false;
            document.getElementById('ttsIcon').className = 'fas fa-volume-up';
        }
    }

    // Load voices asynchronously
    window.speechSynthesis.onvoiceschanged = () => { window.speechSynthesis.getVoices(); };
</script>
@endsection
