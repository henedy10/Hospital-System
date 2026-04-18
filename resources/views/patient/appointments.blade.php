@extends('layouts.dashboard')

@section('title', 'My Appointments')

@section('content')
    <div class="appointments-wrapper">
        <div class="page-header animate-fade-down">
            <div class="header-content">
                <h1 class="premium-title">My Appointments</h1>
                <p class="text-muted">Manage your health journey and upcoming visits.</p>
            </div>
            <button class="btn-premium" onclick="openModal('bookModal')">
                <i class="fas fa-plus"></i> 
                <span>Book New Appointment</span>
            </button>
        </div>

        <div class="appointment-tabs animate-fade-in">
            <a href="{{ route('patient.appointments', ['status' => 'upcoming']) }}"
                class="tab-btn {{ $status === 'upcoming' ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Upcoming
            </a>
            <a href="{{ route('patient.appointments', ['status' => 'completed']) }}"
                class="tab-btn {{ $status === 'completed' ? 'active' : '' }}">
                <i class="fas fa-history"></i> Past Visits
            </a>
            <a href="{{ route('patient.appointments', ['status' => 'cancelled']) }}"
                class="tab-btn {{ $status === 'cancelled' ? 'active' : '' }}">
                <i class="fas fa-times-circle"></i> Cancelled
            </a>
        </div>

        <div class="appointments-container">
            @forelse($appointments as $index => $appointment)
                <div class="appointment-card glass-card animate-stagger" style="--order: {{ $index }}">
                    <div class="card-status-indicator {{ $appointment->status === 'upcoming' ? 'status-upcoming' : ($appointment->status === 'completed' ? 'status-completed' : 'status-cancelled') }}"></div>
                    <div class="doctor-profile">
                        <div class="avatar-wrapper">
                            <img src="{{ $appointment->doctor->user->profile_image ? asset('storage/' . $appointment->doctor->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($appointment->doctor->user->name) . '&background=0D9488&color=fff&size=200' }}"
                                alt="Doctor" class="doctor-avatar">
                            @if($appointment->status === 'upcoming')
                                <div class="online-status"></div>
                            @endif
                        </div>
                        <div class="doctor-info">
                            <h3>Dr. {{ $appointment->doctor->user->name }}</h3>
                            <span class="specialty-badge">{{ $appointment->doctor->specialty ?? 'General Practitioner' }}</span>
                        </div>
                    </div>
                    
                    <div class="appointment-details">
                        <div class="detail-row">
                            <div class="detail-item">
                                <div class="icon-box date-gradient">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="detail-text">
                                    <span class="label">Date</span>
                                    <p>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</p>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="icon-box time-gradient">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="detail-text">
                                    <span class="label">Time</span>
                                    <p>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="detail-item reason-item">
                            <div class="icon-box reason-gradient">
                                <i class="fas fa-notes-medical"></i>
                            </div>
                            <div class="detail-text">
                                <span class="label">Reason</span>
                                <p>{{ Str::limit($appointment->reason, 100) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="appointment-actions">
                        @if($appointment->status === 'upcoming')
                            <button class="btn-action-outline edit-btn" onclick="openEditModal({{ json_encode($appointment) }})">
                                <i class="fas fa-edit"></i> Reschedule
                            </button>
                            <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                @csrf
                                <button type="submit" class="btn-action-outline cancel-btn">
                                    <i class="fas fa-trash-alt"></i> Cancel
                                </button>
                            </form>
                        @elseif($appointment->status === 'completed')
                            @if($appointment->feedback)
                                {{-- Already reviewed --}}
                                <div class="existing-review-mini-wrapper">
                                    <div class="existing-review-mini">
                                        <div class="mini-stars">
                                            @for($s=1;$s<=5;$s++)
                                                <i class="fas fa-star {{ $s <= $appointment->feedback->rating ? 'star-f' : 'star-e' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="mini-review-actions">
                                            <button class="btn-review-edit"
                                                onclick="openEditReview({{ $appointment->feedback->id }}, {{ $appointment->feedback->rating }}, '{{ addslashes($appointment->feedback->comment) }}')"
                                            ><i class="fas fa-pen"></i></button>
                                            <form method="POST"
                                                  action="{{ route('patient.feedback.destroy', $appointment->feedback) }}"
                                                  onsubmit="return confirm('Delete your review?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-review-del"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    @if($appointment->feedback->doctor_reply)
                                        <div class="doctor-reply-box animate-fade-in">
                                            <div class="reply-header">
                                                <i class="fas fa-reply fa-flip-horizontal"></i>
                                                <span>Dr. {{ $appointment->doctor->user->name }}'s Response:</span>
                                            </div>
                                            <p class="reply-text">{{ $appointment->feedback->doctor_reply }}</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="review-prompt">
                                    <span class="prompt-text">How was your visit?</span>
                                    <button class="btn-leave-review"
                                        onclick="openFeedbackModal({{ $appointment->id }}, '{{ addslashes($appointment->doctor->user->name) }}')"
                                    >
                                        <i class="fas fa-star"></i> Leave a Review
                                    </button>
                                </div>
                            @endif
                        @else
                            <button class="btn-action-muted" disabled>
                                <i class="fas fa-ban"></i> Cancelled
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state glass-card animate-fade-in" style="width: 100%; grid-column: 1 / -1; text-align: center; padding: 4rem;">
                    <div class="empty-icon" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1.5rem;">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">No Appointments Found</h2>
                    <p style="color: #64748b; margin-bottom: 2rem;">It looks like you don't have any appointments in this category.</p>
                    <button class="btn-premium" onclick="openModal('bookModal')" style="margin: 0 auto;">Book Your First Visit</button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookModal" class="modal-overlay">
        <div class="modal-container glass-modal">
            <div class="modal-header">
                <div class="header-icon head-book">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="header-text">
                    <h2>Book Appointment</h2>
                    <p>Select your doctor and preferred time.</p>
                </div>
                <button class="close-modal" onclick="closeModal('bookModal')">&times;</button>
            </div>
            <form action="{{ route('patient.appointments.store') }}" method="POST" class="premium-form">
                @csrf
                <div class="form-grid">
                    <div class="input-group full-width">
                        <label><i class="fas fa-user-md"></i> Choose Specialist</label>
                        <select name="doctor_id" class="premium-select" required>
                            <option value="" disabled selected>Select a Doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">Dr. {{ $doctor->user->name }} - {{ $doctor->specialty ?? 'General' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-calendar-alt"></i> Preferred Date</label>
                        <input type="date" name="appointment_date" class="premium-input" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-clock"></i> Preferred Time</label>
                        <select name="appointment_time" class="premium-select" required>
                            <option value="" disabled selected>Select Time</option>
                            @for($i = 9; $i <= 17; $i++)
                                <option value="{{ sprintf('%02d:00', $i) }}">{{ date('h:i A', strtotime($i . ':00')) }}</option>
                                <option value="{{ sprintf('%02d:30', $i) }}">{{ date('h:i A', strtotime($i . ':30')) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="input-group full-width">
                        <label><i class="fas fa-comment-medical"></i> Reason for Visit</label>
                        <textarea name="reason" class="premium-input" rows="3" required placeholder="Describe your symptoms or reason for the visit..."></textarea>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('bookModal')">Cancel</button>
                    <button type="submit" class="btn-premium">Confirm Booking</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-container glass-modal">
            <div class="modal-header">
                <div class="header-icon head-edit">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="header-text">
                    <h2>Reschedule</h2>
                    <p>Adjust your appointment details below.</p>
                </div>
                <button class="close-modal" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form id="editForm" method="POST" class="premium-form">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="input-group full-width">
                        <label><i class="fas fa-user-md"></i> Specialist</label>
                        <select name="doctor_id" id="edit_doctor_id" class="premium-select" required>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">Dr. {{ $doctor->user->name }} - {{ $doctor->specialty ?? 'General' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-calendar-alt"></i> New Date</label>
                        <input type="date" name="appointment_date" id="edit_appointment_date" class="premium-input" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-clock"></i> New Time</label>
                        <select name="appointment_time" id="edit_appointment_time" class="premium-select" required>
                            @for($i = 9; $i <= 17; $i++)
                                <option value="{{ sprintf('%02d:00', $i) }}">{{ date('h:i A', strtotime($i . ':00')) }}</option>
                                <option value="{{ sprintf('%02d:30', $i) }}">{{ date('h:i A', strtotime($i . ':30')) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="input-group full-width">
                        <label><i class="fas fa-comment-medical"></i> Reason</label>
                        <textarea name="reason" id="edit_reason" class="premium-input" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('editModal')">Cancel</button>
                    <button type="submit" class="btn-premium">Update Appointment</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
            --secondary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f43f5e 100%);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.5);
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        .appointments-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .premium-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .btn-premium {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(13, 148, 136, 0.4);
        }

        .appointment-tabs {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 3rem;
            background: rgba(241, 245, 249, 0.5);
            padding: 0.5rem;
            border-radius: 16px;
            width: fit-content;
        }

        .tab-btn {
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            color: var(--text-muted);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-btn:hover {
            color: var(--text-main);
            background: white;
        }

        .tab-btn.active {
            background: white;
            color: #0d9488;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .appointments-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            border-color: rgba(13, 148, 136, 0.2);
        }

        .card-status-indicator {
            position: absolute;
            top: 1.5rem;
            right: 0;
            width: 4px;
            height: 32px;
            border-radius: 4px 0 0 4px;
        }

        .status-upcoming { background: #10b981; }
        .status-completed { background: #64748b; }
        .status-cancelled { background: #ef4444; }

        .doctor-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar-wrapper {
            position: relative;
        }

        .doctor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .online-status {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background: #10b981;
            border: 2px solid white;
            border-radius: 50%;
        }

        .doctor-info h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.125rem;
        }

        .specialty-badge {
            font-size: 0.75rem;
            color: #0d9488;
            background: rgba(13, 148, 136, 0.08);
            padding: 0.125rem 0.625rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .appointment-details {
            background: rgba(248, 250, 252, 0.8);
            border-radius: 14px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .icon-box {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .date-gradient { background: var(--primary-gradient); }
        .time-gradient { background: var(--secondary-gradient); }
        .reason-gradient { background: #f59e0b; }

        .detail-text .label {
            display: block;
            font-size: 0.625rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            color: var(--text-muted);
            font-weight: 700;
        }

        .detail-text p {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .appointment-actions {
            display: flex;
            gap: 0.625rem;
            margin-top: auto;
        }

        .btn-action-outline {
            flex: 1;
            padding: 0.625rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            background: white;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        .edit-btn:hover { border-color: #0d9488; color: #0d9488; background: rgba(13, 148, 136, 0.05); }
        .cancel-btn:hover { border-color: #ef4444; color: #ef4444; background: rgba(239, 68, 68, 0.05); }

        .btn-action-success {
            flex: 1;
            padding: 0.625rem;
            border-radius: 10px;
            font-weight: 600;
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            font-size: 0.875rem;
        }

        .btn-action-muted {
            flex: 1;
            padding: 0.625rem;
            border-radius: 10px;
            font-weight: 600;
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            font-size: 0.875rem;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            padding: 1rem;
            align-items: center;
            justify-content: center;
        }

        .glass-modal {
            background: white;
            border-radius: 24px;
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            animation: modalPop 0.3s ease-out;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        @keyframes modalPop {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid #f1f5f9;
            position: relative;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .head-book { background: var(--primary-gradient); }
        .head-edit { background: var(--secondary-gradient); }

        .header-text h2 { font-size: 1.25rem; font-weight: 700; color: var(--text-main); }
        .header-text p { color: var(--text-muted); font-size: 0.875rem; }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            background: #f1f5f9;
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .premium-form { padding: 1.5rem; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .full-width { grid-column: span 2; }

        .input-group label {
            display: block;
            font-size: 0.813rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.375rem;
        }

        .premium-input, .premium-select {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.625rem 0.875rem;
            border-radius: 10px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .premium-input:focus, .premium-select:focus {
            outline: none;
            border-color: #0d9488;
            background: white;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .modal-actions {
            margin-top: 1.5rem;
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: var(--text-main);
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.875rem;
        }

        /* Animations */
        @keyframes fadeDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes staggerIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .animate-fade-down { animation: fadeDown 0.4s ease-out; }
        .animate-fade-in { animation: fadeIn 0.6s ease-out; }
        .animate-stagger {
            animation: staggerIn 0.4s ease-out both;
            animation-delay: calc(var(--order) * 0.05s);
        }

        @media (max-width: 640px) {
            .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .appointments-container { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: auto; }
        }
    </style>

    {{-- ────────── Feedback Modal (new) ────────── --}}
    <div id="feedbackModal" class="modal-overlay">
        <div class="modal-container glass-modal">
            <div class="modal-header">
                <div class="header-icon head-feedback">
                    <i class="fas fa-star"></i>
                </div>
                <div class="header-text">
                    <h2>Rate Your Visit</h2>
                    <p id="feedbackDoctorName">Leave a review for your doctor.</p>
                </div>
                <button class="close-modal" onclick="closeModal('feedbackModal')">&times;</button>
            </div>
            <form action="{{ route('patient.feedback.store') }}" method="POST" class="premium-form">
                @csrf
                <input type="hidden" name="appointment_id" id="feedbackAppointmentId">
                <div class="form-grid">
                    <div class="input-group full-width">
                        <label><i class="fas fa-star"></i> Your Rating</label>
                        <div class="star-picker" id="starPicker">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn" data-value="{{ $i }}"
                                    onclick="selectStar({{ $i }})"
                                    onmouseenter="hoverStar({{ $i }})"
                                    onmouseleave="resetStarHover()">
                                    <i class="fas fa-star"></i>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="feedbackRating" value="">
                        @error('rating')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-group full-width">
                        <label><i class="fas fa-comment"></i> Comment <span style="color:#94a3b8">(optional)</span></label>
                        <textarea name="comment" class="premium-input" rows="4"
                            placeholder="Share your experience…" maxlength="1000">{{ old('comment') }}</textarea>
                        @error('comment')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('feedbackModal')">Cancel</button>
                    <button type="submit" class="btn-premium">
                        <i class="fas fa-paper-plane"></i> Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ────────── Edit Feedback Modal ────────── --}}
    <div id="editFeedbackModal" class="modal-overlay">
        <div class="modal-container glass-modal">
            <div class="modal-header">
                <div class="header-icon head-edit">
                    <i class="fas fa-pen"></i>
                </div>
                <div class="header-text">
                    <h2>Edit Your Review</h2>
                    <p>Update your rating or comment.</p>
                </div>
                <button class="close-modal" onclick="closeModal('editFeedbackModal')">&times;</button>
            </div>
            <form id="editFeedbackForm" method="POST" class="premium-form">
                @csrf @method('PUT')
                <div class="form-grid">
                    <div class="input-group full-width">
                        <label><i class="fas fa-star"></i> Your Rating</label>
                        <div class="star-picker" id="editStarPicker">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn" data-value="{{ $i }}"
                                    onclick="selectEditStar({{ $i }})"
                                    onmouseenter="hoverEditStar({{ $i }})"
                                    onmouseleave="resetEditStarHover()">
                                    <i class="fas fa-star"></i>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="editFeedbackRating" value="">
                    </div>
                    <div class="input-group full-width">
                        <label><i class="fas fa-comment"></i> Comment <span style="color:#94a3b8">(optional)</span></label>
                        <textarea name="comment" id="editFeedbackComment" class="premium-input" rows="4"
                            placeholder="Share your experience…" maxlength="1000"></textarea>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('editFeedbackModal')">Cancel</button>
                    <button type="submit" class="btn-premium">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* ── Star Picker ── */
        .star-picker { display:flex; gap:.35rem; margin:.5rem 0; }
        .star-btn {
            background:none; border:none; font-size:2rem; cursor:pointer;
            color:#e2e8f0; transition:color .15s, transform .15s;
            padding:0;
        }
        .star-btn:hover, .star-btn.hovered, .star-btn.selected { color:#f59e0b; }
        .star-btn.selected { transform:scale(1.15); }
        .field-error { color:#ef4444; font-size:.78rem; margin-top:.25rem; display:block; }
        .head-feedback { background: linear-gradient(135deg,#f59e0b,#f97316); }

        /* ── Leave review button ── */
        .btn-leave-review {
            flex:1; padding:.625rem; border-radius:10px; font-weight:600;
            background: linear-gradient(135deg,#f59e0b,#f97316);
            color:white; border:none; cursor:pointer;
            display:flex; align-items:center; justify-content:center;
            gap:.375rem; font-size:.875rem; transition:all .2s;
        }
        .btn-leave-review:hover { transform:translateY(-1px); box-shadow:0 6px 12px rgba(245,158,11,.35); }

        /* ── Existing mini review ── */
        .existing-review-mini {
            flex:1; display:flex; align-items:center;
            justify-content:space-between; gap:.5rem;
            background:#fffbeb; border:1px solid #fde68a;
            border-radius:10px; padding:.5rem .75rem;
        }
        .existing-review-mini-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .doctor-reply-box {
            background: #f0fdfa;
            border: 1px solid #99f6e4;
            border-radius: 12px;
            padding: 0.875rem;
            position: relative;
            margin-top: 0.25rem;
        }
        .doctor-reply-box::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 20px;
            width: 10px;
            height: 10px;
            background: #f0fdfa;
            border-top: 1px solid #99f6e4;
            border-left: 1px solid #99f6e4;
            transform: rotate(45deg);
        }
        .reply-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.375rem;
            color: #0d9488;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        .reply-text {
            font-size: 0.875rem;
            color: #334155;
            line-height: 1.5;
            font-style: italic;
        }
        .review-prompt {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .prompt-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            margin-left: 0.25rem;
        }
        .mini-stars { display:flex; gap:2px; }
        .star-f { color:#f59e0b; font-size:.9rem; }
        .star-e { color:#e2e8f0; font-size:.9rem; }
        .mini-review-actions { display:flex; gap:.35rem; align-items:center; }
        .btn-review-edit, .btn-review-del {
            background:none; border:1px solid #e2e8f0; border-radius:8px;
            width:28px; height:28px; display:flex; align-items:center;
            justify-content:center; cursor:pointer; font-size:.75rem;
            transition:all .2s;
        }
        .btn-review-edit { color:#0d9488; }
        .btn-review-edit:hover { background:rgba(13,148,136,.1); border-color:#0d9488; }
        .btn-review-del { color:#ef4444; }
        .btn-review-del:hover { background:rgba(239,68,68,.08); border-color:#ef4444; }
    </style>

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function openEditModal(appointment) {
            const form = document.getElementById('editForm');
            form.action = `/patient/appointments/${appointment.id}`;

            document.getElementById('edit_doctor_id').value = appointment.doctor_id;
            document.getElementById('edit_appointment_date').value = appointment.appointment_date;

            // Format time to HH:MM for select
            const time = appointment.appointment_time.substring(0, 5);
            document.getElementById('edit_appointment_time').value = time;

            document.getElementById('edit_reason').value = appointment.reason;

            openModal('editModal');
        }

        /* ── Feedback modal helpers ── */
        let currentRating = 0;
        let editRating    = 0;

        function openFeedbackModal(appointmentId, doctorName) {
            document.getElementById('feedbackAppointmentId').value = appointmentId;
            document.getElementById('feedbackDoctorName').textContent = 'Rate Dr. ' + doctorName;
            currentRating = 0;
            renderStars('starPicker', currentRating);
            document.getElementById('feedbackRating').value = '';
            openModal('feedbackModal');
        }

        function openEditReview(feedbackId, rating, comment) {
            const form = document.getElementById('editFeedbackForm');
            form.action = `/patient/feedback/${feedbackId}`;
            editRating = rating;
            renderStars('editStarPicker', editRating);
            document.getElementById('editFeedbackRating').value = editRating;
            document.getElementById('editFeedbackComment').value = comment;
            openModal('editFeedbackModal');
        }

        function selectStar(val) {
            currentRating = val;
            document.getElementById('feedbackRating').value = val;
            renderStars('starPicker', val);
        }
        function hoverStar(val)   { renderStars('starPicker', val, true); }
        function resetStarHover() { renderStars('starPicker', currentRating); }

        function selectEditStar(val) {
            editRating = val;
            document.getElementById('editFeedbackRating').value = val;
            renderStars('editStarPicker', val);
        }
        function hoverEditStar(val)   { renderStars('editStarPicker', val, true); }
        function resetEditStarHover() { renderStars('editStarPicker', editRating); }

        function renderStars(pickerId, rating, isHover = false) {
            const btns = document.querySelectorAll(`#${pickerId} .star-btn`);
            btns.forEach(btn => {
                const v = parseInt(btn.dataset.value);
                btn.classList.toggle('selected', !isHover && v <= rating);
                btn.classList.toggle('hovered',   isHover && v <= rating);
            });
        }

        window.onclick = function (event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }
    </script>
@endsection
