@extends('layouts.dashboard')

@section('title', 'AI Medical Assistant')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
            <i class="fas fa-robot" style="color: var(--secondary); margin-right: 8px;"></i>
            AI Medical Assistant
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">
            Ask general questions, explain lab results, or understand your prescriptions.
        </p>
    </div>
</div>

<div class="glass-card" style="padding: 0; display: flex; flex-direction: column; height: 600px; max-height: 70vh; max-width: 900px; margin: 0 auto; overflow: hidden; border-radius: 16px;">
    
    <!-- Chat Header -->
    <div style="padding: 16px 24px; background: rgba(255, 255, 255, 0.9); border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; background: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                <i class="fas fa-user-md"></i>
            </div>
            <div>
                <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-main); line-height: 1.2;">Dr. AI</h3>
            </div>
        </div>
        <div style="font-size: 0.8rem; color: #d11212ff; background: #f9f1f1ff; padding: 4px 10px; border-radius: 12px; font-weight: 500;">
            This is an AI assistant. Not to replace a professional medical advice.
        </div>
    </div>

    <!-- Chat History -->
    <div id="chatHistory" style="flex: 1; overflow-y: auto; padding: 24px; background: #f8fafc; display: flex; flex-direction: column; gap: 20px;">
        
        <!-- Welcome Message -->
        <div style="display: flex; gap: 12px; max-width: 80%;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--secondary); display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; font-size: 0.85rem;">
                <i class="fas fa-robot"></i>
            </div>
            <div style="background: white; padding: 12px 16px; border-radius: 16px; border-top-left-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); color: var(--text-main); font-size: 0.95rem; line-height: 1.5; border: 1px solid #e2e8f0;">
                Hello! I'm your AI Medical Assistant. How can I help you today? You can ask me to explain medical terms, review lab results, or help clarify your prescriptions.
            </div>
        </div>

        @foreach($messages as $msg)
            <!-- User Message -->
            <div style="display: flex; gap: 12px; max-width: 80%; align-self: flex-end; flex-direction: row-reverse;">
                <div style="background: var(--primary); padding: 12px 16px; border-radius: 16px; border-top-right-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); color: white; font-size: 0.95rem; line-height: 1.5;">
                    @if($msg->attachment_path)
                        <div style="margin-bottom: 8px;">
                            <img src="{{ asset($msg->attachment_path) }}" alt="Attachment" style="max-width: 100%; border-radius: 8px; cursor: pointer;" onclick="window.open(this.src)">
                        </div>
                    @endif
                    @if($msg->message)
                        {{ $msg->message }}
                    @endif
                </div>
            </div>

            <!-- AI Response -->
            <div style="display: flex; gap: 12px; max-width: 80%;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--secondary); display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; font-size: 0.85rem;">
                    <i class="fas fa-robot"></i>
                </div>
                <div style="background: white; padding: 12px 16px; border-radius: 16px; border-top-left-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); color: var(--text-main); font-size: 0.95rem; line-height: 1.5; border: 1px solid #e2e8f0; white-space: pre-wrap;">{{ $msg->response }}</div>
            </div>
        @endforeach
    </div>

    <!-- Typing Indicator -->
    <div id="typingIndicator" style="display: none; padding: 12px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
        <div style="display: flex; gap: 12px; max-width: 80%; align-items: center;">
            <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--secondary); display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; font-size: 0.65rem;">
                <i class="fas fa-pen"></i>
            </div>
            <div style="color: var(--text-muted); font-size: 0.85rem; font-style: italic;" class="loading-dots">
                Dr. AI is typing
            </div>
        </div>
    </div>

    <!-- Image Preview Area -->
    <div id="imagePreviewContainer" style="display: none; padding: 12px 24px; background: white; border-top: 1px solid #e2e8f0; position: relative;">
        <div style="position: relative; display: inline-block;">
            <img id="imagePreview" src="" alt="Preview" style="max-height: 100px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <button type="button" id="removeImageBtn" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Chat Input Form -->
    <div style="padding: 16px 24px; background: white; border-top: 1px solid #e2e8f0;">
        <form action="{{ route('patient.ai-chat.send') }}" method="POST" id="aiChatForm" enctype="multipart/form-data" style="display: flex; gap: 12px; align-items: center;">
            @csrf
            <input type="file" id="attachmentInput" name="attachment" accept="image/*" style="display: none;">
            <button type="button" id="attachBtn" class="btn-light" style="border-radius: 50%; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #f1f5f9; border: none; color: #64748b;">
                <i class="fas fa-paperclip"></i>
            </button>
            <input type="text" id="messageInput" name="message" class="form-control" autocomplete="off" placeholder="Type your medical question here..." style="flex: 1; border-radius: 20px; background: #f8fafc; padding: 12px 24px;">
            <button type="submit" id="sendBtn" class="btn-primary" style="border-radius: 50%; width: 48px; height: 48px; padding: 0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-paper-plane" style="margin-left: -2px;"></i>
            </button>
        </form>
    </div>
