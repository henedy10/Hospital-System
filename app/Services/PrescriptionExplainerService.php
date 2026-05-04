<?php

namespace App\Services;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PrescriptionExplainerService
{
    protected string $geminiUrl;

    public function __construct()
    {
        $this->geminiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . env('GEMINI_API_KEY');
    }

    /**
     * Main entry point — explain an entire prescription.
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

        // 1. Basic local processing
        $explainedItems = $prescription->items->map(function(PrescriptionItem $item) {
            return [
                'medicine'     => $item->medicine_name,
                'dosage'       => $item->dosage,
                'frequency'    => $item->frequency,
                'duration'     => $item->duration,
                'instructions' => $item->instructions,
                'local_warnings' => $this->applyLocalRules($item),
            ];
        })->all();

        // 2. Enrich with Gemini (Detailed Info & Warnings)
        $enrichedItems = $this->enrichWithGemini($explainedItems, $prescription->notes);

        return [
            'summary' => $this->buildSummary($prescription, $enrichedItems),
            'items'   => $enrichedItems,
        ];
    }

    /**
     * Use Gemini to get detailed medical information for all medicines in one go.
     */
    private function enrichWithGemini(array $items, ?string $doctorNotes): array
    {
        $medicineList = implode(', ', array_column($items, 'medicine'));
        
        $prompt = "As a medical expert AI, analyze this prescription in Arabic.
Medicines: $medicineList
Doctor Notes: $doctorNotes

For each medicine, provide:
1. 'description': Detailed explanation of what it does and its medical category.
2. 'side_effects': Array of 3-4 common side effects.
3. 'detailed_warnings': Array of 3-4 specific safety warnings (interactions, contraindications).
4. 'patient_tips': Best way to take it (time of day, food, etc.).

Also provide a 'general_summary': A professional medical summary of the whole prescription in Arabic.

Return ONLY a valid JSON object with this structure:
{
  \"medicines\": [
    { \"name\": \"...\", \"description\": \"...\", \"side_effects\": [...], \"detailed_warnings\": [...], \"patient_tips\": \"...\" },
    ...
  ],
  \"general_summary\": \"...\"
}";

        try {
            $response = Http::timeout(30)->post($this->geminiUrl, [
                'contents' => [
                    'parts' => [['text' => $prompt]]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ]
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                $aiData = json_decode($content, true);

                if (isset($aiData['medicines'])) {
                    foreach ($items as $index => &$item) {
                        $aiMedicine = $aiData['medicines'][$index] ?? null;
                        if ($aiMedicine) {
                            $item['description'] = $aiMedicine['description'];
                            $item['side_effects'] = $aiMedicine['side_effects'];
                            $item['detailed_warnings'] = array_merge($item['local_warnings'], $aiMedicine['detailed_warnings']);
                            $item['patient_tips'] = $aiMedicine['patient_tips'];
                        } else {
                            $this->applyFallbacks($item);
                        }
                    }
                    $items['ai_summary'] = $aiData['general_summary'] ?? null;
                    return $items;
                }
            }
        } catch (Exception $e) {
            Log::error('Gemini Prescription Enrichment Error', ['error' => $e->getMessage()]);
        }

        // Fallback if Gemini fails
        foreach ($items as &$item) {
            $this->applyFallbacks($item);
        }
        return $items;
    }

    private function applyFallbacks(array &$item): void
    {
        $item['description'] = 'دواء موصوف من قِبَل طبيبك لحالتك الصحية — يُرجى استشارة طبيبك أو الصيدلاني لمزيد من التفاصيل.';
        $item['side_effects'] = ['قد تختلف الأعراض الجانبية من شخص لآخر.'];
        $item['detailed_warnings'] = $item['local_warnings'];
        $item['patient_tips'] = 'التزم بمواعيد الجرعات المحددة من قبل الطبيب.';
    }

    private function applyLocalRules(PrescriptionItem $item): array
    {
        $warnings = [];
        if ($item->frequency > 3) {
            $warnings[] = '⚠️ تنبيه: الجرعة عالية (' . $item->frequency . ' مرات يومياً).';
        }
        if ($item->duration > 10) {
            $warnings[] = '📋 تذكير: مدة العلاج طويلة، راجع الطبيب قبل التجديد.';
        }
        return $warnings;
    }

    private function buildSummary(Prescription $prescription, array $items): string
    {
        if (isset($items['ai_summary'])) {
            return $items['ai_summary'];
        }

        $doctorName = $prescription->doctor->user->name ?? 'طبيبك';
        $patientName = $prescription->patient->user->name ?? 'عزيزي المريض';
        $date = $prescription->created_at->format('d/m/Y');
        
        return "مرحباً {$patientName}، فيما يلي شرح وصفتك الطبية الصادرة بتاريخ {$date} من الدكتور {$doctorName}. يُرجى الالتزام بالتعليمات الموضحة لكل دواء.";
    }
}
