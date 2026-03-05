@extends('layouts.dashboard')

@section('title', 'Nursing Tasks')

@section('content')
    <div class="welcome-section" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">National Nursing Care Checklist</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Manage your clinical and administrative tasks for the current shift.</p>
        </div>
        <div style="display: flex; gap: 12px;">
             <button class="btn" style="background: white; border: 1px solid #e2e8f0; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn btn-primary" style="background: var(--primary); color: white; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600;">
                <i class="fas fa-plus"></i> Add Quick Task
            </button>
        </div>
    </div>

    <div class="grid-2-cols" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <div class="tasks-container">
            <div class="glass-card" style="margin-bottom: 24px;">
                <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 1.1rem; font-weight: 700;">Remaining Tasks Today</h2>
                    <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">{{ count(array_filter($tasks['today'], function($t){ return $t['status'] != 'completed'; })) }} Pending</span>
                </div>
                <div style="padding: 8px 0;">
                    @foreach($tasks['today'] as $task)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: {{ $loop->last ? 'none' : '1px solid #f1f5f9' }}; {{ $task['status'] == 'completed' ? 'opacity: 0.6;' : '' }}">
                            <div style="display: flex; gap: 16px; align-items: center;">
                                <div style="width: 24px; height: 24px; border-radius: 6px; border: 2px solid {{ $task['status'] == 'completed' ? 'var(--primary)' : '#cbd5e1' }}; display: flex; align-items: center; justify-content: center; background: {{ $task['status'] == 'completed' ? 'var(--primary)' : 'transparent' }}; color: white;">
                                    @if($task['status'] == 'completed') <i class="fas fa-check" style="font-size: 0.7rem;"></i> @endif
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-main); {{ $task['status'] == 'completed' ? 'text-decoration: line-through;' : '' }}">{{ $task['title'] }}</div>
                                    <div style="font-size: 0.8rem; color: #64748b;">{{ $task['time'] }} • <span style="color: {{ $task['category'] == 'Clinical' ? '#0ea5e9' : '#64748b' }}">{{ $task['category'] }}</span></div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 12px; align-items: center;">
                                @if($task['status'] == 'pending')
                                    <span style="padding: 4px 8px; background: #fffbeb; color: #b45309; border-radius: 4px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;">Due Now</span>
                                @endif
                                <button style="background: transparent; border: none; color: #94a3b8; cursor: pointer; font-size: 1rem;"><i class="fas fa-ellipsis-v"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="glass-card">
                <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0;">
                    <h2 style="font-size: 1.1rem; font-weight: 700;">Upcoming (Next Shifts)</h2>
                </div>
                <div style="padding: 8px 0;">
                    @foreach($tasks['upcoming'] as $task)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: {{ $loop->last ? 'none' : '1px solid #f1f5f9' }};">
                            <div style="display: flex; gap: 16px; align-items: center;">
                                <div style="width: 24px; height: 24px; border-radius: 6px; border: 2px solid #cbd5e1; display: flex; align-items: center; justify-content: center; color: white;">
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-main);">{{ $task['title'] }}</div>
                                    <div style="font-size: 0.8rem; color: #64748b;">{{ $task['time'] }} • {{ $task['category'] }}</div>
                                </div>
                            </div>
                            <button style="background: transparent; border: none; color: #94a3b8; cursor: pointer; font-size: 1rem;"><i class="fas fa-ellipsis-v"></i></button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="tasks-sidebar">
            <div class="glass-card" style="padding: 24px; margin-bottom: 24px; background: linear-gradient(135deg, #0d9488, #0ea5e9); color: white;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 12px;">Productivity Tip 💡</h3>
                <p style="font-size: 0.9rem; line-height: 1.5; opacity: 0.9;">Completing medication rounds on time reduces patient anxiety and improves clinical outcomes. Try to batch your administrative tasks for the last hour of your shift.</p>
            </div>

            <div class="glass-card" style="padding: 24px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px;">Task Categories</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="display: flex; align-items: center; gap: 8px; font-size: 0.95rem;"><i class="fas fa-circle" style="font-size: 0.6rem; color: #0ea5e9;"></i> Clinical</span>
                        <span style="font-weight: 600;">12</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="display: flex; align-items: center; gap: 8px; font-size: 0.95rem;"><i class="fas fa-circle" style="font-size: 0.6rem; color: #8b5cf6;"></i> Administrative</span>
                        <span style="font-weight: 600;">5</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="display: flex; align-items: center; gap: 8px; font-size: 0.95rem;"><i class="fas fa-circle" style="font-size: 0.6rem; color: #f59e0b;"></i> General</span>
                        <span style="font-weight: 600;">3</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
