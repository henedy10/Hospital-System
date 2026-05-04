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
    public function getResponse(int $userId, ?string $message, ?string $attachmentPath = null): string
    {
        $systemPrompt = "You are a medical assistant chatbot.
Your role is to:
1. Explain medical information in simple terms.
2. Help patients understand lab results, medical images, and prescriptions.
3. Provide general health advice based on text or images provided.

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
            ->limit(5)
            ->get()
            ->reverse();

        $messages = [];

        foreach ($history as $chat) {
            $userParts = [];
            if ($chat->message) {
                $userParts[] = ['text' => $chat->message];
            }
            if ($chat->attachment_path && file_exists(public_path($chat->attachment_path))) {
                // For history, we don't necessarily need to resend the whole image if the model remembers context,
                // but Gemini context works better if history includes parts correctly.
                // However, sending many large images in history might exceed tokens. 
                // Let's just send the text from history for now to keep it lean, or just the current image.
                $userParts[] = ['text' => "[Image attached in previous message: " . basename($chat->attachment_path) . "]"];
            }

            if (!empty($userParts)) {
                $messages[] = [
                    'role' => 'user',
                    'parts' => $userParts
                ];
                $messages[] = [
                    'role' => 'model',
                    'parts' => [['text' => $chat->response]]
                ];
            }
        }

        $currentParts = [];
        if ($message) {
            $currentParts[] = ['text' => $message];
        }

        if ($attachmentPath && file_exists(public_path($attachmentPath))) {
            $mimeType = mime_content_type(public_path($attachmentPath));
            $imageData = base64_encode(file_get_contents(public_path($attachmentPath)));
            $currentParts[] = [
                'inline_data' => [
                    'mime_type' => $mimeType,
                    'data' => $imageData
                ]
            ];
        }

        if (empty($currentParts)) {
            return "Please provide a message or an image for me to analyze.";
        }

        $messages[] = [
            'role' => 'user',
            'parts' => $currentParts
        ];

        try {
            $response = Http::timeout(60)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . env('GEMINI_API_KEY'), [
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
