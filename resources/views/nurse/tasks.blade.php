@extends('layouts.dashboard')

@section('title', 'Nursing Tasks')

@section('content')
    <div class="tasks-wrapper" style="animation: fadeIn 0.8s ease-out;">
        <div class="welcome-section" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h1 style="font-size: 1.85rem; font-weight: 850; color: #1e293b; margin-bottom: 8px; letter-spacing: -0.02em;">Task Management</h1>
                <p style="color: #64748b; font-size: 1rem; font-weight: 500;">Organize and track your clinical responsibilities for the current shift.</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <button class="btn" style="background: white; border: 1px solid #e2e8f0; padding: 10px 20px; border-radius: 12px; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <i class="fas fa-filter" style="font-size: 0.8rem;"></i> Filter
                </button>
                <button class="btn btn-primary" style="background: #0D9488; color: white; padding: 10px 24px; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.2);">
                    + Create Task
                </button>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 32px;">
            <!-- Today's Tasks -->
            <section>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <h2 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0;">Today's Priorities</h2>
                    <span style="background: #0D9488; color: white; padding: 2px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">{{ count($tasks['today']) }} Tasks</span>
                </div>
                
                <div class="glass-card" style="padding: 0; border-radius: 24px; background: white; overflow: hidden; border: 1px solid #f1f5f9;">
                    @foreach($tasks['today'] as $task)
                        <div class="task-row" style="display: grid; grid-template-columns: auto 1fr auto auto auto; align-items: center; padding: 20px 28px; gap: 24px; border-bottom: 1px solid #f8fafc; transition: all 0.2s; {{ $task['status'] == 'completed' ? 'opacity: 0.6;' : '' }}">
                            <!-- Checkbox -->
                            <label class="custom-checkbox">
                                <input type="checkbox" {{ $task['status'] == 'completed' ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                            </label>
                            
                            <!-- Title & Info -->
                            <div>
                                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #1e293b; {{ $task['status'] == 'completed' ? 'text-decoration: line-through;' : '' }}">{{ $task['title'] }}</h3>
                                <div style="display: flex; gap: 12px; align-items: center; margin-top: 4px;">
                                    <span style="font-size: 0.8rem; font-weight: 600; color: #94a3b8;"><i class="far fa-clock" style="margin-right: 4px;"></i>{{ \Carbon\Carbon::parse($task['due_at'])->format('h:i A') }}</span>
                                    @if($task['patient_id'])
                                        <span style="width: 4px; height: 4px; border-radius: 50%; background: #cbd5e1;"></span>
                                        <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;"><i class="fas fa-user-circle" style="margin-right: 4px;"></i>Patient ID: #{{ $task['patient_id'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Category -->
                            <div style="padding: 4px 12px; background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 8px; font-size: 0.75rem; font-weight: 700; color: #64748b;">{{ $task['category'] }}</div>

                            <!-- Priority -->
                            <div>
                                <span style="padding: 4px 10px; background: {{ ($task['priority'] ?? 'Medium') == 'High' ? '#fef2f2' : (($task['priority'] ?? 'Medium') == 'Medium' ? '#fffbeb' : '#eff6ff') }}; color: {{ ($task['priority'] ?? 'Medium') == 'High' ? '#ef4444' : (($task['priority'] ?? 'Medium') == 'Medium' ? '#f59e0b' : '#3b82f6') }}; border-radius: 8px; font-size: 0.75rem; font-weight: 800; border: 1px solid rgba(0,0,0,0.05);">
                                    {{ $task['priority'] ?? 'Medium' }} Priority
                                </span>
                            </div>

                            <!-- Action -->
                            <button style="width: 32px; height: 32px; border-radius: 8px; border: none; background: #f8fafc; color: #94a3b8; cursor: pointer; transition: all 0.2s;">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Upcoming Tasks -->
            <section style="margin-bottom: 40px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <h2 style="font-size: 1.1rem; font-weight: 800; color: #64748b; margin: 0;">Scheduled Ahead</h2>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    @foreach($tasks['upcoming'] as $task)
                        <div class="glass-card" style="padding: 20px; border-radius: 20px; background: white; border: 1px solid #f1f5f9; display: flex; gap: 16px; align-items: center;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: #1e293b;">{{ $task['title'] }}</h4>
                                <span style="font-size: 0.8rem; font-weight: 600; color: #94a3b8;">Due {{ \Carbon\Carbon::parse($task['due_at'])->format('M d, h:i A') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <style>
        .task-row:hover {
            background-color: #fafafa !important;
        }
        
        /* Custom Checkbox Style */
        .custom-checkbox {
            position: relative;
            padding-left: 28px;
            cursor: pointer;
            font-size: 22px;
            user-select: none;
            display: inline-block;
            height: 24px;
        }
        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 24px;
            width: 24px;
            background-color: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .custom-checkbox:hover input ~ .checkmark {
            border-color: #0D9488;
        }
        .custom-checkbox input:checked ~ .checkmark {
            background-color: #0D9488;
            border-color: #0D9488;
            box-shadow: 0 4px 10px -2px rgba(13, 148, 136, 0.4);
        }
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        .custom-checkbox input:checked ~ .checkmark:after {
            display: block;
        }
        .custom-checkbox .checkmark:after {
            left: 8px;
            top: 4px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection
