@extends('layouts.dashboard')

@section('title', 'Medical Reports')

@section('content')
    <div class="welcome-section"
        style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Reports &
                Analytics 📊</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Monitor clinic performance and review issued medical
                reports.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <button class="btn-outline" style="width: auto; padding: 10px 24px;">
                <i class="fas fa-file-export"></i> Export Data
            </button>
            <button class="btn-primary" style="width: auto; padding: 10px 24px; margin-top: 0;">
                <i class="fas fa-plus"></i> Create New Report
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="stats-grid" style="margin-bottom: 32px;">
        @foreach($stats as $stat)
            <div class="glass-card">
                <div class="summary-card">
                    <div class="stats-info">
                        <p>{{ $stat['label'] }}</p>
                        <h3>{{ $stat['value'] }}</h3>
                        <span class="summary-growth {{ str_contains($stat['change'], '+') ? 'growth-up' : 'growth-down' }}">
                            {{ $stat['change'] }} from last month
                        </span>
                    </div>
                    <div class="icon-box {{ $stat['color'] }}">
                        <i class="{{ $stat['icon'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; margin-bottom: 32px;">
        <!-- Improved Diagnosis Distribution Chart -->
        <div class="glass-card" style="display: flex; flex-direction: column;">
            <h2 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">Diagnosis Distribution</h2>

            <div style="position: relative; height: 200px; margin-bottom: 20px;">
                <canvas id="categoryChart"></canvas>
                <div
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                    <span style="display: block; font-size: 1.5rem; font-weight: 800; color: var(--text-main);">128</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">TOTAL</span>
                </div>
            </div>

            <div id="chartLegend" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: auto;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 10px; height: 10px; border-radius: 3px; background: #0D9488;"></div>
                    <span style="font-size: 0.85rem; font-weight: 500;">Heart (30%)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 10px; height: 10px; border-radius: 3px; background: #0EA5E9;"></div>
                    <span style="font-size: 0.85rem; font-weight: 500;">Bones (20%)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 10px; height: 10px; border-radius: 3px; background: #F59E0B;"></div>
                    <span style="font-size: 0.85rem; font-weight: 500;">Lab (25%)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 10px; height: 10px; border-radius: 3px; background: #EF4444;"></div>
                    <span style="font-size: 0.85rem; font-weight: 500;">Surgery (25%)</span>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="glass-card">
            <div class="chart-header">
                <h2 style="font-size: 1.1rem; font-weight: 700;">Latest Issued Reports</h2>
                <div class="search-input-wrapper" style="width: 250px;">
                    <i class="fas fa-search" style="left: 12px; font-size: 0.8rem;"></i>
                    <input type="text" class="form-control" style="padding: 8px 12px 8px 32px; font-size: 0.85rem;"
                        placeholder="Search reports...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Report Name</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td style="color: var(--primary); font-weight: 600;">{{ $report['id'] }}</td>
                                <td>{{ $report['name'] }}</td>
                                <td>{{ $report['date'] }}</td>
                                <td>{{ $report['category'] }}</td>
                                <td>
                                    <span class="report-status {{ $report['status_type'] }}">
                                        {{ $report['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('doctor.reports.show', $report['id']) }}" class="btn-icon"
                                            title="View Details"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="btn-icon" title="Download"><i class="fas fa-download"></i></a>
                                        <a href="#" class="btn-icon" title="Print"><i class="fas fa-print"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Doughnut Chart for Categories
            const catCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Heart', 'Bones', 'Laboratory', 'Surgery'],
                    datasets: [{
                        data: [30, 20, 25, 25],
                        backgroundColor: ['#0D9488', '#0EA5E9', '#F59E0B', '#EF4444'],
                        borderWidth: 0,
                        hoverOffset: 15,
                        weight: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Using custom legend
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            displayColors: true,
                            boxPadding: 6
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