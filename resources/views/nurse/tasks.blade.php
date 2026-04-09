@extends('layouts.dashboard')

@section('title', 'Nursing Tasks')

@section('content')
<div class="tasks-page">

    {{-- ══════════════════ PAGE HEADER ══════════════════ --}}
    <div class="tasks-header">
        <div>
            <h1 class="tasks-title"><i class="fas fa-clipboard-check tasks-title-icon"></i>My Tasks</h1>
            <p class="tasks-subtitle">Your clinical responsibilities — sorted by priority, assigned by your doctors.</p>
        </div>
        <div class="tasks-date">
            <i class="far fa-calendar-alt" style="margin-right:6px;color:#0D9488;"></i>
            {{ now()->format('l, F j, Y') }}
        </div>
    </div>

    {{-- ══════════════════ STATS ROW ══════════════════ --}}
    <div class="stats-row">
        <div class="stat-pill stat-amber">
            <div class="stat-pill-icon"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <div class="stat-pill-value">{{ $stats['pending'] }}</div>
                <div class="stat-pill-label">Pending</div>
            </div>
        </div>
        <div class="stat-pill stat-red">
            <div class="stat-pill-icon"><i class="fas fa-arrow-up"></i></div>
            <div>
                <div class="stat-pill-value">{{ $stats['high'] }}</div>
                <div class="stat-pill-label">High Priority</div>
            </div>
        </div>
        <div class="stat-pill stat-rose">
            <div class="stat-pill-icon"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-pill-value">{{ $stats['overdue'] }}</div>
                <div class="stat-pill-label">Overdue</div>
            </div>
        </div>
        <div class="stat-pill stat-green">
            <div class="stat-pill-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-pill-value">{{ $stats['completed'] }}</div>
                <div class="stat-pill-label">Completed</div>
            </div>
        </div>
    </div>

    {{-- ══════════════════ FILTER BAR ══════════════════ --}}
    <div class="filter-bar glass-card">
        <form method="GET" action="{{ route('nurse.tasks') }}" class="filter-form">
            {{-- Search --}}
            <div class="search-wrap">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search tasks..." class="search-input">
            </div>

            {{-- Priority chips --}}
            <div class="chip-group">
                <span class="chip-label">Priority</span>
                @foreach(['High' => 'chip-red', 'Medium' => 'chip-amber', 'Low' => 'chip-blue'] as $p => $cls)
                    <button type="submit" name="priority" value="{{ $p }}"
                            class="chip {{ $cls }} {{ request('priority') === $p ? 'chip-active' : '' }}">
                        {{ $p }}
                    </button>
                @endforeach
            </div>

            {{-- Status --}}
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>⏳ Pending</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Completed</option>
            </select>

            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i>
            </button>

            @if(request()->hasAny(['search','priority','status']))
                <a href="{{ route('nurse.tasks') }}" class="btn-clear">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </form>
    </div>

    {{-- ══════════════════ OVERDUE ══════════════════ --}}
    @if($overdueTasks->isNotEmpty() && !request()->filled('status'))
    <section class="task-section">
        <div class="section-header">
            <span class="section-dot dot-blink"></span>
            <h2 class="section-title title-danger">Overdue</h2>
            <span class="section-badge badge-red">{{ $overdueTasks->count() }}</span>
        </div>
        <div class="task-list task-list-danger">
            @foreach($overdueTasks as $task)
                @include('nurse.partials.task-row', ['task' => $task, 'isOverdue' => true])
            @endforeach
        </div>
    </section>
    @endif

    {{-- ══════════════════ TODAY ══════════════════ --}}
    <section class="task-section">
        <div class="section-header">
            <h2 class="section-title">Today's Priorities</h2>
            <span class="section-badge badge-teal">{{ $todayTasks->count() }}</span>
        </div>

        @if($todayTasks->isEmpty())
            <div class="empty-state glass-card">
                <i class="fas fa-check-circle empty-icon" style="color:#22c55e;"></i>
                <p class="empty-text">
                    @if(request()->hasAny(['search','priority','status']))
                        No matching tasks for today.
                    @else
                        No tasks scheduled for today. Great work! 🎉
                    @endif
                </p>
            </div>
        @else
            <div class="task-list glass-card">
                @foreach($todayTasks as $task)
                    @include('nurse.partials.task-row', ['task' => $task, 'isOverdue' => false])
                @endforeach
            </div>
        @endif
    </section>

    {{-- ══════════════════ UPCOMING ══════════════════ --}}
    @if($upcomingTasks->isNotEmpty())
    <section class="task-section" style="margin-bottom:40px;">
        <div class="section-header">
            <h2 class="section-title" style="color:#64748b;">Scheduled Ahead</h2>
            <span class="section-badge badge-muted">{{ $upcomingTasks->count() }}</span>
        </div>
        <div class="upcoming-grid">
            @foreach($upcomingTasks as $task)
            @php
                $upColors = [
                    'High'   => ['icon-bg'=>'#fef2f2','icon-color'=>'#ef4444','badge-bg'=>'#fef2f2','badge-color'=>'#ef4444'],
                    'Medium' => ['icon-bg'=>'#fffbeb','icon-color'=>'#f59e0b','badge-bg'=>'#fffbeb','badge-color'=>'#f59e0b'],
                    'Low'    => ['icon-bg'=>'#eff6ff','icon-color'=>'#3b82f6','badge-bg'=>'#eff6ff','badge-color'=>'#3b82f6'],
                ];
                $uc = $upColors[$task->priority] ?? $upColors['Medium'];
            @endphp
            <div class="upcoming-card glass-card">
                <div class="upcoming-icon" style="background:{{ $uc['icon-bg'] }};color:{{ $uc['icon-color'] }};">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <div class="upcoming-body">
                    <h4 class="upcoming-title">{{ $task->title }}</h4>
                    <div class="upcoming-due">
                        <i class="far fa-clock" style="margin-right:4px;"></i>
                        {{ \Carbon\Carbon::parse($task->due_at)->format('M d · h:i A') }}
                    </div>
                    @if($task->assignedBy)
                    <div class="upcoming-doctor">
                        <i class="fas fa-user-md" style="margin-right:4px;color:#0D9488;"></i>
                        Dr. {{ $task->assignedBy->name }}
                    </div>
                    @endif
                </div>
                <span class="priority-pill" style="background:{{ $uc['badge-bg'] }};color:{{ $uc['badge-color'] }};">
                    {{ $task->priority }}
                </span>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>

