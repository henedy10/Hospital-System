@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('content')
    <div class="page-header">
        <h1>My Profile</h1>
        <p class="text-muted">Manage your personal information and health preferences.</p>
    </div>

    <div class="profile-container">
        <div class="profile-header-card">
            <div class="profile-cover"></div>
            <div class="profile-user-block">
                <div class="avatar-wrapper">
                    <img id="avatar-preview"
                        src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D9488&color=fff&size=128' }}"
                        alt="Profile" class="profile-avatar">
                    <button type="button" class="edit-avatar"
                        onclick="document.getElementById('profile_image_input').click()"><i
                            class="fas fa-camera"></i></button>
                    <input type="file" id="profile_image_input" name="profile_image" form="profile-update-form"
                        class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>
                @error('profile_image', 'profileUpdate') <span
                class="invalid-feedback block mt-1">{{ $message }}</span>@enderror
                <div class="user-meta">
                    <h2>{{ $user->name }}</h2>
                    <p>Patient ID: {{ $user->patient_id ?? 'N/A' }}</p>
                    <span class="user-status {{ $user->is_verified ? 'active' : 'pending' }}">
                        {{ $user->is_verified ? 'Verified Account' : 'Pending Verification' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="profile-grid">
            <div class="profile-main">

                <div class="form-card">
                    <div class="card-title">
                        <i class="fas fa-user"></i>
                        <h3>Personal Information</h3>
                    </div>
                    <form action="{{ route('patient.profile.update') }}" method="POST" enctype="multipart/form-data"
                        id="profile-update-form">
                        @csrf
                        {{-- @if ($errors->profileUpdate->any())
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Please correct the errors in the form below.</span>
                        </div>
                        @endif --}}
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="form-control @error('name', 'profileUpdate') is-invalid @enderror">
                                @error('name', 'profileUpdate') <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="form-control @error('email', 'profileUpdate') is-invalid @enderror">
                                @error('email', 'profileUpdate') <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="form-control @error('phone', 'profileUpdate') is-invalid @enderror">
                                @error('phone', 'profileUpdate') <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" name="dob" value="{{ old('dob', $user->dob) }}"
                                    class="form-control @error('dob', 'profileUpdate') is-invalid @enderror">
                                @error('dob', 'profileUpdate') <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address"
                                class="form-control @error('address', 'profileUpdate') is-invalid @enderror"
                                rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address', 'profileUpdate') <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="card-title mt-4">
                            <i class="fas fa-heartbeat"></i>
                            <h3>Medical Information</h3>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Blood Type</label>
                                <select name="blood_type"
                                    class="form-control @error('blood_type', 'profileUpdate') is-invalid @enderror">
                                    <option value="">Select Blood Type</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                        <option value="{{ $type }}" {{ old('blood_type', $user->blood_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('blood_type', 'profileUpdate') <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Allergies</label>
                                <input type="text" name="allergies" value="{{ old('allergies', $user->allergies) }}"
                                    class="form-control @error('allergies', 'profileUpdate') is-invalid @enderror"
                                    placeholder="e.g. Peanuts, Penicillin">
                                @error('allergies', 'profileUpdate') <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-title mt-4">
                            <i class="fas fa-phone-alt"></i>
                            <h3>Emergency Contact</h3>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Contact Name</label>
                                <input type="text" name="emergency_contact_name"
                                    value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}"
                                    class="form-control @error('emergency_contact_name', 'profileUpdate') is-invalid @enderror">
                                @error('emergency_contact_name', 'profileUpdate') <span
                                    class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Relationship</label>
                                <input type="text" name="emergency_contact_relationship"
                                    value="{{ old('emergency_contact_relationship', $user->emergency_contact_relationship) }}"
                                    class="form-control @error('emergency_contact_relationship', 'profileUpdate') is-invalid @enderror">
                                @error('emergency_contact_relationship', 'profileUpdate') <span
                                class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Contact Phone</label>
                            <input type="text" name="emergency_contact_phone"
                                value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}"
                                class="form-control @error('emergency_contact_phone', 'profileUpdate') is-invalid @enderror">
                            @error('emergency_contact_phone', 'profileUpdate') <span
                                class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="card-title mt-4">
                            <i class="fas fa-shield-alt"></i>
                            <h3>Health Insurance</h3>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Provider</label>
                                <input type="text" name="insurance_provider"
                                    value="{{ old('insurance_provider', $user->insurance_provider) }}"
                                    class="form-control @error('insurance_provider', 'profileUpdate') is-invalid @enderror">
                                @error('insurance_provider', 'profileUpdate') <span
                                    class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Plan Name</label>
                                <input type="text" name="insurance_plan"
                                    value="{{ old('insurance_plan', $user->insurance_plan) }}"
                                    class="form-control @error('insurance_plan', 'profileUpdate') is-invalid @enderror">
                                @error('insurance_plan', 'profileUpdate') <span
                                class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Member ID</label>
                            <input type="text" name="insurance_member_id"
                                value="{{ old('insurance_member_id', $user->insurance_member_id) }}"
                                class="form-control @error('insurance_member_id', 'profileUpdate') is-invalid @enderror">
                            @error('insurance_member_id', 'profileUpdate') <span
                            class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">Update Profile</button>
                        </div>
                    </form>
                </div>

                <div class="form-card mt-2">
                    <div class="card-title">
                        <i class="fas fa-lock"></i>
                        <h3>Security</h3>
                    </div>
                    <form action="{{ route('patient.profile.password') }}" method="POST">
                        @csrf
                        {{-- @if ($errors->passwordUpdate->any())
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Please correct the errors in the password form.</span>
                        </div>
                        @endif --}}
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" name="current_password"
                                class="form-control @error('current_password', 'passwordUpdate') is-invalid @enderror">
                            @error('current_password', 'passwordUpdate') <span
                            class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password', 'passwordUpdate') is-invalid @enderror">
                                @error('password', 'passwordUpdate') <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-save">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="profile-sidebar">
                <div class="sidebar-card">
                    <h3>Emergency Contact</h3>
                    @if($user->emergency_contact_name)
                        <div class="contact-box">
                            <div class="contact-info">
                                <strong>{{ $user->emergency_contact_name }}</strong>
                                <p>{{ $user->emergency_contact_relationship }}</p>
                            </div>
                            <p class="contact-phone"><i class="fas fa-phone"></i> {{ $user->emergency_contact_phone }}</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">No emergency contact set.</p>
                    @endif
                </div>

                <div class="sidebar-card">
                    <h3>Health Insurance</h3>
                    @if($user->insurance_provider)
                        <div class="insurance-card">
                            <div class="ins-header">{{ $user->insurance_provider }}</div>
                            <div class="ins-body">
                                <p><span>Member ID</span> <strong>{{ $user->insurance_member_id }}</strong></p>
                                <p><span>Plan</span> <strong>{{ $user->insurance_plan }}</strong></p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">No insurance information set.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .hidden {
            display: none;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.875rem;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .profile-header-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .profile-cover {
            height: 160px;
            background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%);
        }

        .profile-user-block {
            padding: 0 2rem 2rem 2rem;
            display: flex;
            align-items: flex-end;
            gap: 2rem;
            margin-top: -64px;
        }

        .avatar-wrapper {
            position: relative;
        }

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
            background: #0D9488;
            color: white;
            border: 2px solid white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: scale 0.2s;
        }

        .edit-avatar:hover {
            scale: 1.1;
        }

        .user-meta h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .user-meta p {
            font-size: 0.875rem;
            color: #6B7280;
            margin-bottom: 0.5rem;
        }

        .user-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .active {
            background: #ECFDF5;
            color: #059669;
        }

        .pending {
            background: #FEF3C7;
            color: #D97706;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .form-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #F3F4F6;
        }

        .card-title i {
            color: #0D9488;
            font-size: 1.125rem;
        }

        .card-title h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #0D9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .btn-save {
            background: #0D9488;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-save:hover {
            background: #0F766E;
        }

        .btn-outline {
            background: white;
            color: #374151;
            border: 1px solid #D1D5DB;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
        }

        .sidebar-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .sidebar-card h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
        }

        .contact-box {
            background: #F9FAFB;
            padding: 1rem;
            border-radius: 0.75rem;
            border: 1px solid #E5E7EB;
        }

        .contact-info strong {
            display: block;
            color: #111827;
            font-size: 0.9375rem;
        }

        .contact-info p {
            font-size: 0.8125rem;
            color: #6B7280;
        }

        .contact-phone {
            font-size: 0.875rem;
            color: #0D9488;
            font-weight: 600;
            margin-top: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .insurance-card {
            background: #1E293B;
            color: white;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .ins-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .ins-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .ins-body p {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
        }

        .ins-body span {
            color: #94A3B8;
        }

        .ins-body strong {
            color: white;
        }

        .mt-2 {
            margin-top: 2rem;
        }

        .mt-1 {
            margin-top: 1rem;
        }

        .btn-small {
            padding: 0.5rem;
            font-size: 0.75rem;
        }
    </style>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                    // Also update the sidebar avatar for a truly "realtime" feel
                    const sidebarAvatar = document.querySelector('.user-profile .avatar');
                    if (sidebarAvatar) {
                        sidebarAvatar.src = e.target.result;
                    }
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection