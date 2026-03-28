<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
#[Title('Messages')]
class Chat extends Component
{
    use WithFileUploads;

    public $message = '';
    public $selectedUserId = null;
    public $search = '';
    public $attachment;
    public $audioData; // Base64 audio string

    public function render()
    {
        $query = User::where('id', '!=', Auth::id());
        
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        
        $users = $query->get();
        $messages = [];
        $selectedUser = null;

        if ($this->selectedUserId) {
            $selectedUser = User::find($this->selectedUserId);
            if ($selectedUser) {
                $messages = Message::where(function($q) use ($selectedUser) {
                    $q->where('sender_id', Auth::id())
                      ->where('receiver_id', $selectedUser->id);
                })->orWhere(function($q) use ($selectedUser) {
                    $q->where('sender_id', $selectedUser->id)
                      ->where('receiver_id', Auth::id());
                })->orderBy('created_at', 'asc')->get();
                
                // Mark unread messages as read
                Message::where('sender_id', $selectedUser->id)
                    ->where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        }

        return view('livewire.chat', [
            'users' => $users,
            'messages' => $messages,
            'selectedUser' => $selectedUser
        ]);
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
    }
    
    public function removeAttachment()
    {
        $this->attachment = null;
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240', // 10MB Max
        ]);

        if (!$this->selectedUserId) {
            return;
        }

        if (empty($this->message) && !$this->attachment && empty($this->audioData)) {
            return; // Don't send empty messages
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('chat_attachments', 'public');
            $mimeType = $this->attachment->getMimeType();
            $attachmentType = str_starts_with($mimeType, 'image/') ? 'image' : 'file';
        } elseif ($this->audioData) {
            // Handle Base64 Audio
            $audioParts = explode(';base64,', $this->audioData);
            if (count($audioParts) == 2) {
                $audioFile = base64_decode($audioParts[1]);
                $fileName = 'chat_attachments/audio_' . time() . '_' . uniqid() . '.webm';
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $audioFile);
                $attachmentPath = $fileName;
                $attachmentType = 'audio';
            }
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedUserId,
            'message' => $this->message,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        $this->reset(['message', 'attachment', 'audioData']);
        $this->dispatch('message-sent');
    }
}