</div>

<style>
.loading-dots::after {
    content: '';
    animation: dots 1.5s steps(4, end) infinite;
}
@keyframes dots {
    0%, 20% { content: ''; }
    40% { content: '.'; }
    60% { content: '..'; }
    80%, 100% { content: '...'; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatHistory = document.getElementById('chatHistory');
    const form = document.getElementById('aiChatForm');
    const input = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const typingIndicator = document.getElementById('typingIndicator');

    const attachmentInput = document.getElementById('attachmentInput');
    const attachBtn = document.getElementById('attachBtn');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImageBtn');

    // Scroll to bottom immediately
    chatHistory.scrollTop = chatHistory.scrollHeight;

    // Handle attachment button click
    attachBtn.addEventListener('click', () => attachmentInput.click());

    // Handle image selection
    attachmentInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block';
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Handle image removal
    removeImageBtn.addEventListener('click', function() {
        attachmentInput.value = '';
        imagePreviewContainer.style.display = 'none';
        imagePreview.src = '';
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = input.value.trim();
        const hasFile = attachmentInput.files && attachmentInput.files[0];
        
        if (!message && !hasFile) return;

        // Create FormData
        const formData = new FormData();
        if (message) formData.append('message', message);
        if (hasFile) formData.append('attachment', attachmentInput.files[0]);

        // Append user message immediately
        appendMessage(message, 'user', hasFile ? imagePreview.src : null);
        
        // Clear input and preview
        input.value = '';
        attachmentInput.value = '';
        imagePreviewContainer.style.display = 'none';
        
        input.disabled = true;
        sendBtn.disabled = true;
        attachBtn.disabled = true;
        sendBtn.style.opacity = '0.5';
        
        // Show typing indicator
        typingIndicator.style.display = 'block';
        chatHistory.scrollTop = chatHistory.scrollHeight;

        // Send AJAX request
        fetch('{{ route("patient.ai-chat.send") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if(!response.ok) {
                throw new Error("API Limit or Server Error");
            }
            return response.json();
        })
        .then(data => {
            typingIndicator.style.display = 'none';
            if(data.status === 'success') {
                appendMessage(data.ai_response, 'ai');
            }
        })
        .catch(err => {
            typingIndicator.style.display = 'none';
            appendMessage("I'm sorry, I cannot process your request right now. Please try again later.", 'ai');
        })
        .finally(() => {
            input.disabled = false;
            sendBtn.disabled = false;
            attachBtn.disabled = false;
            sendBtn.style.opacity = '1';
            input.focus();
        });
    });

    function appendMessage(text, role, imageUrl = null) {
        const div = document.createElement('div');
        
        if (role === 'user') {
            div.style.cssText = 'display: flex; gap: 12px; max-width: 80%; align-self: flex-end; flex-direction: row-reverse;';
            let content = '';
            if (imageUrl) {
                content += `<div style="margin-bottom: 8px;"><img src="${imageUrl}" style="max-width: 100%; border-radius: 8px; cursor: pointer;" onclick="window.open(this.src)"></div>`;
            }
            if (text) {
                content += `<div>${escapeHTML(text)}</div>`;
            }
            div.innerHTML = `<div style="background: var(--primary); padding: 12px 16px; border-radius: 16px; border-top-right-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); color: white; font-size: 0.95rem; line-height: 1.5; word-break: break-word;">${content}</div>`;
        } else {
            div.style.cssText = 'display: flex; gap: 12px; max-width: 80%;';
            div.innerHTML = `
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--secondary); display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; font-size: 0.85rem;">
                    <i class="fas fa-robot"></i>
                </div>
                <div style="background: white; padding: 12px 16px; border-radius: 16px; border-top-left-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); color: var(--text-main); font-size: 0.95rem; line-height: 1.5; border: 1px solid #e2e8f0; white-space: pre-wrap; word-break: break-word;">${escapeHTML(text)}</div>
            `;
        }

        chatHistory.appendChild(div);
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    function escapeHTML(str) {
        return str.replace(/[&<>'"]/g, 
            tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag])
        );
    }
});
</script>
@endsection
