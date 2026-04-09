@extends('layouts.dashboard')

@section('title', 'Nurse Task Management')

@section('content')
<div style="animation: fadeIn 0.6s ease-out;">

    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 1.85rem; font-weight: 850; color: #1e293b; margin-bottom: 6px; letter-spacing: -0.02em;">
                <i class="fas fa-clipboard-list" style="color: #0D9488; margin-right: 10px;"></i>Nurse Task Management
            </h1>
            <p style="color: #64748b; font-size: 0.95rem; font-weight: 500;">Assign and track clinical tasks for your nursing team.</p>
        </div>
        <a href="{{ route('doctor.tasks.create') }}"
           style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #0D9488, #0f766e); color: white; padding: 12px 24px; border-radius: 14px; font-weight: 700; text-decoration: none; font-size: 0.95rem; box-shadow: 0 8px 20px -4px rgba(13,148,136,0.4); transition: all 0.2s;">
            <i class="fas fa-plus"></i> Assign New Task
        </a>
    </div>

    {{-- Stats Row --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px;">
        @php
        $statCards = [
            ['label' => 'Total Assigned', 'value' => $stats['total'],   'icon' => 'fa-clipboard-list', 'bg' => '#eff6ff', 'color' => '#3b82f6'],
            ['label' => 'Pending',         'value' => $stats['pending'], 'icon' => 'fa-hourglass-half', 'bg' => '#fffbeb', 'color' => '#f59e0b'],
            ['label' => 'High Priority',   'value' => $stats['high'],    'icon' => 'fa-exclamation-circle', 'bg' => '#fef2f2', 'color' => '#ef4444'],
            ['label' => 'Completed',       'value' => $stats['done'],    'icon' => 'fa-check-circle',   'bg' => '#f0fdf4', 'color' => '#22c55e'],
        ];
        @endphp
        @foreach($statCards as $card)
        <div class="glass-card" style="padding: 20px 24px; display: flex; align-items: center; gap: 16px; margin-bottom: 0;">
            <div style="width: 46px; height: 46px; border-radius: 12px; background: {{ $card['bg'] }}; color: {{ $card['color'] }}; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                <i class="fas {{ $card['icon'] }}"></i>
            </div>
            <div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #1e293b; line-height: 1;">{{ $card['value'] }}</div>
                <div style="font-size: 0.8rem; font-weight: 600; color: #94a3b8; margin-top: 2px;">{{ $card['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="glass-card" style="padding: 20px 24px; margin-bottom: 24px;">
        <form method="GET" action="{{ route('doctor.tasks.index') }}" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px; position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..." 
                    style="width: 100%; padding: 10px 12px 10px 36px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border 0.2s; box-sizing: border-box;">
            </div>
            <select name="priority" style="padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; font-weight: 600; color: #475569; background: white; cursor: pointer;">
                <option value="">All Priorities</option>
                <option value="High"   {{ request('priority') == 'High'   ? 'selected' : '' }}>🔴 High</option>
                <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>🟡 Medium</option>
                <option value="Low"    {{ request('priority') == 'Low'    ? 'selected' : '' }}>🔵 Low</option>
            </select>
            <select name="status" style="padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; font-weight: 600; color: #475569; background: white; cursor: pointer;">
                <option value="">All Statuses</option>
                <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <button type="submit" style="padding: 10px 20px; background: #0D9488; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 0.9rem;">Filter</button>
            @if(request()->hasAny(['search','priority','status']))
            <a href="{{ route('doctor.tasks.index') }}" style="padding: 10px 16px; color: #64748b; font-weight: 600; font-size: 0.9rem; text-decoration: none; border: 1.5px solid #e2e8f0; border-radius: 10px;">Clear</a>
            @endif
        </form>
    </div>

    {{-- Tasks Table --}}
    <div class="glass-card" style="padding: 0; border-radius: 20px; overflow: hidden;">
        @if($tasks->isEmpty())
            <div style="padding: 60px; text-align: center;">
                <div style="width: 72px; height: 72px; border-radius: 20px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 2rem; color: #94a3b8;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 8px;">No tasks found</h3>
                <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 20px;">Start by assigning a new task to a nurse.</p>
                <a href="{{ route('doctor.tasks.create') }}" style="display: inline-flex; align-items: center; gap: 8px; background: #0D9488; color: white; padding: 10px 22px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-plus"></i> Assign Task
                </a>
            </div>
        @else
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 14px 20px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Task</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Nurse</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Priority</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Category</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Due Date</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                        <th style="padding: 14px 16px; text-align: center; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    @php
                        $priorityColors = [
                            'High'   => ['bg' => '#fef2f2', 'text' => '#ef4444', 'dot' => '#ef4444'],
                            'Medium' => ['bg' => '#fffbeb', 'text' => '#f59e0b', 'dot' => '#f59e0b'],
                            'Low'    => ['bg' => '#eff6ff', 'text' => '#3b82f6', 'dot' => '#3b82f6'],
                        ];
                        $pc = $priorityColors[$task->priority] ?? $priorityColors['Medium'];
                        $isOverdue = $task->status === 'pending' && \Carbon\Carbon::parse($task->due_at)->isPast();
                    @endphp
                    <tr style="border-bottom: 1px solid #f8fafc; transition: background 0.15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 16px 20px; max-width: 260px;">
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $task->title }}</div>
                            @if($task->patient)
                            <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 3px;"><i class="fas fa-user-circle" style="margin-right: 4px;"></i>{{ $task->patient->user->name ?? 'Patient #'.$task->patient_id }}</div>
                            @endif
                        </td>
                        <td style="padding: 16px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($task->nurse->name ?? 'N') }}&background=0D9488&color=fff" 
                                     style="width: 30px; height: 30px; border-radius: 50%;" alt="">
                                <span style="font-size: 0.88rem; font-weight: 600; color: #475569;">{{ $task->nurse->name ?? '—' }}</span>
                            </div>
                        </td>
                        <td style="padding: 16px;">
                            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 8px; background: {{ $pc['bg'] }}; color: {{ $pc['text'] }}; font-size: 0.78rem; font-weight: 800;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $pc['dot'] }};"></span>
                                {{ $task->priority }}
                            </span>
                        </td>
                        <td style="padding: 16px;">
                            <span style="font-size: 0.82rem; font-weight: 600; color: #64748b; padding: 3px 10px; background: #f1f5f9; border-radius: 6px;">{{ $task->category }}</span>
                        </td>
                        <td style="padding: 16px;">
                            <div style="font-size: 0.88rem; font-weight: 600; color: {{ $isOverdue ? '#ef4444' : '#475569' }};">
                                {{ \Carbon\Carbon::parse($task->due_at)->format('M d, Y') }}
                            </div>
                            <div style="font-size: 0.78rem; color: #94a3b8;">{{ \Carbon\Carbon::parse($task->due_at)->format('h:i A') }}</div>
                            @if($isOverdue)
                            <span style="font-size: 0.7rem; font-weight: 700; color: #ef4444;">OVERDUE</span>
                            @endif
                        </td>
                        <td style="padding: 16px;">
                            @if($task->status === 'completed')
                                <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 8px; background: #f0fdf4; color: #22c55e; font-size: 0.78rem; font-weight: 800;">
                                    <i class="fas fa-check-circle"></i> Completed
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 8px; background: #fffbeb; color: #f59e0b; font-size: 0.78rem; font-weight: 800;">
                                    <i class="fas fa-hourglass-half"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <form action="{{ route('doctor.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width: 32px; height: 32px; border-radius: 8px; border: none; background: #fef2f2; color: #ef4444; cursor: pointer; transition: all 0.2s;" title="Delete task">
                                    <i class="fas fa-trash-alt" style="font-size: 0.8rem;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($tasks->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
                {{ $tasks->links() }}
            </div>
            @endif
        @endif
    </div>

</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    input:focus, select:focus { border-color: #0D9488 !important; box-shadow: 0 0 0 3px rgba(13,148,136,0.1); }
</style>
@endsection
