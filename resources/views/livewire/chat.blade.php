<div>
    <style>
        .chat-container {
            display: flex;
            height: calc(100vh - 150px);
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .chat-sidebar {
            width: 320px;
            border-right: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            z-index: 10;
        }
        .chat-sidebar h2 {
            padding: 24px 20px 16px;
            margin: 0;
            font-size: 1.25rem;
            color: #0f172a;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        .search-box {
            padding: 0 20px 16px;
            border-bottom: 1px solid #f1f5f9;
        }
        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 36px;
            background: #f8fafc;
            border: 1px solid transparent;
            border-radius: 12px;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s ease;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="%2394a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>');
            background-repeat: no-repeat;
            background-position: 12px center;
            background-size: 16px;
        }
        .search-box input:focus {
            background: #ffffff;
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }
        .user-list {
            flex: 1;
            overflow-y: auto;
            padding: 12px 10px;
        }
        .user-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 4px;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .user-item:hover {
            background: #f8fafc;
        }
        .user-item.active {
            background: #f0fdfa;
            border-left: 4px solid #0d9488;
        }
        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #14b8a6, #0d9488);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            margin-right: 14px;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2);
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
            color: #1e293b;
            font-size: 0.95rem;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-role-chat {
            font-size: 0.8rem;
            color: #64748b;
            text-transform: capitalize;
            font-weight: 500;
        }
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
            position: relative;
        }
        .chat-main::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.4;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
            z-index: 0;
        }
        .chat-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            z-index: 10;
        }
        .chat-header h3 {
            margin: 0 0 2px 0;
            font-size: 1.1rem;
            color: #0f172a;
            font-weight: 700;
        }
        .chat-messages {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
            z-index: 1;
        }
        .message {
            max-width: 75%;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .message.sent {
            align-self: flex-end;
        }
        .message.received {
            align-self: flex-start;
        }
        .message-content {
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 0.95rem;
            line-height: 1.5;
            word-break: break-word;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .message.sent .message-content {
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }
        .message.received .message-content {
            background: #ffffff;
            color: #334155;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
        }
        .message-time {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 6px;
            display: flex;
            align-items: center;
        }
        .message.sent .message-time {
            justify-content: flex-end;
        }
        .msg-attachment {
            margin-top: 8px;
            border-radius: 12px;
            overflow: hidden;
            max-width: 280px;
            border: 2px solid rgba(255,255,255,0.1);
        }
        .message.received .msg-attachment {
            border-color: #f1f5f9;
        }
        .msg-attachment img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .msg-attachment img:hover {
            transform: scale(1.02);
        }
        .msg-file {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            color: inherit;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: background 0.2s;
        }
        .message.received .msg-file {
            background: #f8fafc;
        }
        .msg-file:hover {
            background: rgba(255,255,255,0.25);
        }
        .message.received .msg-file:hover {
            background: #f1f5f9;
        }
        
        /* Input Area Styling */
        .chat-input-wrapper {
            padding: 20px 24px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-top: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 10;
        }
        .attachment-preview {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #475569;
            align-self: flex-start;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .remove-attachment {
            color: #ef4444;
            cursor: pointer;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fee2e2;
            transition: all 0.2s;
        }
        .remove-attachment:hover {
            background: #fecaca;
        }
        .chat-input {
            display: flex;
            gap: 12px;
            align-items: center;
            background: #f8fafc;
            padding: 8px;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .chat-input:focus-within {
            border-color: #0d9488;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }
        .chat-input input[type="text"] {
            flex: 1;
            padding: 10px 16px;
            background: transparent;
            border: none;
            font-size: 0.95rem;
            color: #1e293b;
            outline: none;
        }
        .chat-input input[type="text"]::placeholder {
            color: #94a3b8;
        }
        .action-btn {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 1.1rem;
            cursor: pointer;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
            flex-shrink: 0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .action-btn:hover { 
            background: #f1f5f9; 
            color: #0d9488; 
            transform: translateY(-1px);
        }
        .action-btn.recording { 
            background: #fee2e2;
            color: #ef4444; 
            border-color: #fca5a5;
            animation: pulse 1.5s infinite; 
        }
        .send-btn {
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: white;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
            box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.3);
        }
        .send-btn:hover { 
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgba(13, 148, 136, 0.4);
        }
        .no-chat-selected {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 1.1rem;
            flex-direction: column;
            gap: 16px;
            background: #f8fafc;
            z-index: 1;
        }
        .no-chat-selected .icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #cbd5e1;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
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
                    <div class="icon-wrapper">
                        <i class="far fa-comments"></i>
                    </div>
                    <span>Select a contact to start messaging</span>
                </div>
            @endif
        </div>
    </div>
</div>
