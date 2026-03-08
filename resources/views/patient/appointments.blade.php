@extends('layouts.dashboard')

@section('title', 'My Appointments')

@section('content')
    <div class="page-header">
        <div class="header-content">
            <h1>My Appointments</h1>
            <p class="text-muted">Manage your upcoming visits and view history.</p>
        </div>
        <button class="btn-primary" onclick="openModal('bookModal')"><i class="fas fa-plus"></i> Book New
            Appointment</button>
    </div>

    <div class="appointment-tabs">
        <a href="{{ route('patient.appointments', ['status' => 'upcoming']) }}"
            class="tab-btn {{ $status === 'upcoming' ? 'active' : '' }}">Upcoming</a>
        <a href="{{ route('patient.appointments', ['status' => 'completed']) }}"
            class="tab-btn {{ $status === 'completed' ? 'active' : '' }}">Past</a>
        <a href="{{ route('patient.appointments', ['status' => 'cancelled']) }}"
            class="tab-btn {{ $status === 'cancelled' ? 'active' : '' }}">Cancelled</a>
    </div>

    <div class="appointments-container">
        @forelse($appointments as $appointment)
            <div class="appointment-card {{ $appointment->status === 'upcoming' ? '' : 'status-completed-card' }}">
                <div class="doctor-profile">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($appointment->doctor_name) }}&background=0D9488&color=fff"
                        alt="Doctor" class="doctor-avatar">
                    <div class="doctor-info">
                        <h3>{{ $appointment->doctor_name }}</h3>
                        <p>Specialist</p>
                    </div>
                </div>
                <div class="appointment-details">
                    <div class="detail-item">
                        <i class="fas fa-calendar-alt"></i>
                        <div>
                            <span>Date</span>
                            <p>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <span>Time</span>
                            <p>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <span>Reason</span>
                            <p>{{ $appointment->reason }}</p>
                        </div>
                    </div>
                </div>
                <div class="appointment-actions">
                    @if($appointment->status === 'upcoming')
                        <button class="btn-secondary" onclick="openEditModal({{ json_encode($appointment) }})">Reschedule</button>
                        <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                            @csrf
                            <button type="submit" class="btn-outline-danger w-full text-center">Cancel</button>
                        </form>
                    @else
                        <button class="btn-outline" disabled>{{ ucfirst($appointment->status) }}</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center bg-white rounded-xl shadow-sm">
                <p class="text-red-500 font-bold">* You have no appointments in this category.</p>
            </div>
        @endforelse
    </div>

    <!-- Booking Modal -->
    <div id="bookModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Book New Appointment</h2>
                <button class="close-btn" onclick="closeModal('bookModal')">&times;</button>
            </div>
            <form action="{{ route('patient.appointments.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Doctor</label>
                    <select name="doctor_id" class="form-control" required>
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} - {{ $doctor->specialist ?? 'General' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="appointment_date" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Preferred Time</label>
                        <select name="appointment_time" class="form-control" required>
                            <option value="">Select Time</option>
                            @for($i = 9; $i <= 17; $i++)
                                <option value="{{ $i }}:00">{{ sprintf('%02d:00 AM', $i > 12 ? $i - 12 : $i) }}</option>
                                <option value="{{ $i }}:30">{{ sprintf('%02d:30 AM', $i > 12 ? $i - 12 : $i) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Reason for Visit</label>
                    <textarea name="reason" class="form-control" rows="3" required
                        placeholder="Briefly describe your reason for visiting"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" onclick="closeModal('bookModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Confirm Booking</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Reschedule Appointment</h2>
                <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Doctor</label>
                    <select name="doctor_id" id="edit_doctor_id" class="form-control" required>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} - {{ $doctor->specialist ?? 'General' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="appointment_date" id="edit_appointment_date" class="form-control" required
                            min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Preferred Time</label>
                        <select name="appointment_time" id="edit_appointment_time" class="form-control" required>
                            @for($i = 9; $i <= 17; $i++)
                                <option value="{{ sprintf('%02d:00', $i) }}">{{ date('h:i A', strtotime($i . ':00')) }}</option>
                                <option value="{{ sprintf('%02d:30', $i) }}">{{ date('h:i A', strtotime($i . ':30')) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Reason for Visit</label>
                    <textarea name="reason" id="edit_reason" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" onclick="closeModal('editModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Update Appointment</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2rem;
        }

        .header-content h1 {
            font-size: 1.875rem;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #ECFDF5;
            color: #059669;
            border: 1px solid #10B981;
        }

        .btn-primary {
            background: #0D9488;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #0F766E;
        }

        .appointment-tabs {
            display: flex;
            gap: 1rem;
            border-bottom: 1px solid #E5E7EB;
            margin-bottom: 2rem;
        }

        .tab-btn {
            text-decoration: none;
            background: none;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
            color: #6B7280;
            cursor: pointer;
            position: relative;
            transition: color 0.2s;
        }

        .tab-btn:hover {
            color: #0D9488;
        }

        .tab-btn.active {
            color: #0D9488;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: #0D9488;
        }

        .appointments-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .appointment-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 2rem;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #0D9488;
        }

        .status-completed-card {
            border-left-color: #9CA3AF;
            opacity: 0.8;
        }

        .doctor-profile {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .doctor-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
        }

        .doctor-info h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .doctor-info p {
            font-size: 0.875rem;
            color: #6B7280;
        }

        .appointment-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .detail-item i {
            color: #6B7280;
            margin-top: 0.25rem;
        }

        .detail-item span {
            font-size: 0.75rem;
            color: #9CA3AF;
            text-transform: uppercase;
            font-weight: 600;
        }

        .detail-item p {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1F2937;
        }

        .appointment-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn-secondary {
            background: #F3F4F6;
            color: #374151;
            border: none;
            padding: 0.625rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            text-align: center;
            transition: background 0.2s;
        }

        .btn-secondary:hover {
            background: #E5E7EB;
        }

        .btn-outline-danger {
            background: white;
            color: #EF4444;
            border: 1px solid #EF4444;
            padding: 0.625rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-outline-danger:hover {
            background: #FEF2F2;
        }

        .btn-outline {
            background: white;
            color: #374151;
            border: 1px solid #D1D5DB;
            padding: 0.625rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            width: 500px;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }

        .close-btn {
            font-size: 1.5rem;
            color: #9CA3AF;
            background: none;
            border: none;
            cursor: pointer;
        }

        form {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #0D9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            background: #F9FAFB;
        }

        .w-full {
            width: 100%;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .p-8 {
            padding: 2rem;
        }
    </style>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
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

        // Close on outside click
        window.onclick = function (event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
@endsection
