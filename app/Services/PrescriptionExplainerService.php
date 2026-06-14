<?php

namespace App\Services;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PrescriptionExplainerService
{
    /**
     * Main entry point — explain an entire prescription.
     */
    public function explain(Prescription $prescription): array
    {
        $prescription->loadMissing(['items', 'doctor.user', 'patient.user']);

        if ($prescription->items->isEmpty()) {
            return [
                'data' => [],
                'summary' => 'هذه الوصفة الطبية لا تحتوي على أي دواء. يُرجى التواصل مع طبيبك.',
            ];
        }

        try {
        $response = Http::timeout(20)->post('http://127.0.0.1:5005/xai-explain-prescription', [
            'items' => $prescription->items->map(function ($item) {
                return [
                    'medicine_name' => $item->medicine_name,
                    'dosage' => $item->dosage
                ];
            })->toArray(),
            'notes' => $prescription->notes
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['status']) && $data['status'] === 'success') {
                $mapped = collect($data['data'])->map(function ($drug) {
// dd($drug);
                    return [
                        'drug_name' => $drug['drug_name'] ?? 'Unknown',

                        'english' => [
                            'usage' => $drug['english']['usage'] ?? '',
                            'dosage' => $drug['english']['dosage'] ?? '',
                            'side_effects' => $drug['english']['side_effects'] ?? [],
                            'warnings' => $drug['english']['warnings'] ?? [],
                            'summary' => $drug['english']['summary'] ?? '',
                            'xai' => [
                                'feature_importance' => $drug['english']['xai']['feature_importance'] ?? [],
                                'reasoning' => $drug['english']['xai']['explanation'] ?? [],
                                'confidence' => $drug['english']['xai']['confidence_score'] ?? 0,
                            ]
                        ],

                    ];
                });
// dd($mapped);
                return [
                    'data' => $mapped->toArray(),
                ];
            }
        }
    } catch (Exception $e) {
        Log::error('Python XAI API Error', [
            'error' => $e->getMessage()
        ]);
    }
    // fallback
    return [
        'data' => [],
        'error' => 'Could not fetch AI explanation'
    ];
}
}
