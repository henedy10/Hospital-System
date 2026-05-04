@extends('layouts.dashboard')
@section('title', 'شرح الوصفة الطبية التفصيلي')

@section('content')
{{-- Full RTL Arabic layout for this page --}}
<div dir="rtl" style="font-family: 'Cairo', 'Segoe UI', sans-serif; max-width: 950px; margin: 0 auto; padding-bottom: 80px;">

    {{-- Google Font: Cairo (Arabic-optimized) --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- Top Navigation & Actions --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:32px; flex-wrap:wrap; gap:16px;">
        <a href="{{ route('patient.prescriptions.index') }}"
           style="color:#64748b; text-decoration:none; display:flex; align-items:center; gap:8px; font-size:.9rem; font-weight:700; background:#fff; padding:10px 20px; border-radius:12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);"
           onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
            <i class="fas fa-arrow-right"></i> العودة للوصفات
        </a>

        <div style="display:flex; gap:12px;">
            <button id="ttsBtn" onclick="toggleSpeech()" 
                    style="background:linear-gradient(135deg,#7c3aed,#4f46e5); color:#fff; border:none; padding:10px 20px; border-radius:12px; font-weight:700; font-size:.9rem; cursor:pointer; display:flex; align-items:center; gap:10px; box-shadow:0 4px 12px rgba(124,58,237,.3); transition: all 0.3s ease;">
                <i id="ttsIcon" class="fas fa-volume-up"></i>
                <span id="ttsText">استمع للشرح</span>
            </button>
            <a href="{{ route('patient.ai-chat', ['message' => 'أريد أن أسأل عن وصفتي الطبية رقم ' . $prescription->id]) }}"
               style="background:#fff; color:#0d9488; border: 1px solid #0d9488; padding:10px 20px; border-radius:12px; font-weight:700; font-size:.9rem; text-decoration:none; display:flex; align-items:center; gap:10px; box-shadow:0 4px 12px rgba(13,148,136,0.05); transition: all 0.3s ease;"
               onmouseover="this.style.background='#f0fdfa'" onmouseout="this.style.background='#fff'">
                <i class="fas fa-comment-medical"></i> إسأل الطبيب الذكي
            </a>
        </div>
    </div>

    {{-- Hero Summary Card --}}
    <div style="background:linear-gradient(135deg,#0d9488 0%,#0891b2 50%,#0f172a 100%); border-radius:24px; padding:40px; margin-bottom:40px; color:#fff; position:relative; overflow:hidden; box-shadow: 0 20px 40px rgba(13,148,136,0.15);">
        <div style="position:absolute; top:-30px; left:-30px; width:180px; height:180px; background:rgba(255,255,255,.05); border-radius:50%;"></div>
        <div style="position:absolute; bottom:-40px; right:-20px; width:220px; height:220px; background:rgba(255,255,255,.04); border-radius:50%;"></div>
        
        <div style="position:relative; z-index:1;">
            <div style="display:flex; align-items:center; gap:20px; margin-bottom:24px; flex-wrap:wrap;">
                <div style="width:64px; height:64px; background:rgba(255,255,255,.15); backdrop-filter: blur(10px); border-radius:20px; display:flex; align-items:center; justify-content:center; border: 1px solid rgba(255,255,255,0.2);">
                    <i class="fas fa-stethoscope" style="font-size:2rem;"></i>
                </div>
                <div>
                    <h2 style="font-size:1.8rem; font-weight:800; margin:0;">تحليل الوصفة الطبي المتكامل</h2>
                    <div style="opacity:.8; font-size:.9rem; margin-top:6px; display:flex; align-items:center; gap:12px;">
                        <span><i class="far fa-file-alt"></i> وصفة #{{ $prescription->id }}</span>
                        <span><i class="far fa-user"></i> د. {{ $prescription->doctor->user->name ?? 'طبيبك' }}</span>
                        <span><i class="far fa-calendar-alt"></i> {{ $prescription->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            <div id="summaryText" style="font-size:1.15rem; line-height:2; opacity:.95; white-space:pre-line; background: rgba(0,0,0,0.15); padding: 28px; border-radius: 20px; border-right: 5px solid #94d2bd; box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);">{{ $explanation['summary'] }}</div>
        </div>
    </div>

    {{-- Section Divider --}}
    <div style="display:flex; align-items:center; gap:16px; margin-bottom:32px;">
        <h3 style="font-size:1.25rem; font-weight:800; color:#0f172a; margin:0; white-space:nowrap;">تحليل الأدوية الموصوفة</h3>
        <div style="height:2px; flex:1; background:linear-gradient(to left, #e2e8f0, transparent);"></div>
        <span style="background:#0d9488; color:#fff; padding:8px 20px; border-radius:30px; font-size:.9rem; font-weight:800; display:flex; align-items:center; gap:8px; box-shadow: 0 4px 10px rgba(13,148,136,0.2);">
            <i class="fas fa-capsules"></i> {{ count(array_filter($explanation['items'], 'is_array')) }} {{ count(array_filter($explanation['items'], 'is_array')) === 1 ? 'دواء' : 'أدوية' }}
        </span>
    </div>

    {{-- Per-Medicine Cards --}}
    <div id="medicineCards" style="display:grid; gap:32px;">
        @foreach($explanation['items'] as $index => $item)
        @if(is_array($item))
        <div class="medicine-card" style="background:#fff; border-radius:28px; box-shadow:0 15px 35px rgba(0,0,0,.04); border: 1px solid #f1f5f9; overflow:hidden; animation: slideUp .6s ease {{ $index * 0.15 }}s both; transition: all 0.4s ease;">

            {{-- Card Header --}}
            <div style="background:linear-gradient(135deg,#0f172a,#1e293b); padding:28px 40px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
                <div style="display:flex; align-items:center; gap:20px;">
                    <div style="width:52px; height:52px; background:linear-gradient(135deg,#0d9488,#0891b2); border-radius:16px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:1.3rem; box-shadow: 0 5px 15px rgba(13,148,136,0.3);">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <div style="color:#fff; font-weight:800; font-size:1.35rem; letter-spacing: 0.5px;">{{ $item['medicine'] }}</div>
                        <div style="color:#94d2bd; font-size:.88rem; font-weight:700; margin-top:4px; display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-tag"></i> معلومات طبية متقدمة
                        </div>
                    </div>
                </div>
                <div style="background:rgba(255,255,255,0.1); color:#fff; padding:8px 20px; border-radius:30px; font-size:.95rem; font-weight:700; border: 1px solid rgba(255,255,255,0.15);">
                    {{ $item['dosage'] ?? '' }}
                </div>
            </div>

            <div style="padding:40px;">
                {{-- Medicine Description --}}
                <div style="background:#f8fafc; border-radius:20px; padding:28px; margin-bottom:32px; border-right:6px solid #0d9488; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                    <div style="font-size:.9rem; font-weight:800; color:#0f172a; text-transform:uppercase; letter-spacing:.05em; margin-bottom:12px; display:flex; align-items:center; gap:10px;">
                        <i class="fas fa-info-circle" style="color:#0d9488;"></i> حول هذا الدواء
                    </div>
                    <div style="color:#334155; font-size:1.1rem; line-height:1.9; font-weight:600;">
                        {{ $item['description'] ?? 'لا يوجد وصف متاح.' }}
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:24px; margin-bottom:32px;">
                    {{-- Side Effects --}}
                    <div style="background:#fff7ed; border-radius:20px; padding:24px; border: 1px solid #ffedd5;">
                        <div style="color:#c2410c; font-size:1.1rem; font-weight:800; margin-bottom:16px; display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-vial"></i> الآثار الجانبية الشائعة
                        </div>
                        <ul style="margin:0; padding:0; list-style:none; display:grid; gap:10px;">
                            @foreach($item['side_effects'] ?? [] as $effect)
                                <li style="color:#9a3412; font-size:.95rem; font-weight:600; display:flex; align-items:flex-start; gap:8px;">
                                    <i class="fas fa-dot-circle" style="font-size:.6rem; margin-top:8px; opacity:.7;"></i>
                                    {{ $effect }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Patient Tips --}}
                    <div style="background:#eff6ff; border-radius:20px; padding:24px; border: 1px solid #dbeafe;">
                        <div style="color:#1d4ed8; font-size:1.1rem; font-weight:800; margin-bottom:16px; display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-lightbulb"></i> نصائح للمريض
                        </div>
                        <div style="color:#1e40af; font-size:.98rem; line-height:1.8; font-weight:600;">
                            {{ $item['patient_tips'] ?? 'التزم بتعليمات الطبيب.' }}
                        </div>
                    </div>
                </div>

                {{-- Dosage Details Grid --}}
                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:32px;">
                    <div style="background:#f1f5f9; border-radius:18px; padding:20px; text-align:center; border: 1px solid #e2e8f0;">
                        <div style="color:#475569; font-size:1.5rem; margin-bottom:10px;"><i class="fas fa-mortar-pestle"></i></div>
                        <div style="font-size:.75rem; color:#64748b; font-weight:800; text-transform:uppercase; margin-bottom:6px;">الجرعة</div>
                        <div style="font-weight:800; color:#0f172a; font-size:1.05rem;">{{ $item['dosage'] ?? '—' }}</div>
                    </div>
                    <div style="background:#f1f5f9; border-radius:18px; padding:20px; text-align:center; border: 1px solid #e2e8f0;">
                        <div style="color:#475569; font-size:1.5rem; margin-bottom:10px;"><i class="fas fa-clock"></i></div>
                        <div style="font-size:.75rem; color:#64748b; font-weight:800; text-transform:uppercase; margin-bottom:6px;">التكرار</div>
                        <div style="font-weight:800; color:#0f172a; font-size:1.05rem;">{{ ($item['frequency'] ?? '') }}x يومياً</div>
                    </div>
                    <div style="background:#f1f5f9; border-radius:18px; padding:20px; text-align:center; border: 1px solid #e2e8f0;">
                        <div style="color:#475569; font-size:1.5rem; margin-bottom:10px;"><i class="fas fa-calendar-check"></i></div>
                        <div style="font-size:.75rem; color:#64748b; font-weight:800; text-transform:uppercase; margin-bottom:6px;">المدة</div>
                        <div style="font-weight:800; color:#0f172a; font-size:1.05rem;">{{ ($item['duration'] ?? '') }} يوم</div>
                    </div>
                </div>

                {{-- Warnings Section --}}
                @if(!empty($item['detailed_warnings']))
                    <div style="margin-top:24px;">
                        <div style="font-size:.9rem; font-weight:800; color:#991b1b; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-exclamation-triangle"></i> تحذيرات وتنبيهات هامة
                        </div>
                        <div style="display:grid; gap:12px;">
                            @foreach($item['detailed_warnings'] as $warning)
                            <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:16px; padding:18px 24px; display:flex; gap:16px; align-items:center; box-shadow: 0 4px 10px rgba(225, 29, 72, 0.05);">
                                <i class="fas fa-shield-virus" style="color:#e11d48; flex-shrink:0; font-size: 1.3rem;"></i>
                                <div style="color:#9f1239; font-size:1rem; font-weight:700; line-height:1.6;">{{ $warning }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
        @endforeach
    </div>

    {{-- Safety Disclaimer --}}
    <div style="margin-top:50px; background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-radius:24px; padding:35px; display:flex; gap:25px; align-items:center; border:1px solid #bbf7d0; box-shadow: 0 10px 30px rgba(22, 163, 74, 0.08);">
        <div style="width:64px; height:64px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#16a34a; font-size:1.8rem; flex-shrink:0; box-shadow: 0 6px 12px rgba(0,0,0,0.06);">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div style="color:#166534; font-size:1rem; line-height:1.9;">
            <strong style="font-size: 1.2rem; display: block; margin-bottom: 6px;">إخلاء مسؤولية طبي</strong>
            تم توليد هذه المعلومات بواسطة ذكاء اصطناعي طبي متقدم لمساعدتك على فهم علاجك. هذا التحليل لا يغني عن استشارة الطبيب أو الصيدلاني. يُمنع تغيير الجرعات أو التوقف عن العلاج دون الرجوع للفريق الطبي المعالج. في حال ظهور أعراض حساسية شديدة، توجه فوراً لأقرب مركز طوارئ.
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity: 0; transform: translateY(40px); }
    to   { opacity: 1; transform: translateY(0); }
}
.medicine-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(0,0,0,.1) !important;
}
</style>

<script>
    const summaryText = document.getElementById('summaryText')?.innerText ?? '';
    let utterance = null;
    let speaking   = false;

    function buildFullText() {
        let text = summaryText + '\n\n';
        @foreach($explanation['items'] as $index => $item)
            @if(is_array($item))
            text += 'الدواء {{ $index + 1 }}: {{ $item["medicine"] }}.\n';
            text += '{{ $item["description"] ?? "" }}\n';
            text += 'الجرعة: {{ $item["dosage"] ?? "" }}.\n';
            text += 'نصيحة: {{ $item["patient_tips"] ?? "" }}\n';
            @if(!empty($item['detailed_warnings']))
                @foreach($item['detailed_warnings'] as $w)
                    text += 'تنبيه: {{ $w }}\n';
                @endforeach
            @endif
            text += '\n';
            @endif
        @endforeach
        return text;
    }

    function toggleSpeech() {
        if (speaking) { stopSpeaking(); } else { speakExplanation(); }
    }

    function speakExplanation() {
        if (!('speechSynthesis' in window)) {
            alert('متصفحك لا يدعم ميزة تحويل النص إلى كلام.');
            return;
        }
        const text = buildFullText();
        utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'ar-SA';
        utterance.rate = 0.85;
        utterance.pitch = 1;

        const voices = window.speechSynthesis.getVoices();
        const arVoice = voices.find(v => v.lang.startsWith('ar'));
        if (arVoice) utterance.voice = arVoice;

        utterance.onstart = () => {
            speaking = true;
            document.getElementById('ttsIcon').className = 'fas fa-stop-circle fa-beat';
            document.getElementById('ttsText').innerText = 'إيقاف القراءة';
            document.getElementById('ttsBtn').style.background = 'linear-gradient(135deg,#ef4444,#dc2626)';
        };

        utterance.onend = utterance.onerror = () => {
            speaking = false;
            document.getElementById('ttsIcon').className = 'fas fa-volume-up';
            document.getElementById('ttsText').innerText = 'استمع للشرح';
            document.getElementById('ttsBtn').style.background = 'linear-gradient(135deg,#7c3aed,#4f46e5)';
        };

        window.speechSynthesis.speak(utterance);
    }

    function stopSpeaking() {
        if (window.speechSynthesis) { window.speechSynthesis.cancel(); speaking = false; }
    }

    window.speechSynthesis.onvoiceschanged = () => { window.speechSynthesis.getVoices(); };
</script>
@endsection
