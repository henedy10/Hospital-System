<div>
    <style>
        .chat-container {
            display: flex;
            height: calc(100vh - 150px);
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .chat-sidebar {
            width: 300px;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            background: #f9fafb;
        }
        .chat-sidebar h2 {
            padding: 15px;
            margin: 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 1.2rem;
            color: #374151;
            font-weight: 600;
        }
        .search-box {
            padding: 10px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .search-box input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .search-box input:focus {
            border-color: #0ea5e9;
        }
        .user-list {
            flex: 1;
            overflow-y: auto;
        }
        .user-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s;
        }
        .user-item:hover {
            background: #f3f4f6;
        }
        .user-item.active {
            background: #e0f2fe;
            border-left: 4px solid #0284c7;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
            margin-right: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .user-info-chat {
            flex: 1;
            overflow: hidden;
        }
        .user-name-chat {
            font-weight: 600;
            color: #111827;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-role-chat {
            font-size: 0.8rem;
            color: #6b7280;
            text-transform: capitalize;
        }
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
        }
        .chat-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            background: #fff;
        }
        .chat-header h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #111827;
        }
        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .message {
            max-width: 70%;
            display: flex;
            flex-direction: column;
        }
        .message.sent {
            align-self: flex-end;
        }
        .message.received {
            align-self: flex-start;
        }
        .message-content {
            padding: 10px 15px;
            border-radius: 15px;
            font-size: 0.95rem;
            line-height: 1.4;
            word-break: break-word;
        }
        .message.sent .message-content {
            background: #0ea5e9;
            color: #fff;
            border-bottom-right-radius: 4px;
        }
        .message.received .message-content {
            background: #fff;
            color: #1f2937;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
        }
        .message-time {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 4px;
        }
        .message.sent .message-time {
            text-align: right;
        }
        .msg-attachment {
            margin-top: 8px;
            border-radius: 8px;
            overflow: hidden;
            max-width: 250px;
        }
        .msg-attachment img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 6px;
        }
        .msg-file {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            background: rgba(0,0,0,0.1);
            border-radius: 6px;
            color: inherit;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .msg-file:hover {
            background: rgba(0,0,0,0.15);
        }
        
        /* Input Area Styling */
        .chat-input-wrapper {
            padding: 15px 20px;
            background: #fff;
            border-top: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .attachment-preview {
            background: #f1f5f9;
            padding: 8px 12px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: #334155;
            align-self: flex-start;
        }
        .remove-attachment {
            color: #ef4444;
            cursor: pointer;
            font-weight: bold;
        }
        .chat-input {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .chat-input input[type="text"] {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            outline: none;
            transition: border-color 0.2s;
        }
        .chat-input input[type="text"]:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.2);
        }
        .action-btn {
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.2rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        .action-btn:hover { background: #f1f5f9; color: #0ea5e9; }
        .action-btn.recording { color: #ef4444; animation: pulse 1s infinite; }
        .send-btn {
            background: #0ea5e9;
            color: white;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            flex-shrink: 0;
        }
        .send-btn:hover { background: #0284c7; }
        .no-chat-selected {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 1.1rem;
            flex-direction: column;
            gap: 10px;
        }
        .no-chat-selected i {
            font-size: 3rem;
            color: #d1d5db;
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
    </style>

    <div class="chat-container">
        <!-- Sidebar -->
        <div class="chat-sidebar">
            <h2>Contacts</h2>
            <div class="search-box">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users...">
            </div>
            
            <div class="user-list" wire:poll.10s>
                @if(count($users) > 0)
                    @foreach($users as $user)
                        <div wire:key="user-{{ $user->id }}" class="user-item {{ $selectedUser && $selectedUser->id === $user->id ? 'active' : '' }}" 
                             wire:click="selectUser({{ $user->id }})">
                            <div class="user-avatar">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}">
                                @else
                                    {{ substr($user->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="user-info-chat">
                                <div class="user-name-chat">{{ $user->name }}</div>
                                <div class="user-role-chat">{{ $user->role }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="padding: 15px; text-align: center; color: #9ca3af; font-size: 0.9rem;">No users found.</div>
                @endif
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main">
            @if($selectedUser)
                <div class="chat-header">
                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.9rem;">
                        @if($selectedUser->profile_image)
                            <img src="{{ asset('storage/' . $selectedUser->profile_image) }}" alt="{{ $selectedUser->name }}">
                        @else
                            {{ substr($selectedUser->name, 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <h3>{{ $selectedUser->name }}</h3>
                        <span style="font-size: 0.8rem; color: #6b7280; text-transform: capitalize;">{{ $selectedUser->role }}</span>
                    </div>
                </div>

                <div class="chat-messages" wire:poll.2s>
                    @if(count($messages) > 0)
                        @foreach($messages as $msg)
                            <div wire:key="message-{{ $msg->id }}" class="message {{ $msg->sender_id === Auth::id() ? 'sent' : 'received' }}">
                                <div class="message-content">
                                    @if($msg->message)
                                        <div>{{ $msg->message }}</div>
                                    @endif
                                    
                                    @if($msg->attachment_path)
                                        <div class="msg-attachment">
                                            @if($msg->attachment_type === 'image')
                                                <img src="{{ asset('storage/' . $msg->attachment_path) }}" alt="Attachment">
                                            @elseif($msg->attachment_type === 'audio')
                                                <audio controls src="{{ asset('storage/' . $msg->attachment_path) }}" style="max-width: 220px; height: 35px;"></audio>
                                            @else
                                                <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" class="msg-file">
                                                    <i class="fas fa-file-alt"></i> Download File
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="message-time">
                                    {{ $msg->created_at->format('M d, H:i') }}
                                    @if($msg->sender_id === Auth::id())
                                        @if($msg->is_read)
                                            <i class="fas fa-check-double" style="color: #38bdf8; font-size: 0.7rem; margin-left: 2px;"></i>
                                        @else
                                            <i class="fas fa-check" style="color: #94a3b8; font-size: 0.7rem; margin-left: 2px;"></i>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div style="text-align: center; color: #9ca3af; margin-top: auto; margin-bottom: auto;">
                            No messages yet. Send a message to start the conversation!
                        </div>
                    @endif
                </div>

                <div class="chat-input-wrapper">
                    @if($attachment)
                        <div class="attachment-preview">
                            <i class="fas fa-file"></i>
                            <span>{{ $attachment->getClientOriginalName() }}</span>
                            <i class="fas fa-times remove-attachment" wire:click="removeAttachment" title="Remove attachment"></i>
                        </div>
                    @endif

                    <!-- Using AlpineJS for Voice Recording State & Logic -->
                    <div class="chat-input" x-data="{ recording: false, mediaRecorder: null, audioChunks: [] }">
                        <!-- Attachment Label -->
                        <label for="chat-attachment" class="action-btn" title="Attach file or image">
                            <i class="fas fa-paperclip"></i>
                        </label>
                        <input type="file" id="chat-attachment" wire:model="attachment" style="display: none">

                        <!-- Main Message Input -->
                        <input type="text" wire:model="message" placeholder="Type a message..." autocomplete="off" wire:keydown.enter="sendMessage">
                        
                        <!-- Audio Recording Button -->
                        <button type="button" class="action-btn" :class="recording ? 'recording' : ''" title="Record Voice Message"
                            @click="
                                if(recording) {
                                    mediaRecorder.stop();
                                    recording = false;
                                } else {
                                    navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
                                        mediaRecorder = new MediaRecorder(stream);
                                        mediaRecorder.start();
                                        recording = true;
                                        
                                        mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                                        mediaRecorder.onstop = () => {
                                            let audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                                            audioChunks = [];
                                            let reader = new FileReader();
                                            reader.readAsDataURL(audioBlob);
                                            reader.onloadend = () => {
                                                $wire.audioData = reader.result;
                                                $wire.sendMessage();
                                            };
                                            stream.getTracks().forEach(t => t.stop());
                                        };
                                    }).catch(err => {
                                        alert('Microphone access denied or unavailable: ' + err.message);
                                    });
                                }
                            ">
                            <i class="fas" :class="recording ? 'fa-stop' : 'fa-microphone'"></i>
                        </button>

                        <!-- Send Button -->
                        <button type="button" class="send-btn" wire:click="sendMessage" title="Send">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            @else
                <div class="no-chat-selected">
                    <i class="far fa-comments"></i>
                    <span>Select a contact to start messaging</span>
                </div>
            @endif
        </div>
    </div>
</div>
