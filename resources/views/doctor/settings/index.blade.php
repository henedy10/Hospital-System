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
                        src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0ea5e9&color=fff&size=128' }}"
                        alt="Profile" class="profile-avatar">
                    <button type="button" class="edit-avatar"
                        onclick="document.getElementById('profile_image').click()"><i
                            class="fas fa-camera"></i></button>
                    @if($user->profile_image)
                    <form action="{{ route('doctor.settings.image.remove') }}" method="POST" style="position: absolute; bottom: -8px; left: -8px; margin: 0;">
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
                    <h2>{{ $user->name }}</h2>
                    <p>Specialization: <span class="user-status active">{{ $user->doctor->specialty ?? 'General' }}</span></p>
                </div>
            </div>
        </div>

        <div class="profile-main">
            <div class="form-card">
                <div class="card-title">
                    <i class="fas fa-user-md"></i>
                    <h3>Professional Information</h3>
                </div>
                <form action="{{ route('doctor.settings.update') }}" method="POST" enctype="multipart/form-data"
                    id="profile-update-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Professional Bio</label>
                        <textarea name="bio" class="form-control" rows="4" placeholder="Write a short bio about your professional background...">{{ old('bio', $user->doctor?->bio) }}</textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Update Profile</button>
                    </div>
                </form>
            </div>

            <div class="form-card mt-2">
                <div class="card-title">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Security</h3>
                </div>
                <form action="{{ route('doctor.settings.password') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .hidden { display: none; }

        .page-header { margin-bottom: 2rem; }
        .page-header h1 { font-size: 1.875rem; color: #111827; margin-bottom: 0.5rem; }

        .profile-header-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .profile-cover {
            height: 160px;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        }

        .profile-user-block {
            padding: 0 2rem 2rem 2rem;
            display: flex;
            align-items: flex-end;
            gap: 2rem;
            margin-top: -64px;
        }

        .avatar-wrapper { position: relative; }
        .profile-avatar {
            width: 128px;
            height: 128px;
            border-radius: 1rem;
            border: 4px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            object-fit: cover;
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
            transition: scale 0.2s;
        }
        .edit-avatar:hover { scale: 1.1; }

        .user-meta h2 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 0.25rem; }
        .user-meta p { font-size: 0.875rem; color: #6B7280; margin-bottom: 0.5rem; }
        
        .user-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .active { background: #f0f9ff; color: #0284c7; }

        .profile-main { width: 100%; max-width: 800px; margin: 0 auto; }

        .form-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #F3F4F6;
        }
        .card-title i { color: #0ea5e9; font-size: 1.125rem; }
        .card-title h3 { font-size: 1.125rem; font-weight: 600; color: #111827; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
        .form-control {
            width: 100%; padding: 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; font-size: 0.875rem; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1); }

        .form-actions { display: flex; justify-content: flex-end; margin-top: 1rem; }
        .btn-save {
            background: #0ea5e9; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: background 0.2s;
        }
        .btn-save:hover { background: #0284c7; }

        .mt-2 { margin-top: 2rem; }
        
        @media (max-width: 768px) {
            .profile-user-block { flex-direction: column; align-items: center; text-align: center; }
            .user-meta h2 { margin-top: 1rem; }
        }
    </style>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-preview').src = e.target.result;
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
