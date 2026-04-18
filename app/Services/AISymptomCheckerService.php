<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AISymptomCheckerService
{
    /**
     * Analyze symptoms using OpenAI API
     *
     * @param string $symptoms Patient's entered symptoms
     * @return array Returns structured array with possible_diseases, recommended_specialization, urgency_level, and medical_advice.
     */
    public function analyzeSymptoms(string $symptoms): array
    {
        $prompt = "You are a medical AI assistant.
                    Analyze patient symptoms and return structured JSON only.
                    Do not provide a final diagnosis.
                    Always include:
                    - possible_diseases (max 3)
                    - recommended_specialization
                    - urgency_level (low, medium, high)
                    - medical_advice (short advice)

                    Patient Symptoms:
                    \"$symptoms\" ";

        try {
            $response = Http::withToken(config('services.openai.api_key'))
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini', // gpt-4.1-mini isn't valid, but gpt-4o-mini is standard for affordable JSON now.
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => $prompt],
                        ['role' => 'user', 'content' => $symptoms]
                    ],
                    'temperature' => 0.1,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                if ($content) {
                    $decoded = json_decode($content, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $decoded;
                    }
                }
            }
            
            Log::error('OpenAI API Error or invalid JSON returned', ['response' => $response->body()]);

        } catch (Exception $e) {
            Log::error('OpenAI Exception checking symptoms', ['error' => $e->getMessage()]);
        }

        // Fallback response if AI fails or returns invalid results
        return [
            'possible_diseases' => ['Unable to determine at this time'],
            'recommended_specialization' => 'General Medicine',
            'urgency_level' => 'medium',
            'medical_advice' => 'System could not process symptoms. Please consult a doctor directly.'
        ];
    }
}