{{-- ══════════════════ STYLES ══════════════════ --}}
<style>
/* Page wrapper */
.tasks-page { animation: fadeInUp 0.6s ease-out; }

/* Header */
.tasks-header {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-bottom: 28px;
}
.tasks-title {
    font-size: 1.9rem; font-weight: 800; color: #0f172a;
    margin: 0 0 6px; letter-spacing: -0.03em;
}
.tasks-title-icon { color: #0D9488; margin-right: 10px; font-size: 1.6rem; }
.tasks-subtitle { color: #64748b; font-size: 0.92rem; font-weight: 500; margin: 0; }
.tasks-date {
    font-size: 0.82rem; font-weight: 700; color: #64748b;
    background: white; border: 1.5px solid #e2e8f0;
    padding: 8px 16px; border-radius: 20px;
}

/* Stats row */
.stats-row {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 14px; margin-bottom: 22px;
}
.stat-pill {
    display: flex; align-items: center; gap: 14px;
    padding: 16px 20px; border-radius: 18px; border: 1.5px solid transparent;
    background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: transform 0.2s, box-shadow 0.2s;
}
.stat-pill:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
.stat-pill-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.stat-pill-value { font-size: 1.7rem; font-weight: 800; color: #0f172a; line-height: 1; }
.stat-pill-label { font-size: 0.73rem; font-weight: 700; color: #94a3b8; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.04em; }

.stat-amber  { border-color: #fde68a; }
.stat-amber  .stat-pill-icon { background: #fffbeb; color: #f59e0b; }
.stat-red    { border-color: #fecaca; }
.stat-red    .stat-pill-icon { background: #fef2f2; color: #ef4444; }
.stat-rose   { border-color: #fecaca; }
.stat-rose   .stat-pill-icon { background: #fff1f2; color: #f43f5e; }
.stat-green  { border-color: #bbf7d0; }
.stat-green  .stat-pill-icon { background: #f0fdf4; color: #22c55e; }

/* Filter bar */
.filter-bar { padding: 14px 20px; margin-bottom: 24px; }
.filter-form {
    display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
}
.search-wrap { position: relative; flex: 1; min-width: 180px; }
.search-icon {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 0.82rem;
}
.search-input {
    width: 100%; padding: 9px 12px 9px 34px;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    font-size: 0.88rem; outline: none; box-sizing: border-box;
    transition: border 0.2s; font-family: inherit;
}
.search-input:focus { border-color: #0D9488; box-shadow: 0 0 0 3px rgba(13,148,136,0.1); }
.chip-group { display: flex; gap: 6px; align-items: center; }
.chip-label {
    font-size: 0.73rem; font-weight: 700; color: #94a3b8;
    text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;
}
.chip {
    padding: 5px 12px; border-radius: 8px; border: 1.5px solid #e2e8f0;
    background: white; color: #64748b; font-size: 0.78rem; font-weight: 700;
    cursor: pointer; transition: all 0.15s; font-family: inherit;
}
.chip-red.chip-active    { background: #fef2f2; border-color: #fca5a5; color: #ef4444; }
.chip-amber.chip-active  { background: #fffbeb; border-color: #fde68a; color: #f59e0b; }
.chip-blue.chip-active   { background: #eff6ff; border-color: #bfdbfe; color: #3b82f6; }
.chip:hover { border-color: #94a3b8; }
.filter-select {
    padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 10px;
    font-size: 0.88rem; font-weight: 600; color: #475569;
    background: white; cursor: pointer; outline: none;
    transition: border 0.2s; font-family: inherit;
}
.filter-select:focus { border-color: #0D9488; }
.btn-filter {
    padding: 9px 14px; background: #0D9488; color: white;
    border: none; border-radius: 10px; cursor: pointer;
    font-size: 0.88rem; transition: all 0.2s;
}
.btn-filter:hover { background: #0f766e; }
.btn-clear {
    padding: 9px 14px; color: #64748b; font-weight: 600;
    font-size: 0.88rem; text-decoration: none;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    transition: all 0.2s; display: flex; align-items: center; gap: 5px;
    white-space: nowrap;
}
.btn-clear:hover { border-color: #94a3b8; color: #475569; }

/* Section headers */
.task-section { margin-bottom: 28px; }
.section-header { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
.section-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #ef4444; flex-shrink: 0;
}
.dot-blink { animation: dotPulse 1.8s infinite; }
.section-title { font-size: 1.02rem; font-weight: 800; color: #0f172a; margin: 0; }
.title-danger { color: #dc2626; }
.section-badge {
    padding: 2px 10px; border-radius: 20px;
    font-size: 0.72rem; font-weight: 800;
}
.badge-teal   { background: #0D9488; color: white; }
.badge-red    { background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; }
.badge-muted  { background: #f1f5f9; color: #64748b; }

/* Task lists */
.task-list { padding: 0; border-radius: 20px; overflow: hidden; border: 1px solid #f1f5f9; }
.task-list-danger { border-color: #fecaca !important; border-width: 1.5px; }

/* Empty state */
.empty-state {
    padding: 36px; text-align: center;
    border-radius: 20px;
}
.empty-icon { font-size: 2.2rem; display: block; margin-bottom: 12px; }
.empty-text { color: #64748b; font-size: 0.9rem; font-weight: 600; margin: 0; }

/* Upcoming grid */
.upcoming-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.upcoming-card {
    display: flex; gap: 14px; align-items: flex-start;
    padding: 18px; border-radius: 18px;
    border: 1px solid #f1f5f9; transition: all 0.2s;
}
.upcoming-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); border-color: #e2e8f0; }
.upcoming-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.upcoming-body { flex: 1; min-width: 0; }
.upcoming-title {
    margin: 0 0 4px; font-size: 0.9rem; font-weight: 700; color: #0f172a;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.upcoming-due { font-size: 0.77rem; color: #94a3b8; margin-bottom: 5px; }
.upcoming-doctor { font-size: 0.75rem; color: #64748b; font-weight: 600; }
.priority-pill {
    padding: 3px 9px; border-radius: 8px;
    font-size: 0.72rem; font-weight: 800; flex-shrink: 0;
}

/* Animations */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes dotPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.4; transform: scale(1.4); }
}

/* Responsive */
@media (max-width: 900px) {
    .stats-row { grid-template-columns: 1fr 1fr; }
    .upcoming-grid { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .tasks-header { flex-direction: column; align-items: flex-start; gap: 10px; }
    .stats-row { grid-template-columns: 1fr 1fr; }
    .chip-group { flex-wrap: wrap; }
}
</style>

{{-- ══════════════════ SCRIPTS ══════════════════ --}}
<script>
const CSRF = '{{ csrf_token() }}';

function toggleTask(taskId, checkbox) {
    const row         = document.getElementById('task-row-' + taskId);
    const title       = document.getElementById('task-title-' + taskId);
    const statusBadge = document.getElementById('task-status-' + taskId);
    const checkmark   = document.getElementById('cb-checkmark-' + taskId);

    checkbox.disabled = true;

    fetch(`/nurse/tasks/${taskId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        const done = data.status === 'completed';
        checkbox.checked  = done;
        checkbox.disabled = false;

        if (title) {
            title.style.textDecoration = done ? 'line-through' : 'none';
            title.style.color          = done ? '#94a3b8'      : '#0f172a';
        }
        if (row)       row.style.opacity = done ? '0.55' : '1';
        if (checkmark) {
            checkmark.style.background   = done ? '#0D9488' : 'white';
            checkmark.style.borderColor  = done ? '#0D9488' : '#e2e8f0';
            checkmark.style.boxShadow    = done ? '0 4px 10px -2px rgba(13,148,136,0.35)' : 'none';
            checkmark.innerHTML          = done ? '<i class="fas fa-check" style="color:white;font-size:0.65rem;"></i>' : '';
        }
        if (statusBadge) {
            statusBadge.innerHTML        = done
                ? '<i class="fas fa-check-circle"></i> Done'
                : '<i class="fas fa-hourglass-half"></i> Pending';
            statusBadge.style.background = done ? '#f0fdf4' : '#fffbeb';
            statusBadge.style.color      = done ? '#22c55e' : '#f59e0b';
        }
    })
    .catch(() => { checkbox.disabled = false; });
}
</script>
@endsection
