<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AISymptomCheckerService
{
    protected string $flaskUrl;
    protected string $geminiUrl;

    public function __construct()
    {
        // Local Flask API URL
        $this->flaskUrl = 'http://127.0.0.1:5005/predict';
        // Gemini API URL
        $this->geminiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . env('GEMINI_API_KEY');
    }

    /**
     * Analyze symptoms from natural language text
     *
     * @param string $symptomsText
     * @return array Predictions from the model
     */
    public function analyzeSymptoms(string $symptomsText): array
    {
        try {
            // 1. Use Gemini to extract binary features from the text
            $features = $this->extractFeaturesWithGemini($symptomsText);
            
            // 2. Call local Flask API with extracted features
            $response = Http::timeout(10)
                ->post($this->flaskUrl, $features);

            if ($response->successful()) {
                $result = $response->json();
                // Include the original text for history/display if needed
                $result['original_text'] = $symptomsText;
                return $result;
            }
            
            Log::error('AI Flask API Error', ['body' => $response->body()]);

        } catch (Exception $e) {
            Log::error('AI Symptom Checker Exception', ['error' => $e->getMessage()]);
        }

        // Fallback response if everything fails
        return [
            'predicted_disease' => 'Unknown',
            'specialization' => 'General Medicine',
            'urgency' => 'medium',
            'error' => 'Could not complete AI analysis',
            'original_text' => $symptomsText
        ];
    }

    /**
     * Use Gemini to extract symptoms into a structured JSON object
     */
    private function extractFeaturesWithGemini(string $text): array
    {
        $prompt = "Extract the following symptoms from the user's text. 
Return ONLY a valid JSON object with these keys and values as 1 (present) or 0 (absent):
fever, cough, headache, fatigue, chest_pain, shortness_of_breath, dizziness, nausea, sore_throat.

User text: \"$text\"";

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
                $features = json_decode($content, true);
                
                if (is_array($features)) {
                    // Ensure all keys are present
                    $defaults = [
                        'fever' => 0, 'cough' => 0, 'headache' => 0, 'fatigue' => 0, 
                        'chest_pain' => 0, 'shortness_of_breath' => 0, 'dizziness' => 0, 
                        'nausea' => 0, 'sore_throat' => 0
                    ];
                    return array_merge($defaults, $features);
                }
            }
        } catch (Exception $e) {
            Log::error('Gemini Feature Extraction Error', ['error' => $e->getMessage()]);
        }

        // Return empty defaults if Gemini fails
        return [
            'fever' => 0, 'cough' => 0, 'headache' => 0, 'fatigue' => 0, 
            'chest_pain' => 0, 'shortness_of_breath' => 0, 'dizziness' => 0, 
            'nausea' => 0, 'sore_throat' => 0
        ];
    }
}
