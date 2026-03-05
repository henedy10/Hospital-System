@extends('layouts.dashboard')

@section('title', 'My Appointments')

@section('content')
    <div class="page-header">
        <div class="header-content">
            <h1>My Appointments</h1>
            <p class="text-muted">Manage your upcoming visits and view history.</p>
        </div>
        <button class="btn-primary"><i class="fas fa-plus"></i> Book New Appointment</button>
    </div>

    <div class="appointment-tabs">
        <button class="tab-btn active">Upcoming</button>
        <button class="tab-btn">Past</button>
        <button class="tab-btn">Cancelled</button>
    </div>

    <div class="appointments-container">
        <div class="appointment-card">
            <div class="doctor-profile">
                <img src="https://ui-avatars.com/api/?name=John+Doe&background=0D9488&color=fff" alt="Doctor"
                    class="doctor-avatar">
                <div class="doctor-info">
                    <h3>Dr. John Doe</h3>
                    <p>General Medicine • Cardiology</p>
                    <div class="rating"><i class="fas fa-star text-yellow"></i> 4.9 (120 reviews)</div>
                </div>
            </div>
            <div class="appointment-details">
                <div class="detail-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <span>Date</span>
                        <p>March 06, 2026</p>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <span>Time</span>
                        <p>10:00 AM - 10:30 AM</p>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <span>Location</span>
                        <p>Block A, Room 302</p>
                    </div>
                </div>
            </div>
            <div class="appointment-actions">
                <button class="btn-secondary">Reschedule</button>
                <button class="btn-outline-danger">Cancel</button>
            </div>
        </div>

        <div class="appointment-card status-pending-card">
            <div class="doctor-profile">
                <img src="https://ui-avatars.com/api/?name=Alice+Smith&background=8B5CF6&color=fff" alt="Doctor"
                    class="doctor-avatar">
                <div class="doctor-info">
                    <h3>Dr. Alice Smith</h3>
                    <p>Dermatology</p>
                    <div class="rating"><i class="fas fa-star text-yellow"></i> 4.8 (85 reviews)</div>
                </div>
            </div>
            <div class="appointment-details">
                <div class="detail-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <span>Date</span>
                        <p>March 15, 2026</p>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <span>Time</span>
                        <p>02:30 PM - 03:00 PM</p>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <span>Status</span>
                        <p class="text-orange">Awaiting Confirmation</p>
                    </div>
                </div>
            </div>
            <div class="appointment-actions">
                <button class="btn-outline">View Details</button>
                <button class="btn-outline-danger">Cancel Request</button>
            </div>
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
            background: none;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
            color: #6B7280;
            cursor: pointer;
            position: relative;
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

        .status-pending-card {
            border-left-color: #F59E0B;
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

        .rating {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .text-yellow {
            color: #F59E0B;
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
        }

        .btn-outline-danger {
            background: white;
            color: #EF4444;
            border: 1px solid #EF4444;
            padding: 0.625rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-outline {
            background: white;
            color: #374151;
            border: 1px solid #D1D5DB;
            padding: 0.625rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
        }

        .text-orange {
            color: #D97706;
        }
    </style>
@endsection