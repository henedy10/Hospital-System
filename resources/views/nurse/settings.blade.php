@extends('layouts.dashboard')

@section('title', 'Nurse Settings')

@section('content')
    <div class="welcome-section" style="margin-bottom: 32px;">
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Account Settings</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Manage your personal information, department details, and
            shift preferences.</p>
    </div>

    @if(session('success'))
        <div
            style="background: #f0fdf4; color: #16a34a; padding: 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #bbf7d0; font-weight: 600;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid-2-cols" style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px; align-items: flex-start;">
        <!-- Profile Card -->
        <div class="glass-card" style="padding: 32px; text-align: center;">
            <div style="position: relative; display: inline-block; margin-bottom: 24px;">
                <img src="{{ $user['avatar'] }}"
                    style="width: 140px; height: 140px; border-radius: 50%; border: 4px solid #f8fafc;" alt="Avatar">
                <button
                    style="position: absolute; bottom: 5px; right: 5px; width: 36px; height: 36px; border-radius: 50%; background: var(--primary); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-camera" style="font-size: 0.9rem;"></i>
                </button>
            </div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">
                {{ $user['name'] }}</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">{{ $user['department'] }} •
                {{ $user['employee_id'] }}</p>

            <div style="padding: 16px; background: #f8fafc; border-radius: 12px; text-align: left;">
                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">Current Shift</div>
                <div style="font-weight: 600; color: var(--text-main);">{{ $user['shift'] }}</div>
            </div>
        </div>

        <!-- Settings Tabs & Form -->
        <div class="settings-content">
            <div class="glass-card">
                <div style="display: flex; gap: 24px; border-bottom: 1px solid #e2e8f0; padding: 0 24px;">
                    <a href="#"
                        style="padding: 16px 0; border-bottom: 2px solid var(--primary); color: var(--primary); font-weight: 600; text-decoration: none;">Profile
                        Info</a>
                    <a href="#"
                        style="padding: 16px 0; color: #64748b; font-weight: 500; text-decoration: none;">Security</a>
                    <a href="#"
                        style="padding: 16px 0; color: #64748b; font-weight: 500; text-decoration: none;">Notifications</a>
                </div>

                <div style="padding: 32px;">
                    <form action="{{ route('nurse.settings.update') }}" method="POST">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Full Name</label>
                                <input type="text" name="name" value="{{ $user['name'] }}"
                                    style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none;">
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Email Address</label>
                                <input type="email" name="email" value="{{ $user['email'] }}"
                                    style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none;">
                            </div>
                        </div>

                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Shift Preference</label>
                            <select name="shift_pref"
                                style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; background: white;">
                                <option value="morning">Morning (08:00 - 16:00)</option>
                                <option value="afternoon">Afternoon (16:00 - 00:00)</option>
                                <option value="night">Night (00:00 - 08:00)</option>
                            </select>
                        </div>

                        <div
                            style="display: flex; items-center; gap: 12px; margin-bottom: 32px; padding: 16px; background: #e0f2fe; border-radius: 8px; color: #0369a1;">
                            <i class="fas fa-info-circle" style="margin-top: 3px;"></i>
                            <p style="font-size: 0.85rem; margin: 0;">Department changes must be requested through the
                                Hospital Administrator dashboard.</p>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 16px;">
                            <button type="button"
                                style="background: white; border: 1px solid #e2e8f0; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer;">Cancel</button>
                            <button type="submit"
                                style="background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer;">Save
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection