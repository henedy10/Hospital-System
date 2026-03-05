@extends('layouts.dashboard')

@section('title', 'Nurse Dashboard')

@section('content')
    <div class="welcome-section" style="margin-bottom: 32px;">
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Hello Nurse Joy! 👋
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Here is your overview for today's shift and patient care
            tasks.</p>
    </div>

    <div class="stats-grid">
        <!-- Assigned Patients -->
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-teal">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $assignedPatients }}</h3>
                    <p>Assigned Patients</p>
                </div>
            </div>
        </div>

        <!-- Urgent Tasks -->
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-rose">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stats-info">
                    <h3 style="color: var(--danger);">{{ $urgentTasks }}</h3>
                    <p>Urgent Tasks</p>
                </div>
            </div>
        </div>

        <!-- Medication Due -->
        <div class="glass-card">
            <div class="stats-card">
                <div class="icon-box bg-amber">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $medicationDue }}</h3>
                    <p>Medication Due</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2-cols" style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; margin-top: 24px;">
        <div class="glass-card chart-container">
            <div class="chart-header">
                <h2>Nursing Activities Distribution</h2>
            </div>
            <canvas id="activitiesChart" height="150"></canvas>
        </div>

        <div class="glass-card">
            <div class="chart-header">
                <h2>Shift Timeline</h2>
            </div>
            <div class="timeline" style="padding: 10px;">
                <div style="border-left: 2px solid #0D9488; padding-left: 20px; position: relative; margin-bottom: 20px;">
                    <div
                        style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #0D9488;">
                    </div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">08:00 AM</span>
                    <p style="font-weight: 600; margin: 4px 0;">Shift Handover</p>
                </div>
                <div style="border-left: 2px solid #e2e8f0; padding-left: 20px; position: relative; margin-bottom: 20px;">
                    <div
                        style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #e2e8f0;">
                    </div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">10:30 AM</span>
                    <p style="font-weight: 600; margin: 4px 0;">Morning Rounds (Ward A)</p>
                </div>
                <div style="border-left: 2px solid #e2e8f0; padding-left: 20px; position: relative;">
                    <div
                        style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #e2e8f0;">
                    </div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">12:00 PM</span>
                    <p style="font-weight: 600; margin: 4px 0;">Medication Distribution</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('activitiesChart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($activityData['labels']),
                    datasets: [{
                        data: @json($activityData['data']),
                        backgroundColor: [
                            '#0D9488',
                            '#0EA5E9',
                            '#F59E0B',
                            '#EF4444',
                            '#8B5CF6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 12 }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
@endsection