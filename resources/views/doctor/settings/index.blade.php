@extends('layouts.dashboard')

@section('title', 'Account Settings')

@section('content')
    <div class="welcome-section" style="margin-bottom: 32px;">
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">System Settings ⚙️
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Manage your profile, security settings, and system
            preferences.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-error" style="margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass-card" style="padding: 40px;">
        <!-- Tabs Navigation -->
        <nav class="settings-tabs">
            <div class="tab-item active" onclick="switchTab('profile')">Profile</div>
            <div class="tab-item" onclick="switchTab('security')">Security & Privacy</div>
            <div class="tab-item" onclick="switchTab('notifications')">Notifications</div>
        </nav>

        <!-- Profile Tab -->
        <div id="profile" class="tab-content active">
            <div class="settings-grid">
                <div class="settings-sidebar">
                    <h3>Account Information</h3>
                    <p>Update your profile picture and professional account details.</p>
                </div>
                <div class="settings-main">


                    <div class="form-section">
                        <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 32px;">
                            @if($user->profile_image)
                                <form action="{{ route('doctor.settings.image.remove') }}" method="POST" enctype="multipart/form-data"
                                    style="display: inline-block; margin-left: 8px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-secondary"
                                        style="padding: 8px 16px; font-size: 0.85rem; color: #EF4444; border-color: #FECACA; background: #FEF2F2;"
                                        title="Remove Photo"
                                        onclick="return confirm('Are you sure you want to remove your profile picture?')">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </form>
                            @endif
                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D9488&color=fff' }}"
                            alt="Avatar" id="profile-preview"
                            style="width: 80px; height: 80px; border-radius: 20px; object-fit: cover;">
                            <form action="{{ route('doctor.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div>
                                    <input type="file" name="profile_image" id="profile_image" style="display: none;"
                                        onchange="previewImage(this)">
                                    <button type="button" class="btn-secondary"
                                        style="padding: 8px 16px; font-size: 0.85rem;"
                                        onclick="document.getElementById('profile_image').click()">
                                        <i class="fas fa-camera"></i> Change Photo
                                    </button>
                                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 8px;">JPG, GIF or
                                        PNG. Max 800 KB.</p>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div class="form-group">
                                    <label class="input-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" style="padding-left: 16px;"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="input-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" style="padding-left: 16px;"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label class="input-label">Medical Specialization</label>
                                <input type="text" name="specialist" disabled class="form-control" style="padding-left: 16px;"
                                    value="{{$user->doctor->specialty}}">
                            </div>

                            <div class="form-group">
                                <label class="input-label">Bio</label>
                                <textarea name="bio" class="form-control"
                                    style="padding-left: 16px; height: 100px; resize: none;">{{ old('bio', $user->doctor?->bio) }}</textarea>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 12px;">
                            <button type="reset" class="btn-secondary">
                                <i class="fas fa-undo"></i> Discard
                            </button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-check-circle"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Tab -->
        <div id="security" class="tab-content" style="display: none;">
            <div class="settings-grid">
                <div class="settings-sidebar">
                    <h3>Security</h3>
                    <p>Manage your password and protect your account.</p>
                </div>
                <div class="settings-main">
                    <form action="{{ route('doctor.settings.password') }}" method="POST">
                        @csrf
                        <div class="settings-card" style="margin-bottom: 24px;">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label class="input-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control"
                                    style="padding-left: 16px;" placeholder="••••••••" required>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label class="input-label">New Password</label>
                                    <input type="password" name="password" class="form-control" style="padding-left: 16px;"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label class="input-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        style="padding-left: 16px;" required>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 12px;">
                            <button type="reset" class="btn-secondary">
                                <i class="fas fa-undo"></i> Discard
                            </button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-shield-alt"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div id="notifications" class="tab-content" style="display: none;">
            <div class="settings-grid">
                <div class="settings-sidebar">
                    <h3>Notifications</h3>
                    <p>Choose how and when you would like to receive notifications from the system.</p>
                </div>
                <div class="settings-main">
                    <form action="{{ route('doctor.settings.notifications') }}" method="POST">
                        @csrf
                        @php
                            $nSettings = $user->notification_settings ?? [
                                'email' => true,
                                'sms' => false,
                                'reports' => true,
                                'appointments' => true,
                                'system' => true
                            ];
                        @endphp

                        <div class="settings-section-header"
                            style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--primary-light);">
                            <h4 style="font-size: 1rem; color: var(--primary);"><i class="fas fa-envelope-open-text"
                                    style="margin-right: 8px;"></i> Communication Channels</h4>
                        </div>
                        <div class="settings-card" style="margin-bottom: 32px;">
                            <div class="switch-wrapper">
                                <div class="switch-info">
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                                        <i class="fas fa-at" style="color: #6366f1;"></i>
                                        <h4 style="margin: 0;">Email Notifications</h4>
                                    </div>
                                    <p>Receive summaries, newsletters and important clinical updates via email.</p>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="email" {{ ($nSettings['email'] ?? false) ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="switch-wrapper">
                                <div class="switch-info">
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                                        <i class="fas fa-sms" style="color: #10b981;"></i>
                                        <h4 style="margin: 0;">SMS Notifications</h4>
                                    </div>
                                    <p>Get instant text alerts for urgent patient messages and critical cases.</p>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="sms" {{ ($nSettings['sms'] ?? false) ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="settings-section-header"
                            style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--primary-light);">
                            <h4 style="font-size: 1rem; color: var(--primary);"><i class="fas fa-bell"
                                    style="margin-right: 8px;"></i> Activity & Alerts</h4>
                        </div>
                        <div class="settings-card">
                            <div class="switch-wrapper">
                                <div class="switch-info">
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                                        <i class="fas fa-calendar-check" style="color: #f59e0b;"></i>
                                        <h4 style="margin: 0;">Appointment Reminders</h4>
                                    </div>
                                    <p>Be notified of upcoming appointments and schedule changes.</p>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="appointments" {{ ($nSettings['appointments'] ?? false) ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="switch-wrapper">
                                <div class="switch-info">
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                                        <i class="fas fa-file-medical-alt" style="color: #3b82f6;"></i>
                                        <h4 style="margin: 0;">Report Updates</h4>
                                    </div>
                                    <p>Get alerted when new medical reports are submitted or reviewed.</p>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="reports" {{ ($nSettings['reports'] ?? false) ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="switch-wrapper">
                                <div class="switch-info">
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                                        <i class="fas fa-microchip" style="color: #8b5cf6;"></i>
                                        <h4 style="margin: 0;">System Updates</h4>
                                    </div>
                                    <p>Stay informed about new features and scheduled maintenance.</p>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="system" {{ ($nSettings['system'] ?? false) ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px;">
                            <button type="reset" class="btn-secondary" style="padding: 12px 32px;">
                                <i class="fas fa-undo"></i> Discard
                            </button>
                            <button type="submit" class="btn-primary"
                                style="width: auto; padding: 12px 40px; border-radius: 12px; font-weight: 700;">
                                <i class="fas fa-save" style="margin-right: 8px;"></i> Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
            });

            // Remove active class from tabs
            document.querySelectorAll('.tab-item').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            const activeContent = document.getElementById(tabId);
            activeContent.style.display = 'block';
            setTimeout(() => activeContent.classList.add('active'), 10);

            // Add active class to clicked tab
            event.currentTarget.classList.add('active');
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <style>
        .settings-tabs {
            display: flex;
            gap: 32px;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 16px;
        }

        .tab-item {
            color: var(--text-muted);
            font-weight: 500;
            cursor: pointer;
            position: relative;
            padding-bottom: 16px;
            transition: all 0.3s ease;
        }

        .tab-item.active {
            color: var(--primary-color);
        }

        .tab-item.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-color);
        }

        .settings-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 48px;
        }

        .settings-sidebar h3 {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .settings-sidebar p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .settings-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border-color);
        }

        .switch-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .switch-wrapper:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .switch-wrapper:first-child {
            padding-top: 0;
        }

        .switch-info h4 {
            font-size: 0.95rem;
            margin-bottom: 4px;
        }

        .switch-info p {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        <style>.tab-content {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        function switchTab(tabId) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
            });

            // Remove active class from tabs
            document.querySelectorAll('.tab-item').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            const activeContent = document.getElementById(tabId);
            activeContent.style.display = 'block';
            setTimeout(() => activeContent.classList.add('active'), 10);

            // Add active class to clicked tab
            event.currentTarget.classList.add('active');
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
