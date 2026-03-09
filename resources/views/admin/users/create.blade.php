@extends('layouts.dashboard')

@section('title', 'Add Staff Member')

@section('content')
    <div style="max-width: 680px; margin: 0 auto;">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 28px;">
            <a href="{{ route('admin.users.index') }}"
                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #f1f5f9; border-radius: 8px; color: var(--text-muted); text-decoration: none; transition: background 0.15s;"
                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 2px;">Add Staff
                    Member</h1>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Create a new doctor, nurse, or patient account.
                </p>
            </div>
        </div>

        <div class="glass-card" style="padding: 32px;">
            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom: 24px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Please fix the errors below.</span>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                @csrf

                {{-- Role Selector --}}
                <div style="margin-bottom: 24px;">
                    <label
                        style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 10px;">
                        Role <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="display: flex; gap: 12px;">
                        @foreach(['doctor' => ['icon' => 'fa-user-md', 'color' => '#7c3aed', 'bg' => '#ede9fe'], 'nurse' => ['icon' => 'fa-user-nurse', 'color' => '#be185d', 'bg' => '#fce7f3'], 'patient' => ['icon' => 'fa-user-injured', 'color' => '#1d4ed8', 'bg' => '#dbeafe']] as $r => $cfg)
                            <label style="flex: 1; cursor: pointer;">
                                <input type="radio" name="role" value="{{ $r }}" {{ old('role') === $r ? 'checked' : ($r === 'doctor' && !old('role') ? 'checked' : '') }} onchange="toggleSpecialty()"
                                    style="display: none;" class="role-radio" id="role_{{ $r }}">
                                <div class="role-card-label" data-role="{{ $r }}"
                                    style="padding: 16px; border: 2px solid #e2e8f0; border-radius: 12px; text-align: center; transition: all 0.2s;">
                                    <i class="fas {{ $cfg['icon'] }}"
                                        style="font-size: 1.5rem; color: {{ $cfg['color'] }}; margin-bottom: 6px; display: block;"></i>
                                    <span
                                        style="font-weight: 600; font-size: 0.875rem; color: var(--text-main);">{{ ucfirst($r) }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('role') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Name & Email --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label
                            style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">
                            Full Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Dr. Sarah Johnson"
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
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="sarah@hospital.com"
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
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+1 555 0100"
                            style="width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.15s; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div id="specialtyField">
                        <label
                            style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">Specialty</label>
                        <input type="text" name="specialty" value="{{ old('specialty') }}" placeholder="e.g. Cardiology"
                            style="width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.15s; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>

                {{-- Password --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 28px;">
                    <div>
                        <label
                            style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">
                            Password <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="password" name="password" placeholder="Min. 8 characters"
                            style="width: 100%; padding: 10px 14px; border: 1.5px solid {{ $errors->has('password') ? '#ef4444' : '#e2e8f0' }}; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.15s; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#6366f1'"
                            onblur="this.style.borderColor='{{ $errors->has('password') ? '#ef4444' : '#e2e8f0' }}'">
                        @error('password') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">
                            Confirm Password <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="password" name="password_confirmation" placeholder="Repeat password"
                            style="width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.15s; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
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
                        <i class="fas fa-user-plus" style="margin-right: 6px;"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const roleRadios = document.querySelectorAll('.role-radio');
        const roleCardLabels = document.querySelectorAll('.role-card-label');
        const specialtyField = document.getElementById('specialtyField');

        const roleColors = {
            doctor: { border: '#7c3aed', bg: '#f5f3ff' },
            nurse: { border: '#be185d', bg: '#fdf2f8' },
            patient: { border: '#1d4ed8', bg: '#eff6ff' },
        };

        function updateRoleCards() {
            roleRadios.forEach(radio => {
                const card = document.querySelector(`.role-card-label[data-role="${radio.value}"]`);
                if (radio.checked) {
                    const c = roleColors[radio.value];
                    card.style.border = `2px solid ${c.border}`;
                    card.style.background = c.bg;
                } else {
                    card.style.border = '2px solid #e2e8f0';
                    card.style.background = '';
                }
            });
        }

        function toggleSpecialty() {
            const selected = document.querySelector('.role-radio:checked')?.value;
            specialtyField.style.display = (selected === 'doctor') ? 'block' : 'none';
            updateRoleCards();
        }

        roleRadios.forEach(r => r.addEventListener('change', toggleSpecialty));

        // Init
        toggleSpecialty();
    </script>
@endsection