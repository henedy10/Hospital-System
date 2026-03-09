@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content')
    <div style="max-width: 680px; margin: 0 auto;">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 28px;">
            <a href="{{ route('admin.users.index') }}"
                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #f1f5f9; border-radius: 8px; color: var(--text-muted); text-decoration: none; transition: background 0.15s;"
                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div style="display: flex; align-items: center; gap: 12px;">
                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D9488&color=fff&size=48' }}"
                    alt=""
                    style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 2px;">Edit
                        {{ $user->name }}</h1>
                    <p style="color: var(--text-muted); font-size: 0.875rem;">
                        @php
                            $roleColors = ['admin' => '#d97706', 'doctor' => '#7c3aed', 'nurse' => '#be185d', 'patient' => '#1d4ed8'];
                            $rc = $roleColors[$user->role] ?? '#475569';
                        @endphp
                        <span style="font-weight: 600; color: {{ $rc }};">{{ ucfirst($user->role) }}</span>
                        · Joined {{ $user->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="glass-card" style="padding: 32px;">
            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom: 24px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Please fix the errors below.</span>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf @method('PUT')

                {{-- Name & Email --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label
                            style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">
                            Full Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            style="width: 100%; padding: 10px 14px; border: 1.5px solid {{ $errors->has('name') ? '#ef4444' : '#e2e8f0' }}; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.15s; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#6366f1'"
                            onblur="this.style.borderColor='{{ $errors->has('name') ? '#ef4444' : '#e2e8f0' }}'">
                        @error('name') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">
                            Email <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            style="width: 100%; padding: 10px 14px; border: 1.5px solid {{ $errors->has('email') ? '#ef4444' : '#e2e8f0' }}; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.15s; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#6366f1'"
                            onblur="this.style.borderColor='{{ $errors->has('email') ? '#ef4444' : '#e2e8f0' }}'">
                        @error('email') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Phone & Specialty --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label
                            style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1 555 0100"
                            style="width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.15s; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    @if($user->role === 'doctor')
                        <div>
                            <label
                                style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">Specialty</label>
                            <input type="text" name="specialty"
                                value="{{ old('specialty', optional($user->doctor)->specialty) }}" placeholder="e.g. Cardiology"
                                style="width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.15s; box-sizing: border-box;"
                                onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>
                    @endif
                </div>

                {{-- Password Reset Section --}}
                <div
                    style="border: 1.5px dashed #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 28px; background: #fafbfc;">
                    <p style="font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 12px;">
                        <i class="fas fa-lock" style="margin-right: 6px; color: #6366f1;"></i> Change Password
                        <span style="font-weight: 400; color: var(--text-muted); margin-left: 6px;">(leave blank to keep
                            current)</span>
                    </p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <input type="password" name="password" placeholder="New password"
                                style="width: 100%; padding: 10px 14px; border: 1.5px solid {{ $errors->has('password') ? '#ef4444' : '#e2e8f0' }}; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.15s; box-sizing: border-box;"
                                onfocus="this.style.borderColor='#6366f1'"
                                onblur="this.style.borderColor='{{ $errors->has('password') ? '#ef4444' : '#e2e8f0' }}'">
                            @error('password') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}
                            </p> @enderror
                        </div>
                        <div>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password"
                                style="width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.15s; box-sizing: border-box;"
                                onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('admin.users.index') }}"
                        style="padding: 11px 24px; border: 1.5px solid #e2e8f0; border-radius: 10px; color: var(--text-muted); text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: background 0.15s;"
                        onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background=''">
                        Cancel
                    </a>
                    <button type="submit"
                        style="padding: 11px 28px; background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border: none; border-radius: 10px; font-weight: 600; font-size: 0.875rem; cursor: pointer; box-shadow: 0 4px 14px rgba(99,102,241,0.35); transition: transform 0.15s, box-shadow 0.15s;"
                        onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(99,102,241,0.45)'"
                        onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(99,102,241,0.35)'">
                        <i class="fas fa-save" style="margin-right: 6px;"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection