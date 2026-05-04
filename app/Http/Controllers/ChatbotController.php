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
            'message' => 'nullable|string|max:1000',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
        ]);

        if (!$request->message && !$request->hasFile('attachment')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please provide a message or an image.'
            ], 422);
        }

        $user = Auth::user();
        $userMessage = $request->input('message');
        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentType = $file->getClientMimeType();
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/chat_attachments'), $fileName);
            $attachmentPath = 'storage/chat_attachments/' . $fileName;
        }

        // Call AI Service
        $aiResponse = $this->chatbotService->getResponse($user->id, $userMessage, $attachmentPath);

        // Store standard conversation
        $chatMessage = ChatMessage::create([
            'user_id' => $user->id,
            'message' => $userMessage,
            'response' => $aiResponse,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        return response()->json([
            'status' => 'success',
            'chat_id' => $chatMessage->id,
            'user_message' => $chatMessage->message,
            'ai_response' => $chatMessage->response,
            'attachment_url' => $attachmentPath ? asset($attachmentPath) : null,
            'created_at' => $chatMessage->created_at->format('M d, h:i A')
        ]);
    }
}
