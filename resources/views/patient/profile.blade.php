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
                    <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=0D9488&color=fff&size=128"
                        alt="Profile" class="profile-avatar">
                    <button class="edit-avatar"><i class="fas fa-camera"></i></button>
                </div>
                <div class="user-meta">
                    <h2>Sarah Johnson</h2>
                    <p>Patient ID: #PAT-2026-8842</p>
                    <span class="user-status active">Verified Account</span>
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
                    <form action="{{ route('patient.profile.update') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" value="Sarah Johnson" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" value="sarah.j@example.com" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" value="+1 (555) 123-4567" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" value="1995-05-15" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control" rows="3">123 Health Ave, Medical District, NY 10001</textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-save">Update Profile</button>
                        </div>
                    </form>
                </div>

                <div class="form-card mt-2">
                    <div class="card-title">
                        <i class="fas fa-lock"></i>
                        <h3>Current Password</h3>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" placeholder="••••••••" class="form-control">
                    </div>
                    <div class="form-actions">
                        <button class="btn-outline">Change Password</button>
                    </div>
                </div>
            </div>

            <div class="profile-sidebar">
                <div class="sidebar-card">
                    <h3>Emergency Contact</h3>
                    <div class="contact-box">
                        <div class="contact-info">
                            <strong>Robert Johnson</strong>
                            <p>Spouse</p>
                        </div>
                        <p class="contact-phone"><i class="fas fa-phone"></i> +1 (555) 987-6543</p>
                    </div>
                    <button class="btn-block-outline btn-small mt-1">Edit Contact</button>
                </div>

                <div class="sidebar-card">
                    <h3>Health Insurance</h3>
                    <div class="insurance-card">
                        <div class="ins-header">Blue Shield Health</div>
                        <div class="ins-body">
                            <p><span>Member ID</span> <strong>BS-992031</strong></p>
                            <p><span>Plan</span> <strong>Premium Plus</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
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
@endsection