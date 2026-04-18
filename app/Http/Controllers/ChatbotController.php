<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Services\ChatbotService;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Render the chat interface.
     */
    public function index()
    {
        $user = Auth::user();

        // Load paginated chat history (oldest first for scrolling)
        $messages = ChatMessage::where('user_id', $user->id)
            ->orderBy('id', 'asc')
            ->get();

        return view('patient.ai-chat', compact('messages'));
    }

    /**
     * Handle incoming AJAX message.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $userMessage = $request->input('message');

        // Throttle/Rate limit check logic can be added in routes or via rate limiter
        // Let's rely on standard Laravel rate limiter defined in routes if needed

        // Call AI Service
        $aiResponse = $this->chatbotService->getResponse($user->id, $userMessage);

        // Store standard conversation
        $chatMessage = ChatMessage::create([
            'user_id' => $user->id,
            'message' => $userMessage,
            'response' => $aiResponse,
        ]);

        return response()->json([
            'status' => 'success',
            'chat_id' => $chatMessage->id,
            'user_message' => $chatMessage->message,
            'ai_response' => $chatMessage->response,
            'created_at' => $chatMessage->created_at->format('M d, h:i A')
        ]);
    }
}
