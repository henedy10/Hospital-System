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
            ->take(5)
            ->get()
            ->reverse();

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        foreach ($history as $chat) {
            $messages[] = ['role' => 'user', 'content' => $chat->message];
            $messages[] = ['role' => 'assistant', 'content' => $chat->response];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        try {
            $response = Http::withToken(config('services.openai.api_key'))
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => $messages,
                    'temperature' => 0.5,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                if ($content) {
                    return trim($content);
                }
            }

            Log::error('OpenAI Chat Error: ', ['body' => $response->body()]);

        } catch (Exception $e) {
            Log::error('OpenAI Chat Exception: ', ['error' => $e->getMessage()]);
        }

        return "I'm sorry, I'm having trouble connecting to my medical database right now. Please consult a doctor for immediate advice.";
    }
}
