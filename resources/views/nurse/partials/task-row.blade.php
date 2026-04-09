@php
    $priorityConfig = [
        'High'   => ['bg' => '#fef2f2', 'text' => '#ef4444', 'border' => '#fecaca', 'dot' => '#ef4444'],
        'Medium' => ['bg' => '#fffbeb', 'text' => '#f59e0b', 'border' => '#fde68a', 'dot' => '#f59e0b'],
        'Low'    => ['bg' => '#eff6ff', 'text' => '#3b82f6', 'border' => '#bfdbfe', 'dot' => '#3b82f6'],
    ];
    $pc         = $priorityConfig[$task->priority] ?? $priorityConfig['Medium'];
    $isComplete = $task->status === 'completed';
@endphp

<div id="task-row-{{ $task->id }}"
     style="
        display: grid;
        grid-template-columns: 40px 1fr auto auto auto;
        align-items: center;
        padding: 18px 24px;
        gap: 18px;
        border-bottom: 1px solid #f8fafc;
        transition: background 0.15s, opacity 0.3s;
        {{ $isComplete ? 'opacity: 0.5;' : '' }}
        {{ $isOverdue  ? 'background: rgba(254,242,242,0.4);' : '' }}
     "
     onmouseover="if(!this.style.opacity || this.style.opacity=='1') this.style.background='#fafafa'"
     onmouseout="this.style.background='{{ $isOverdue ? 'rgba(254,242,242,0.4)' : 'transparent' }}'">

    {{-- ── Checkbox ── --}}
    <label style="cursor:pointer; display:flex; align-items:center; justify-content:center; width:40px; height:40px;">
        <input type="checkbox" id="cb-{{ $task->id }}"
               {{ $isComplete ? 'checked' : '' }}
               onchange="toggleTask({{ $task->id }}, this)"
               style="position:absolute; opacity:0; width:0; height:0;">
        <span id="cb-checkmark-{{ $task->id }}"
              style="
                width:24px; height:24px; border-radius:8px;
                border: 2px solid {{ $isComplete ? '#0D9488' : '#e2e8f0' }};
                background: {{ $isComplete ? '#0D9488' : 'white' }};
                box-shadow: {{ $isComplete ? '0 4px 10px -2px rgba(13,148,136,0.35)' : 'none' }};
                display:flex; align-items:center; justify-content:center;
                transition: all 0.2s; flex-shrink:0;
              ">
            @if($isComplete)
                <i class="fas fa-check" style="color:white; font-size:0.65rem;"></i>
            @endif
        </span>
    </label>

    {{-- ── Task Info ── --}}
    <div style="min-width:0;">
        <h3 id="task-title-{{ $task->id }}"
            style="
                margin: 0 0 5px;
                font-size: 0.97rem;
                font-weight: 700;
                color: {{ $isComplete ? '#94a3b8' : '#0f172a' }};
                {{ $isComplete ? 'text-decoration: line-through;' : '' }}
                white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            ">
            {{ $task->title }}
        </h3>
        <div style="display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
            {{-- Due time --}}
            <span style="font-size:0.77rem; font-weight:600; color:#94a3b8;">
                <i class="far fa-clock" style="margin-right:3px;"></i>
                {{ \Carbon\Carbon::parse($task->due_at)->format('h:i A') }}
                @if($isOverdue)
                    <span style="color:#ef4444; font-weight:800; margin-left:4px;">· OVERDUE</span>
                @endif
            </span>
            {{-- Patient --}}
            @if($task->patient && $task->patient->user)
            <span style="font-size:0.77rem; font-weight:600; color:#64748b;">
                <i class="fas fa-user-circle" style="margin-right:3px; color:#64748b;"></i>
                {{ $task->patient->user->name }}
            </span>
            @endif
            {{-- Assigned by --}}
            @if($task->assignedBy)
            <span style="font-size:0.77rem; font-weight:600; color:#0D9488;">
                <i class="fas fa-user-md" style="margin-right:3px;"></i>
                Dr. {{ $task->assignedBy->name }}
            </span>
            @endif
        </div>
        @if($task->description)
        <p style="margin:5px 0 0; font-size:0.78rem; color:#94a3b8; line-height:1.4;
                   white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:500px;">
            {{ $task->description }}
        </p>
        @endif
    </div>

    {{-- ── Category ── --}}
    <span style="
        padding: 4px 10px; background: #f8fafc;
        border: 1px solid #f1f5f9; border-radius: 8px;
        font-size: 0.72rem; font-weight: 700; color: #64748b; white-space: nowrap;
    ">{{ $task->category }}</span>

    {{-- ── Priority Badge ── --}}
    <span style="
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 11px;
        background: {{ $pc['bg'] }};
        color: {{ $pc['text'] }};
        border: 1.5px solid {{ $pc['border'] }};
        border-radius: 9px;
        font-size: 0.73rem; font-weight: 800; white-space: nowrap;
    ">
        <span style="width:5px;height:5px;border-radius:50%;background:{{ $pc['dot'] }};"></span>
        {{ $task->priority }}
    </span>

    {{-- ── Status Badge ── --}}
    <span id="task-status-{{ $task->id }}"
          style="
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 10px; border-radius: 9px;
            background: {{ $isComplete ? '#f0fdf4' : '#fffbeb' }};
            color: {{ $isComplete ? '#22c55e' : '#f59e0b' }};
            font-size: 0.73rem; font-weight: 800; white-space: nowrap;
          ">
        @if($isComplete)
            <i class="fas fa-check-circle"></i> Done
        @else
            <i class="fas fa-hourglass-half"></i> Pending
        @endif
    </span>

</div>
