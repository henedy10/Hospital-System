@extends('layouts.dashboard')

@section('title', 'Patient Dashboard')

@section('content')
    <div class="dashboard-welcome">
        <h1>Welcome back, Sarah!</h1>
        <p class="text-muted">Here's an overview of your health and upcoming appointments.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon pulse-blue"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-details">
                <h3>Next Appointment</h3>
                <p class="stat-value">Tomorrow, 10:00 AM</p>
                <p class="stat-label">Dr. John Doe (General Medicine)</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pulse-green"><i class="fas fa-file-medical"></i></div>
            <div class="stat-details">
                <h3>Latest Report</h3>
                <p class="stat-value">Blood Test Result</p>
                <p class="stat-label">Uploaded 2 days ago</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pulse-purple"><i class="fas fa-pills"></i></div>
            <div class="stat-details">
                <h3>Active Medications</h3>
                <p class="stat-value">3 Prescriptions</p>
                <p class="stat-label">Next dose: 8:00 PM</p>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Upcoming Appointments -->
        <div class="grid-card">
            <div class="card-header">
                <h2>Upcoming Appointments</h2>
                <a href="{{ route('patient.appointments') }}" class="btn-link">View All</a>
            </div>
            <div class="card-body">
                <div class="appointment-list">
                    <div class="appointment-item">
                        <div class="appointment-date">
                            <span class="day">06</span>
                            <span class="month">MAR</span>
                        </div>
                        <div class="appointment-info">
                            <h4>General Checkup</h4>
                            <p>Dr. John Doe • 10:00 AM</p>
                        </div>
                        <div class="appointment-status status-upcoming">Confirmed</div>
                    </div>
                    <div class="appointment-item">
                        <div class="appointment-date">
                            <span class="day">15</span>
                            <span class="month">MAR</span>
                        </div>
                        <div class="appointment-info">
                            <h4>Dental Cleaning</h4>
                            <p>Dr. Smith • 02:30 PM</p>
                        </div>
                        <div class="appointment-status status-pending">Pending</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Medical History -->
        <div class="grid-card">
            <div class="card-header">
                <h2>Recent History</h2>
                <a href="{{ route('patient.history') }}" class="btn-link">View History</a>
            </div>
            <div class="card-body">
                <div class="history-list">
                    <div class="history-item">
                        <div class="history-icon bg-light-blue"><i class="fas fa-flask"></i></div>
                        <div class="history-info">
                            <h4>Laboratory Analysis</h4>
                            <p>Complete Blood Count • Feb 28, 2026</p>
                        </div>
                        <button class="btn-icon"><i class="fas fa-download"></i></button>
                    </div>
                    <div class="history-item">
                        <div class="history-icon bg-light-green"><i class="fas fa-prescription"></i></div>
                        <div class="history-info">
                            <h4>New Prescription</h4>
                            <p>Amoxicillin 500mg • Feb 25, 2026</p>
                        </div>
                        <button class="btn-icon"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-welcome {
            margin-bottom: 2rem;
        }

        .dashboard-welcome h1 {
            font-size: 1.875rem;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .pulse-blue {
            background: #E0F2FE;
            color: #0EA5E9;
        }

        .pulse-green {
            background: #DCFCE7;
            color: #10B981;
        }

        .pulse-purple {
            background: #F3E8FF;
            color: #8B5CF6;
        }

        .stat-value {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin: 0.25rem 0;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6B7280;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .grid-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #F3F4F6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
        }

        .btn-link {
            color: #0D9488;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .appointment-item,
        .history-item {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid #F9FAFB;
        }

        .appointment-item:last-child,
        .history-item:last-child {
            border-bottom: none;
        }

        .appointment-date {
            width: 4.5rem;
            height: 4.5rem;
            background: #F8FAFC;
            border-radius: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid #E2E8F0;
        }

        .appointment-date .day {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1E293B;
        }

        .appointment-date .month {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748B;
        }

        .appointment-info h4,
        .history-info h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .appointment-info p,
        .history-info p {
            font-size: 0.875rem;
            color: #6B7280;
        }

        .appointment-status {
            margin-left: auto;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-upcoming {
            background: #ECFDF5;
            color: #059669;
        }

        .status-pending {
            background: #FEF3C7;
            color: #D97706;
        }

        .history-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-light-blue {
            background: #EFF6FF;
            color: #3B82F6;
        }

        .bg-light-green {
            background: #F0FDF4;
            color: #22C55E;
        }

        .btn-icon {
            background: none;
            border: none;
            color: #9CA3AF;
            cursor: pointer;
            transition: color 0.2s;
            margin-left: auto;
        }

        .btn-icon:hover {
            color: #0D9488;
        }
    </style>
@endsection