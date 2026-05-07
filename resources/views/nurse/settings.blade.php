@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('content')
    <div class="page-header">
        <h1>My Profile</h1>
        <p class="text-muted">Manage your professional information and security settings.</p>
    </div>



    @if ($errors->any())
        <div class="alert alert-error" style="background:#fff1f2; border-left:4px solid #e11d48; border-radius:10px; padding:14px 18px; margin-bottom:22px;">
            <div style="font-weight:600; color:#be123c; margin-bottom:6px;"><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</div>
            <ul style="margin:0; padding-left:18px; color:#9f1239; font-size:.88rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-container">
        <div class="profile-header-card">
            <div class="profile-cover"></div>
            <div class="profile-user-block">
                <div class="avatar-wrapper">
                    <img id="profile-preview"
                        src="{{ $user['avatar'] }}"
                        alt="Profile" class="profile-avatar">
                    <button type="button" class="edit-avatar"
                        onclick="document.getElementById('profile_image').click()"><i
                            class="fas fa-camera"></i></button>
                    @php
                        $authUser = Auth::user();
                    @endphp
                    @if($authUser->profile_image)
                    <form action="{{ route('nurse.settings.image.remove') }}" method="POST" style="position: absolute; bottom: -8px; left: -8px; margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-avatar btn-icon" style="width: 36px; height: 36px; border-radius: 50%; background: #EF4444; color: white; border: 2px solid white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: scale 0.2s;" title="Remove Photo" onclick="return confirm('Are you sure you want to remove your profile picture?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                    <input type="file" id="profile_image" name="profile_image" form="profile-update-form"
                        class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>
                <div class="user-meta">
                    <h2>{{ $user['name'] }}</h2>
                    <p>{{ $user['speciality'] ?? 'Nursing Professional' }} • <span class="user-status active">{{ $user['department'] }}</span></p>
                    <p style="font-size: 0.75rem; color: #94a3b8;">Employee ID: {{ $user['employee_id'] }}</p>
                </div>
            </div>
        </div>

        <div class="profile-main">
            <!-- Professional Information -->
            <div class="form-card">
                <div class="card-title">
                    <i class="fas fa-user-nurse"></i>
                    <h3>Professional Information</h3>
                </div>
                <form action="{{ route('nurse.settings.update') }}" method="POST" enctype="multipart/form-data"
                    id="profile-update-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user['name']) }}"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user['email']) }}"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user['phone']) }}"
                                class="form-control" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="form-group">
                            <label>Assigned Shift</label>
                            <select name="shift" class="form-control">
                                <option value="Morning (08:00 - 16:00)" {{ $user['shift'] == 'Morning (08:00 - 16:00)' ? 'selected' : '' }}>Morning (08:00 - 16:00)</option>
                                <option value="Afternoon (16:00 - 00:00)" {{ $user['shift'] == 'Afternoon (16:00 - 00:00)' ? 'selected' : '' }}>Afternoon (16:00 - 00:00)</option>
                                <option value="Night (00:00 - 08:00)" {{ $user['shift'] == 'Night (00:00 - 08:00)' ? 'selected' : '' }}>Night (00:00 - 08:00)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nursing Speciality</label>
                        <input type="text" name="speciality" value="{{ old('speciality', $user['speciality']) }}"
                            class="form-control" placeholder="e.g. ICU, Pediatrics, Emergency">
                    </div>

                    <div class="form-group">
                        <label>Professional Bio</label>
                        <textarea name="bio" class="form-control" rows="4" placeholder="Write a short bio about your professional background...">{{ old('bio', $user['bio']) }}</textarea>
                    </div>

                    <div style="margin-top: 1.5rem; padding: 1rem; background: #f0f9ff; border-radius: 12px; display: flex; gap: 12px; border: 1px solid #e0f2fe;">
                        <i class="fas fa-info-circle" style="color: #0ea5e9; margin-top: 2px;"></i>
                        <p style="font-size: 0.85rem; color: #0369a1; margin: 0;">Department changes and Employee ID updates must be processed by the human resources department.</p>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Update Profile</button>
                    </div>
                </form>
            </div>

            <!-- Security -->
            <div class="form-card mt-2">
                <div class="card-title">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Security</h3>
                </div>
                <form action="{{ route('nurse.settings.password') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Update Password</button>
                    </div>
                </form>
            </div>

            <!-- Notifications -->
            <div class="form-card mt-2">
                <div class="card-title">
                    <i class="fas fa-bell"></i>
                    <h3>Notifications</h3>
                </div>
                <div class="notification-settings">
                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>Email Notifications</h4>
                            <p>Receive shift updates and patient report alerts via email.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>Push Notifications</h4>
                            <p>Get real-time browser alerts for urgent tasks.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>SMS Alerts</h4>
                            <p>Important critical alerts sent directly to your phone.</p>
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

    <style>
        .hidden { display: none; }

        .page-header { margin-bottom: 2rem; }
        .page-header h1 { font-size: 1.875rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; letter-spacing: -0.02em; }
        .text-muted { color: #6B7280; }

        .profile-header-card {
            background: white;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 2rem;
        }

        .profile-cover {
            height: 180px;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        }

        .profile-user-block {
            padding: 0 2.5rem 2.5rem 2.5rem;
            display: flex;
            align-items: flex-end;
            gap: 2rem;
            margin-top: -64px;
        }

        .avatar-wrapper { position: relative; }
        .profile-avatar {
            width: 128px;
            height: 128px;
            border-radius: 1.5rem;
            border: 4px solid white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            background: white;
        }

        .edit-avatar {
            position: absolute;
            bottom: -8px;
            right: -8px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #0ea5e9;
            color: white;
            border: 2px solid white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .edit-avatar:hover { transform: scale(1.1); background: #0284c7; }

        .user-meta h2 { font-size: 1.75rem; font-weight: 800; color: #111827; margin-bottom: 0.25rem; letter-spacing: -0.01em; }
        .user-meta p { font-size: 0.95rem; color: #6B7280; font-weight: 500; }
        
        .user-status {
            display: inline-block;
            padding: 0.25rem 0.875rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        .active { background: #f0f9ff; color: #0284c7; border: 1px solid #e0f2fe; }

        .profile-main { width: 100%; max-width: 800px; margin: 0 auto; }

        .form-card {
            background: white;
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1.25rem; border-bottom: 1px solid #F3F4F6;
        }
        .card-title i { color: #0ea5e9; font-size: 1.25rem; }
        .card-title h3 { font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.625rem; }
        .form-control {
            width: 100%; padding: 0.875rem 1rem; border: 1px solid #E5E7EB; border-radius: 0.75rem; font-size: 0.95rem; transition: all 0.2s; background: #F9FAFB;
        }
        .form-control:focus { outline: none; border-color: #0ea5e9; background: white; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1); }

        .form-actions { display: flex; justify-content: flex-end; margin-top: 2rem; }
        .btn-save {
            background: #0ea5e9; color: white; border: none; padding: 0.875rem 2rem; border-radius: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.2);
        }
        .btn-save:hover { background: #0284c7; transform: translateY(-1px); box-shadow: 0 10px 15px -3px rgba(14, 165, 233, 0.3); }

        /* Notification Styles */
        .notification-settings { display: flex; flex-direction: column; gap: 1.5rem; }
        .notification-item { display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.25rem; border-bottom: 1px solid #F3F4F6; }
        .notification-item:last-child { border-bottom: none; padding-bottom: 0; }
        .notification-info h4 { font-size: 1rem; font-weight: 700; color: #111827; margin: 0 0 0.25rem 0; }
        .notification-info p { font-size: 0.875rem; color: #6B7280; margin: 0; }

        .switch { position: relative; display: inline-block; width: 48px; height: 26px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #E5E7EB; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #0ea5e9; }
        input:checked + .slider:before { transform: translateX(22px); }

        .mt-2 { margin-top: 2.5rem; }
        
        @media (max-width: 768px) {
            .profile-user-block { flex-direction: column; align-items: center; text-align: center; padding: 0 1.5rem 1.5rem 1.5rem; }
            .user-meta h2 { margin-top: 1rem; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-preview').src = e.target.result;
                    // Update sidebar avatar if it exists
                    const sidebarAvatar = document.querySelector('.sidebar-avatar');
                    if (sidebarAvatar) {
                        sidebarAvatar.src = e.target.result;
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection