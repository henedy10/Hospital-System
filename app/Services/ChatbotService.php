<?php

namespace App\Services;

use App\Models\ChatMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ChatbotService
{
    /**
     * Get response from OpenAI based on user message and history.
     */
    public function getResponse(int $userId, string $message): string
    {
        $systemPrompt = "You are a medical assistant chatbot.
Your role is to:
1. Explain medical information in simple terms.
2. Help patients understand lab results and prescriptions.
3. Provide general health advice.

Rules:
- Do NOT give a final diagnosis.
- Always recommend consulting a doctor when needed.
- If symptoms are serious, advise urgent care.
- Keep answers short, clear, and safe.
- End your responses with a friendly tone.
- Do NOT output markdown headers, try to keep it conversational.";

        // Fetch last 5 messages for context
        $history = ChatMessage::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get()
            ->reverse();

        $messages = [];

        foreach ($history as $chat) {
            $messages[] = [
                'role' => 'user',
                'parts' => [['text' => $chat->message]]
            ];
            $messages[] = [
                'role' => 'model',
                'parts' => [['text' => $chat->response]]
            ];
        }

        $messages[] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];

        try {
            $response = Http::timeout(30)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . env('GEMINI_API_KEY'), [
                'contents' => $messages,
                'system_instruction' => [
                    'parts' => [['text' => $systemPrompt]]
                ]
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                if ($content) {
                    return trim($content);
                }
            }

            Log::error('Chat Error: ', ['body' => $response->body()]);

        } catch (Exception $e) {
            Log::error('Chat Exception: ', ['error' => $e->getMessage()]);
        }

        return "I'm sorry, I'm having trouble connecting to my medical database right now. Please consult a doctor for immediate advice.";
    }
}
