@extends('layouts.dashboard')

@section('title', 'Nurse Dashboard')

@section('content')
    <div class="welcome-section" style="margin-bottom: 32px; animation: fadeIn 0.8s ease-out;">
        <h1 style="font-size: 1.85rem; font-weight: 850; color: #1e293b; margin-bottom: 8px; letter-spacing: -0.02em;">Welcome back, Nurse Joy! 👋</h1>
        <p style="color: #64748b; font-size: 1rem; font-weight: 500;">Monitor your assigned patients and upcoming clinical shifts.</p>
    </div>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
        <!-- Assigned Patients -->
        <div class="glass-card stat-card-hover" style="padding: 24px; border-radius: 20px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(13, 148, 136, 0.1); background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(240, 253, 250, 0.9));">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 14px; background: #0D9488; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 8px 16px -4px rgba(13, 148, 136, 0.3);">
                    <i class="fas fa-user-injured" style="font-size: 1.25rem;"></i>
                </div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #0D9488; background: #f0fdfa; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(13, 148, 136, 0.1);">Active Care</span>
            </div>
            <h3 style="font-size: 2.25rem; font-weight: 800; color: #0f172a; margin-bottom: 4px;">{{ $assignedPatients }}</h3>
            <p style="color: #64748b; font-size: 0.9rem; font-weight: 600;">Assigned Patients</p>
        </div>

        <!-- Urgent Tasks -->
        <div class="glass-card stat-card-hover" style="padding: 24px; border-radius: 20px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(225, 29, 72, 0.1); background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255, 241, 242, 0.9));">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 14px; background: #e11d48; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 8px 16px -4px rgba(225, 29, 72, 0.3);">
                    <i class="fas fa-bolt" style="font-size: 1.25rem;"></i>
                </div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #e11d48; background: #fff1f2; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(225, 29, 72, 0.1);">Immediate</span>
            </div>
            <h3 style="font-size: 2.25rem; font-weight: 800; color: #e11d48; margin-bottom: 4px;">{{ $urgentTasks }}</h3>
            <p style="color: #64748b; font-size: 0.9rem; font-weight: 600;">Urgent Tasks</p>
        </div>

        <!-- Medication Due -->
        <div class="glass-card stat-card-hover" style="padding: 24px; border-radius: 20px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(245, 158, 11, 0.1); background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255, 251, 235, 0.9));">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 14px; background: #f59e0b; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 8px 16px -4px rgba(245, 158, 11, 0.3);">
                    <i class="fas fa-pills" style="font-size: 1.25rem;"></i>
                </div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #b45309; background: #fef3c7; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(245, 158, 11, 0.1);">Adminstration</span>
            </div>
            <h3 style="font-size: 2.25rem; font-weight: 800; color: #0f172a; margin-bottom: 4px;">{{ $medicationDue }}</h3>
            <p style="color: #64748b; font-size: 0.9rem; font-weight: 600;">Medication Rounds</p>
        </div>
    </div>

    <div class="grid-2-cols" style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 24px; margin-top: 24px;">
        <!-- Chart Section -->
        <div class="glass-card" style="padding: 28px; border-radius: 24px; position: relative; overflow: hidden; background: white;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                <h2 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0;">Care Distribution</h2>
                <div style="display: flex; gap: 8px;">
                     <div style="padding: 6px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.75rem; font-weight: 700; color: #64748b;">This Shift</div>
                </div>
            </div>
            <div style="position: relative; height: 280px; display: flex; align-items: center; justify-content: center;">
                <canvas id="activitiesChart"></canvas>
                <div style="position: absolute; text-align: center; pointer-events: none;">
                    <div id="totalActivitiesCount" style="font-size: 2.5rem; font-weight: 900; color: #1e293b; line-height: 1;">0</div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px;">Tasks Logged</div>
                </div>
            </div>
        </div>

        <!-- Shift Timeline -->
        <div class="glass-card" style="padding: 28px; border-radius: 24px; background: white;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                <h2 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0;">Timeline</h2>
                <span style="font-size: 0.75rem; font-weight: 700; color: #0D9488; background: #f0fdfa; padding: 4px 10px; border-radius: 20px;">Live Updates</span>
            </div>
            <div class="timeline-container" style="display: flex; flex-direction: column; gap: 0;">
                <div class="timeline-item" style="display: flex; gap: 20px; padding-bottom: 24px; position: relative;">
                    <div style="position: absolute; left: 11px; top: 12px; bottom: -12px; width: 2px; background: linear-gradient(to bottom, #0D9488 0%, #e2e8f0 100%);"></div>
                    <div style="z-index: 1; width: 24px; height: 24px; border-radius: 50%; background: white; border: 4px solid #0D9488; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 4px #f0fdfa;"></div>
                    <div>
                        <span style="font-size: 0.75rem; font-weight: 800; color: #0D9488;">08:00 AM • Completed</span>
                        <p style="font-weight: 700; color: #1e293b; margin: 2px 0; font-size: 0.95rem;">Morning Shift Handover</p>
                        <p style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">Transition report from night shift completed for Ward A.</p>
                    </div>
                </div>
                <div class="timeline-item" style="display: flex; gap: 20px; padding-bottom: 24px; position: relative;">
                    <div style="position: absolute; left: 11px; top: 0; bottom: -12px; width: 2px; background: #e2e8f0;"></div>
                    <div style="z-index: 1; width: 24px; height: 24px; border-radius: 50%; background: white; border: 4px solid #0ea5e9; display: flex; align-items: center; justify-content: center;">
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #0ea5e9; animation: pulse 2s infinite;"></div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; font-weight: 800; color: #0ea5e9;">10:30 AM • In Progress</span>
                        <p style="font-weight: 700; color: #1e293b; margin: 2px 0; font-size: 0.95rem;">Standard Patient Rounds</p>
                        <p style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">Conducting vital checks and patient assessments.</p>
                    </div>
                </div>
                <div class="timeline-item" style="display: flex; gap: 20px; position: relative;">
                    <div style="z-index: 1; width: 24px; height: 24px; border-radius: 50%; background: white; border: 4px solid #f1f5f9; display: flex; align-items: center; justify-content: center;"></div>
                    <div>
                        <span style="font-size: 0.75rem; font-weight: 800; color: #94a3b8;">12:00 PM • Upcoming</span>
                        <p style="font-weight: 700; color: #475569; margin: 2px 0; font-size: 0.95rem;">Lunch Medication Round</p>
                        <p style="font-size: 0.8rem; color: #94a3b8; margin-top: 4px;">Scheduled distribution for long-term patients.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(14, 165, 233, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(14, 165, 233, 0); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('activitiesChart').getContext('2d');
            const activityData = @json($activityData['data']);
            const totalTasks = activityData.reduce((a, b) => a + b, 0);
            
            document.getElementById('totalActivitiesCount').textContent = totalTasks;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($activityData['labels']),
                    datasets: [{
                        data: activityData,
                        backgroundColor: [
                            '#0D9488', // clinical
                            '#0EA5E9', // admin
                            '#8B5CF6', // general
                            '#EF4444', // urgent/other
                            '#F59E0B'
                        ],
                        borderWidth: 0,
                        hoverOffset: 15,
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 25,
                                font: { 
                                    size: 13,
                                    weight: '700',
                                    family: "'Inter', sans-serif"
                                },
                                color: '#4b5563'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            cornerRadius: 8,
                            displayColors: true
                        }
                    },
                    cutout: '82%',
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        });
    </script>
@endsection