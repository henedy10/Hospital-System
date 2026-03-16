@extends('layouts.dashboard')

@section('title', 'Manage Users')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Manage Users</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Create, edit, and remove system users.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.875rem; box-shadow: 0 4px 14px rgba(99,102,241,0.35); transition: transform 0.15s, box-shadow 0.15s;"
            onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(99,102,241,0.45)'"
            onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(99,102,241,0.35)'">
            <i class="fas fa-user-plus"></i> Add Staff
        </a>
    </div>

    {{-- Role Tabs --}}
    <div class="glass-card" style="padding: 6px; margin-bottom: 24px; display: inline-flex; gap: 4px; border-radius: 12px;">
        @foreach([['all','All Users', $counts['all']], ['doctor','Doctors',$counts['doctor']], ['nurse','Nurses',$counts['nurse']], ['patient','Patients',$counts['patient']]] as [$key, $label, $count])
            <a href="{{ route('admin.users.index', ['role' => $key]) }}"
                style="padding: 8px 18px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: {{ $role === $key ? '600' : '500' }}; color: {{ $role === $key ? '#fff' : 'var(--text-muted)' }}; background: {{ $role === $key ? 'linear-gradient(135deg,#6366f1,#4f46e5)' : 'transparent' }}; transition: all 0.15s;">
                {{ $label }}
                <span style="display: inline-block; margin-left: 5px; background: {{ $role === $key ? 'rgba(255,255,255,0.25)' : '#e2e8f0' }}; color: {{ $role === $key ? '#fff' : '#475569' }}; border-radius: 999px; padding: 1px 7px; font-size: 0.7rem;">
                    {{ $count }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Users Table --}}
    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">User</th>
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Email</th>
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Phone</th>
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Role</th>
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Joined</th>
                        <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;"
                            onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                            <td style="padding: 14px 20px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="{{ $u->profile_image ? asset('storage/' . $u->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=0D9488&color=fff&size=40' }}"
                                        alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;">
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-main);">{{ $u->name }}</div>
                                        @if($u->role === 'doctor' && $u->doctor)
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $u->doctor->specialty }}</div>
                                        @elseif($u->role === 'patient' && $u->patient)
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $u->patient->patient_id }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 14px 20px; color: var(--text-muted);">{{ $u->email }}</td>
                            <td style="padding: 14px 20px; color: var(--text-muted);">{{ $u->phone ?? '—' }}</td>
                            <td style="padding: 14px 20px;">
                                @php
                                    $roleColors = [
                                        'admin'   => ['bg' => '#fef3c7', 'text' => '#d97706'],
                                        'doctor'  => ['bg' => '#ede9fe', 'text' => '#7c3aed'],
                                        'nurse'   => ['bg' => '#fce7f3', 'text' => '#be185d'],
                                        'patient' => ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                                    ];
                                    $rc = $roleColors[$u->role] ?? ['bg' => '#e5e7eb', 'text' => '#374151'];
                                @endphp
                                <span style="padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; background: {{ $rc['bg'] }}; color: {{ $rc['text'] }};">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td style="padding: 14px 20px; color: var(--text-muted);">{{ $u->created_at->format('M d, Y') }}</td>
                            <td style="padding: 14px 20px;">
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    @if ($u->role !== 'patient')
                                        <a href="{{ route('admin.users.edit', $u) }}"
                                            style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; background: #ede9fe; color: #7c3aed; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: background 0.15s;"
                                            onmouseover="this.style.background='#ddd6fe'" onmouseout="this.style.background='#ede9fe'">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endif

                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST"
                                        onsubmit="return confirm('Delete {{ addslashes($u->name) }}? This action cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; background: #fee2e2; color: #dc2626; border-radius: 8px; font-size: 0.8rem; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s;"
                                            onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 48px; text-align: center; color: var(--text-muted);">
                                <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 12px; display: block; opacity: 0.3;"></i>
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
