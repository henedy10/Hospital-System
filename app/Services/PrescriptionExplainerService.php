<?php

namespace App\Services;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrescriptionExplainerService
{
    /**
     * Flask AI service URL.
     */
    protected string $flaskUrl = 'http://127.0.0.1:5005/explain-prescription';

    /**
     * Arabic medicine knowledge base.
     * Keys are lowercase medicine names (or partials).
     */
    protected array $medicineKnowledgeBase = [
        // Pain & Fever
        'paracetamol'   => ['purpose' => 'مسكن للألم وخافض للحرارة', 'category' => 'مسكنات'],
        'acetaminophen' => ['purpose' => 'مسكن للألم وخافض للحرارة', 'category' => 'مسكنات'],
        'ibuprofen'     => ['purpose' => 'مضاد للالتهاب ومسكن للألم وخافض للحرارة', 'category' => 'مضادات الالتهاب'],
        'aspirin'       => ['purpose' => 'مسكن للألم ومضاد للتخثر وخافض للحرارة', 'category' => 'مسكنات'],
        'diclofenac'    => ['purpose' => 'مضاد للالتهاب ومسكن للآلام المفصلية والعضلية', 'category' => 'مضادات الالتهاب'],
        'naproxen'      => ['purpose' => 'مضاد للالتهاب ومسكن للألم', 'category' => 'مضادات الالتهاب'],
        'tramadol'      => ['purpose' => 'مسكن قوي للألم الشديد', 'category' => 'مسكنات قوية'],
        'codeine'       => ['purpose' => 'مسكن للألم المتوسط إلى الشديد', 'category' => 'مسكنات قوية'],
        'morphine'      => ['purpose' => 'مسكن للألم الشديد جداً', 'category' => 'مسكنات قوية'],

        // Antibiotics
        'amoxicillin'   => ['purpose' => 'مضاد حيوي لعلاج العدوى البكتيرية كالتهابات الجهاز التنفسي والأذن', 'category' => 'مضادات حيوية'],
        'azithromycin'  => ['purpose' => 'مضاد حيوي لعلاج التهابات الجهاز التنفسي والجلد', 'category' => 'مضادات حيوية'],
        'ciprofloxacin' => ['purpose' => 'مضاد حيوي لعلاج عدوى المسالك البولية والعدوى البكتيرية المختلفة', 'category' => 'مضادات حيوية'],
        'doxycycline'   => ['purpose' => 'مضاد حيوي يُستخدم لعلاج أنواع متعددة من العدوى البكتيرية', 'category' => 'مضادات حيوية'],
        'metronidazole' => ['purpose' => 'مضاد حيوي لعلاج الالتهابات البكتيرية والطفيلية', 'category' => 'مضادات حيوية'],
        'cephalexin'    => ['purpose' => 'مضاد حيوي لعلاج التهابات الجلد والجهاز التنفسي', 'category' => 'مضادات حيوية'],
        'augmentin'     => ['purpose' => 'مضاد حيوي مركب لعلاج العدوى البكتيرية الشديدة', 'category' => 'مضادات حيوية'],
        'clindamycin'   => ['purpose' => 'مضاد حيوي لعلاج العدوى البكتيرية والالتهابات', 'category' => 'مضادات حيوية'],
        'erythromycin'  => ['purpose' => 'مضاد حيوي لعلاج الالتهابات البكتيرية في الحالق والجلد', 'category' => 'مضادات حيوية'],
        'vancomycin'    => ['purpose' => 'مضاد حيوي قوي لعلاج العدوى البكتيرية الخطيرة', 'category' => 'مضادات حيوية'],

        // GI / Stomach
        'omeprazole'    => ['purpose' => 'يحمي المعدة ويقلل إفراز الحمض، يُستخدم لعلاج قرحة المعدة والحموضة', 'category' => 'حماية المعدة'],
        'pantoprazole'  => ['purpose' => 'يقلل حمض المعدة ويحمي بطانتها', 'category' => 'حماية المعدة'],
        'esomeprazole'  => ['purpose' => 'يعالج الحموضة وارتجاع المريء وقرحة المعدة', 'category' => 'حماية المعدة'],
        'ranitidine'    => ['purpose' => 'يقلل إفراز حمض المعدة ويعالج الحموضة', 'category' => 'حماية المعدة'],
        'metoclopramide'=> ['purpose' => 'يعالج الغثيان والقيء ويساعد على تحريك الجهاز الهضمي', 'category' => 'هضم'],
        'domperidone'   => ['purpose' => 'يعالج الغثيان والقيء ويحسن عمل المعدة', 'category' => 'هضم'],
        'loperamide'    => ['purpose' => 'يعالج الإسهال ويبطئ حركة الأمعاء', 'category' => 'هضم'],
        'bisacodyl'     => ['purpose' => 'ملين يُستخدم لعلاج الإمساك', 'category' => 'هضم'],

        // Diabetes
        'metformin'     => ['purpose' => 'يُستخدم لعلاج السكري من النوع الثاني وتنظيم مستوى السكر في الدم', 'category' => 'السكري'],
        'glibenclamide' => ['purpose' => 'يحفز البنكرياس على إفراز الأنسولين لعلاج السكري', 'category' => 'السكري'],
        'glimepiride'   => ['purpose' => 'يُستخدم لعلاج السكري من النوع الثاني', 'category' => 'السكري'],
        'insulin'       => ['purpose' => 'هرمون أساسي لتنظيم مستوى السكر في الدم', 'category' => 'السكري'],
        'sitagliptin'   => ['purpose' => 'يساعد على التحكم في مستوى السكر في الدم لمرضى السكري', 'category' => 'السكري'],

        // Blood Pressure / Heart
        'amlodipine'    => ['purpose' => 'يُستخدم لعلاج ضغط الدم المرتفع وألم الصدر الناتج عن أمراض القلب', 'category' => 'القلب والضغط'],
        'atenolol'      => ['purpose' => 'يُستخدم لعلاج ضغط الدم المرتفع واضطرابات نظم القلب', 'category' => 'القلب والضغط'],
        'lisinopril'    => ['purpose' => 'يُستخدم لعلاج ضغط الدم المرتفع وقصور القلب', 'category' => 'القلب والضغط'],
        'losartan'      => ['purpose' => 'يُستخدم لعلاج ضغط الدم المرتفع وحماية الكلى', 'category' => 'القلب والضغط'],
        'bisoprolol'    => ['purpose' => 'يُستخدم لعلاج ضغط الدم المرتفع وفشل القلب', 'category' => 'القلب والضغط'],
        'furosemide'    => ['purpose' => 'مدر للبول يُستخدم لعلاج احتباس السوائل وضغط الدم المرتفع', 'category' => 'القلب والضغط'],
        'warfarin'      => ['purpose' => 'مضاد للتخثر يمنع تكوّن الجلطات الدموية', 'category' => 'القلب والضغط'],
        'clopidogrel'   => ['purpose' => 'يمنع تكوّن الجلطات ويُستخدم بعد نوبات القلب', 'category' => 'القلب والضغط'],
        'simvastatin'   => ['purpose' => 'يخفض مستوى الكوليسترول في الدم ويحمي القلب', 'category' => 'الكوليسترول'],
        'atorvastatin'  => ['purpose' => 'يخفض الكوليسترول ويقلل خطر أمراض القلب', 'category' => 'الكوليسترول'],

        // Respiratory
        'salbutamol'    => ['purpose' => 'موسع للشعب الهوائية يُستخدم لعلاج ضيق التنفس والربو', 'category' => 'الجهاز التنفسي'],
        'prednisolone'  => ['purpose' => 'كورتيزون يُستخدم لعلاج الالتهابات والحساسية وأمراض المناعة', 'category' => 'كورتيزون'],
        'dexamethasone' => ['purpose' => 'كورتيزون قوي يُستخدم لعلاج الالتهابات الشديدة والحساسية', 'category' => 'كورتيزون'],
        'cetirizine'    => ['purpose' => 'مضاد للحساسية يُستخدم لعلاج الحكة والعطس وحساسية الأنف', 'category' => 'مضادات الحساسية'],
        'loratadine'    => ['purpose' => 'مضاد للحساسية لا يسبب النعاس', 'category' => 'مضادات الحساسية'],
        'chlorphenamine'=> ['purpose' => 'مضاد للحساسية والحكة والعطس', 'category' => 'مضادات الحساسية'],

        // Vitamins & Supplements
        'vitamin c'     => ['purpose' => 'فيتامين مهم لتقوية المناعة وصحة الجلد', 'category' => 'فيتامينات'],
        'vitamin d'     => ['purpose' => 'فيتامين ضروري لصحة العظام والمناعة', 'category' => 'فيتامينات'],
        'vitamin b12'   => ['purpose' => 'فيتامين ضروري للأعصاب وتكوين خلايا الدم الحمراء', 'category' => 'فيتامينات'],
        'folic acid'    => ['purpose' => 'حمض الفوليك الضروري لبناء الخلايا وصحة الدم خاصة أثناء الحمل', 'category' => 'فيتامينات'],
        'iron'          => ['purpose' => 'مكمل حديد لعلاج فقر الدم', 'category' => 'مكملات غذائية'],
        'calcium'       => ['purpose' => 'مكمل كالسيوم لصحة العظام والأسنان', 'category' => 'مكملات غذائية'],
        'zinc'          => ['purpose' => 'مكمل زنك لتقوية المناعة وتسريع التعافي', 'category' => 'مكملات غذائية'],

        // Nervous System / Psyche
        'sertraline'    => ['purpose' => 'مضاد للاكتئاب يُستخدم لتحسين الحالة المزاجية', 'category' => 'الصحة النفسية'],
        'fluoxetine'    => ['purpose' => 'مضاد للاكتئاب يُستخدم لعلاج الاكتئاب والقلق', 'category' => 'الصحة النفسية'],
        'diazepam'      => ['purpose' => 'مهدئ يُستخدم لعلاج القلق والتوتر والتشنجات', 'category' => 'مهدئات'],
        'alprazolam'    => ['purpose' => 'مهدئ يُستخدم لعلاج اضطرابات القلق', 'category' => 'مهدئات'],
        'amitriptyline' => ['purpose' => 'يُستخدم لعلاج الاكتئاب وآلام الأعصاب', 'category' => 'الصحة النفسية'],
        'gabapentin'    => ['purpose' => 'يُستخدم لعلاج آلام الأعصاب والصرع', 'category' => 'أعصاب'],
        'carbamazepine' => ['purpose' => 'يُستخدم لعلاج الصرع واضطرابات الأعصاب', 'category' => 'أعصاب'],

        // Thyroid
        'levothyroxine' => ['purpose' => 'هرمون الغدة الدرقية الاصطناعي لعلاج قصور الغدة الدرقية', 'category' => 'الغدة الدرقية'],

        // Infection / Antifungal
        'fluconazole'   => ['purpose' => 'مضاد للفطريات يُستخدم لعلاج العدوى الفطرية', 'category' => 'مضادات الفطريات'],
        'acyclovir'     => ['purpose' => 'مضاد فيروسي يُستخدم لعلاج الهربس والحزام الناري', 'category' => 'مضادات الفيروسات'],
    ];

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Main entry point — explain an entire prescription.
     *
     * @param Prescription $prescription
     * @return array{ summary: string, items: array }
     */
    public function explain(Prescription $prescription): array
    {
        $prescription->loadMissing(['items', 'doctor.user', 'patient.user']);

        if ($prescription->items->isEmpty()) {
            return [
                'summary' => 'هذه الوصفة الطبية لا تحتوي على أي دواء. يُرجى التواصل مع طبيبك.',
                'items'   => [],
            ];
        }

        $explainedItems = $prescription->items->map(
            fn(PrescriptionItem $item) => $this->explainItem($item)
        )->all();

        // Try Flask AI enhancement first
        $flaskResult = $this->tryFlaskEnhancement($prescription, $explainedItems);
        if ($flaskResult !== null) {
            return $flaskResult;
        }

        return [
            'summary' => $this->buildSummary($prescription, $explainedItems),
            'items'   => $explainedItems,
        ];
    }

