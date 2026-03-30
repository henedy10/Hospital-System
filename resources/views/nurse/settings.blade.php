@extends('layouts.dashboard')

@section('title', 'Nurse Settings')

@section('content')
    <div class="welcome-section" style="margin-bottom: 32px;">
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Account Settings</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Manage your personal information, security, and notification preferences.</p>
    </div>

    @if($errors->any())
        <div style="background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #fecaca; font-weight: 600;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid-2-cols" style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px; align-items: flex-start;">
        <!-- Profile Card -->
        <div class="glass-card" style="padding: 32px; text-align: center;">
            <div style="position: relative; display: inline-block; margin-bottom: 24px;">
                <img id="avatarPreview" src="{{ $user['avatar'] }}"
                    style="width: 140px; height: 140px; border-radius: 50%; border: 4px solid #f8fafc; object-fit: cover;" alt="Avatar">
                <label for="profile_image_input"
                    style="position: absolute; bottom: 5px; right: 5px; width: 36px; height: 36px; border-radius: 50%; background: var(--primary); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-camera" style="font-size: 0.9rem;"></i>
                </label>
            </div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">{{ $user['name'] }}</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">{{ $user['department'] }} • {{ $user['employee_id'] }}</p>

            <div style="padding: 16px; background: #f8fafc; border-radius: 12px; text-align: left;">
                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">Account Status</div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></div>
                    <span style="font-weight: 600; color: #059669; font-size: 0.9rem;">Active Duty</span>
                </div>
            </div>
        </div>

        <!-- Settings Content Container -->
        <div class="settings-container">
            <div class="glass-card">
                <!-- Navigation Tabs -->
                <div style="display: flex; gap: 24px; border-bottom: 1px solid #e2e8f0; padding: 0 24px;">
                    <button onclick="showTab('profile-tab')" class="tab-btn active" id="profile-tab-btn"
                        style="padding: 16px 0; border: none; background: none; border-bottom: 2px solid var(--primary); color: var(--primary); font-weight: 600; cursor: pointer; font-family: inherit;">Profile Info</button>
                    <button onclick="showTab('security-tab')" class="tab-btn" id="security-tab-btn"
                        style="padding: 16px 0; border: none; background: none; color: #64748b; font-weight: 500; cursor: pointer; font-family: inherit;">Security</button>
                    <button onclick="showTab('notifications-tab')" class="tab-btn" id="notifications-tab-btn"
                        style="padding: 16px 0; border: none; background: none; color: #64748b; font-weight: 500; cursor: pointer; font-family: inherit;">Notifications</button>
                </div>

                <!-- Profile Tab Content -->
                <div id="profile-tab" class="tab-content" style="padding: 32px;">
                    <form action="{{ route('nurse.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="profile_image_input" name="profile_image" style="display: none;" onchange="previewImage(this)">
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <div>
                                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--text-main);">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user['name']) }}" required
                                    style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; font-family: inherit;">
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--text-main);">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user['email']) }}" required
                                    style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; font-family: inherit;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <div>
                                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--text-main);">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $user['phone']) }}"
                                    placeholder="+1 (555) 000-0000"
                                    style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; font-family: inherit;">
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--text-main);">Assigned Shift</label>
                                <select name="shift"
                                    style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; background: white; font-family: inherit;">
                                    <option value="Morning (08:00 - 16:00)" {{ $user['shift'] == 'Morning (08:00 - 16:00)' ? 'selected' : '' }}>Morning (08:00 - 16:00)</option>
                                    <option value="Afternoon (16:00 - 00:00)" {{ $user['shift'] == 'Afternoon (16:00 - 00:00)' ? 'selected' : '' }}>Afternoon (16:00 - 00:00)</option>
                                    <option value="Night (00:00 - 08:00)" {{ $user['shift'] == 'Night (00:00 - 08:00)' ? 'selected' : '' }}>Night (00:00 - 08:00)</option>
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom: 32px; padding: 16px; background: #f0f9ff; border-radius: 8px; display: flex; gap: 12px;">
                            <i class="fas fa-info-circle" style="color: #0ea5e9; margin-top: 2px;"></i>
                            <p style="font-size: 0.85rem; color: #0369a1; margin: 0;">Department changes and Employee ID updates must be processed by the human resources department.</p>
                        </div>

                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit"
                                style="background: var(--primary); color: white; border: none; padding: 12px 32px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Save Profile</button>
                        </div>
                    </form>
                </div>

                <!-- Security Tab Content -->
                <div id="security-tab" class="tab-content" style="padding: 32px; display: none;">
                    <form action="{{ route('nurse.settings.password') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--text-main);">Current Password</label>
                            <input type="password" name="current_password" required
                                style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; font-family: inherit;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
                            <div>
                                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--text-main);">New Password</label>
                                <input type="password" name="new_password" required
                                    style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; font-family: inherit;">
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--text-main);">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" required
                                    style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; font-family: inherit;">
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit"
                                style="background: var(--primary); color: white; border: none; padding: 12px 32px; border-radius: 8px; font-weight: 600; cursor: pointer;">Update Password</button>
                        </div>
                    </form>
                </div>

                <!-- Notifications Tab Content -->
                <div id="notifications-tab" class="tab-content" style="padding: 32px; display: none;">
                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
                            <div>
                                <h4 style="margin: 0 0 4px 0; font-size: 1rem; color: var(--text-main);">Email Notifications</h4>
                                <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Receive shift updates and patient report alerts via email.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
                            <div>
                                <h4 style="margin: 0 0 4px 0; font-size: 1rem; color: var(--text-main);">Push Notifications</h4>
                                <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Get real-time browser alerts for urgent tasks.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="margin: 0 0 4px 0; font-size: 1rem; color: var(--text-main);">SMS Alerts</h4>
                                <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Important critical alerts sent directly to your phone.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary); }
        input:checked + .slider:before { transform: translateX(20px); }
        
        .tab-btn { transition: all 0.3s ease; }
        .tab-btn:hover { color: var(--primary); }
    </style>

    <script>
        function showTab(tabId) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.style.display = 'none');
            
            // Show the selected tab content
            document.getElementById(tabId).style.display = 'block';
            
            // Update tab button styles
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => {
                btn.style.color = '#64748b';
                btn.style.fontWeight = '500';
                btn.style.borderBottom = 'none';
            });
            
            const activeBtn = document.getElementById(tabId + '-btn');
            activeBtn.style.color = 'var(--primary)';
            activeBtn.style.fontWeight = '600';
            activeBtn.style.borderBottom = '2px solid var(--primary)';
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection