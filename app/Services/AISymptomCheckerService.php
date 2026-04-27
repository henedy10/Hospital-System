<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AISymptomCheckerService
{
    protected string $apiUrl;

    public function __construct()
    {
        // Local Flask API URL
        $this->apiUrl = 'http://127.0.0.1:5005/predict';
    }

    /**
     * Analyze symptoms using local Python Flask API
     *
     * @param array $symptoms Map of symptoms (e.g. ['fever' => 1, 'cough' => 0])
     * @return array Predictions from the model
     */
    public function analyzeSymptoms(array $symptoms): array
    {
        try {
            $response = Http::timeout(10)
                ->post($this->apiUrl, $symptoms);

            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error('AI Flask API Error', ['body' => $response->body()]);

        } catch (Exception $e) {
            Log::error('AI Flask Connection Exception', ['error' => $e->getMessage()]);
        }

        // Fallback response if API fails
        return [
            'predicted_disease' => 'Unknown',
            'specialization' => 'General Medicine',
            'urgency' => 'medium',
            'error' => 'Could not connect to AI service'
        ];
    }
}