    /**
     * Explain a single prescription item.
     *
     * @param PrescriptionItem $item
     * @return array{ medicine: string, explanation: string, warnings: array }
     */
    public function explainItem(PrescriptionItem $item): array
    {
        $info      = $this->getMedicineInfo($item->medicine_name);
        $warnings  = $this->applySmartRules($item);

        $lines = [];
        $lines[] = "💊 **{$item->medicine_name}** — {$info['purpose']}";
        $lines[] = "📌 الجرعة: {$item->dosage}";
        $lines[] = "🕐 {$this->buildFrequencyText($item->frequency)}";
        $lines[] = "📅 {$this->buildDurationText($item->duration)}";

        if ($item->instructions) {
            $lines[] = "📝 {$this->buildInstructionText($item->instructions)}";
        }

        return [
            'medicine'     => $item->medicine_name,
            'category'     => $info['category'],
            'purpose'      => $info['purpose'],
            'dosage'       => $item->dosage,
            'frequency'    => $item->frequency,
            'duration'     => $item->duration,
            'instructions' => $item->instructions,
            'explanation'  => implode("\n", $lines),
            'warnings'     => $warnings,
            'is_unknown'   => $info['is_unknown'],
        ];
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    /**
     * Lookup medicine in knowledge base.
     */
    protected function getMedicineInfo(string $name): array
    {
        $key = strtolower(trim($name));

        // Exact match
        if (isset($this->medicineKnowledgeBase[$key])) {
            return array_merge(
                $this->medicineKnowledgeBase[$key],
                ['is_unknown' => false]
            );
        }

        // Partial match — check if any KB key is contained within the name
        foreach ($this->medicineKnowledgeBase as $kbKey => $info) {
            if (str_contains($key, $kbKey) || str_contains($kbKey, $key)) {
                return array_merge($info, ['is_unknown' => false]);
            }
        }

        // Unknown medicine — generic fallback
        return [
            'purpose'    => 'دواء موصوف من قِبَل طبيبك لحالتك الصحية — يُرجى استشارة طبيبك أو الصيدلاني لمزيد من التفاصيل.',
            'category'   => 'دواء غير معروف',
            'is_unknown' => true,
        ];
    }

    /**
     * Generate Arabic frequency phrase.
     */
    protected function buildFrequencyText(int $frequency): string
    {
        return match (true) {
            $frequency === 1 => 'يُؤخذ مرة واحدة يومياً',
            $frequency === 2 => 'يُؤخذ مرتين يومياً (كل 12 ساعة)',
            $frequency === 3 => 'يُؤخذ ثلاث مرات يومياً (كل 8 ساعات)',
            $frequency === 4 => 'يُؤخذ أربع مرات يومياً (كل 6 ساعات)',
            default          => "يُؤخذ {$frequency} مرات يومياً",
        };
    }

    /**
     * Generate Arabic duration phrase.
     */
    protected function buildDurationText(int $days): string
    {
        return match (true) {
            $days === 1  => 'لمدة يوم واحد فقط',
            $days === 2  => 'لمدة يومين',
            $days === 3  => 'لمدة ثلاثة أيام',
            $days <= 6   => "لمدة {$days} أيام",
            $days === 7  => 'لمدة أسبوع واحد',
            $days === 14 => 'لمدة أسبوعين',
            $days === 30 => 'لمدة شهر واحد',
            default      => "لمدة {$days} يوماً",
        };
    }

    /**
     * Generate Arabic instruction text.
     */
    protected function buildInstructionText(string $instructions): string
    {
        $lower = strtolower($instructions);

        if (str_contains($lower, 'before') || str_contains($lower, 'قبل الأكل')) {
            return 'يُؤخذ قبل الأكل';
        }
        if (str_contains($lower, 'after') || str_contains($lower, 'بعد الأكل')) {
            return 'يُؤخذ بعد الأكل';
        }
        if (str_contains($lower, 'with food') || str_contains($lower, 'مع الطعام')) {
            return 'يُؤخذ مع الطعام';
        }
        if (str_contains($lower, 'empty') || str_contains($lower, 'صائم')) {
            return 'يُؤخذ على معدة فارغة';
        }
        if (str_contains($lower, 'sleep') || str_contains($lower, 'نوم')) {
            return 'يُؤخذ عند النوم';
        }

        return "تعليمات إضافية: {$instructions}";
    }

    /**
     * Apply smart warning rules.
     *
     * @return string[]
     */
    protected function applySmartRules(PrescriptionItem $item): array
    {
        $warnings = [];

        if ($item->frequency > 3) {
            $warnings[] = '⚠️ تنبيه: الجرعة عالية ({$item->frequency} مرات يومياً). لا تتجاوز الجرعة الموصوفة تجنباً للتأثيرات الجانبية.';
        }

        if ($item->duration > 10) {
            $warnings[] = '📋 تذكير: مدة العلاج تتجاوز 10 أيام. يُنصح بمراجعة الطبيب قبل الانتهاء من الجرعة.';
        }

        if (empty(trim($item->dosage))) {
            $warnings[] = '❗ تحذير: الجرعة غير محددة — استشر طبيبك أو الصيدلاني.';
        }

        return $warnings;
    }

    /**
     * Build the overall Arabic summary paragraph.
     */
    protected function buildSummary(Prescription $prescription, array $items): string
    {
        $doctorName  = $prescription->doctor->user->name ?? 'طبيبك';
        $patientName = $prescription->patient->user->name ?? 'عزيزي المريض';
        $count       = count($items);
        $date        = $prescription->created_at->format('d/m/Y');

        $medicineNames = implode('، ', array_column($items, 'medicine'));

        $summary  = "مرحباً {$patientName}،\n\n";
        $summary .= "فيما يلي شرح مبسّط للوصفة الطبية الصادرة بتاريخ {$date} من الدكتور / {$doctorName}.\n\n";
        $summary .= "تحتوي وصفتك على {$count} " . ($count === 1 ? 'دواء' : 'أدوية') . ": {$medicineNames}.\n\n";

        if ($prescription->notes) {
            $summary .= "ملاحظات الطبيب: {$prescription->notes}\n\n";
        }

        $summary .= "يُرجى الالتزام بالجرعات والمواعيد المحددة، وإبلاغ طبيبك إذا ظهرت أي أعراض جانبية.";

        return $summary;
    }

    /**
     * Optionally call Flask AI endpoint for enhanced Arabic explanation.
     * Returns null if Flask is unavailable.
     */
    protected function tryFlaskEnhancement(Prescription $prescription, array $localItems): ?array
    {
        try {
            $payload = [
                'prescription' => [
                    'notes' => $prescription->notes,
                    'items' => array_map(fn($i) => [
                        'medicine_name' => $i['medicine'],
                        'dosage'        => $i['dosage'],
                        'frequency'     => $i['frequency'],
                        'duration'      => $i['duration'],
                        'instructions'  => $i['instructions'],
                    ], $localItems),
                ],
            ];

            $response = Http::timeout(5)->post($this->flaskUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                // Merge warnings from local rules back in
                foreach ($data['items'] as $index => &$flaskItem) {
                    $flaskItem['warnings'] = $localItems[$index]['warnings'] ?? [];
                    $flaskItem['is_unknown'] = $localItems[$index]['is_unknown'] ?? false;
                    $flaskItem['category']   = $localItems[$index]['category'] ?? '';
                }
                return $data;
            }
        } catch (\Exception $e) {
            Log::info('Flask Prescription Explainer unavailable — using local engine.', ['error' => $e->getMessage()]);
        }

        return null;
    }
}
