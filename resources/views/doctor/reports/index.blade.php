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
            <!-- <button class="btn-outline" style="width: auto; padding: 10px 24px;">
                <i class="fas fa-file-export"></i> Export Data
            </button> -->
            <button class="btn-primary" style="width: auto; padding: 10px 24px; margin-top: 0;"
                onclick="openCreateReportModal()">
                <i class="fas fa-plus"></i> Create New Report
            </button>
        </div>
    </div>

    @if (session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

    <!-- Create Report Modal -->
    <div id="createReportModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);">
        <div class="glass-card"
            style="background: white; margin: 5% auto; padding: 32px; width: 650px; border-radius: 24px; position: relative; max-height: 85vh; overflow-y: auto;">
            <h3 style="margin-top: 0; margin-bottom: 24px; font-weight: 800;">Create New Report</h3>
            <form action="{{ route('doctor.medical-history.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Patient</label>
                    <select name="patient_id" required
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <option value="" disabled selected>Select patient</option>
                        @foreach($patientsForSelect as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Medical
                        Condition / Diagnosis</label>
                    <input type="text" name="condition" required
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Diagnosis
                        Date</label>
                    <input type="date" name="diagnosis_date" required value="{{ date('Y-m-d') }}"
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Treatment /
                        Notes (one line per step)</label>
                    <textarea name="treatment" rows="4"
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;"></textarea>
                </div>

                <div style="margin-bottom: 24px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">
                        <i class="fas fa-prescription-bottle-alt" style="color: #0d9488;"></i> Prescription (Optional)
                    </label>
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; margin-bottom: 4px; font-size: 0.8rem; font-weight: 600;">Prescription Notes</label>
                        <input type="text" name="prescription_notes" placeholder="General notes for the prescription" style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem;">
                    </div>
                    <div id="prescription-items-container">
                        <!-- Prescription items will be added here -->
                    </div>
                    <button type="button" onclick="addPrescriptionItem()" class="btn-outline"
                        style="width: auto; padding: 6px 12px; font-size: 0.85rem; margin-top: 8px;">
                        <i class="fas fa-plus"></i> Add Medicine
                    </button>
                </div>

                <div style="margin-bottom: 24px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">
                        <i class="fas fa-flask" style="color: #2563eb;"></i> Lab Test Suggestion (Optional)
                    </label>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0 0 12px;">Patient can book suggested tests from Lab History.</p>
                    <div id="lab-items-container"></div>
                    <button type="button" onclick="addLabItem()" class="btn-outline"
                        style="width: auto; padding: 6px 12px; font-size: 0.85rem; margin-top: 8px;">
                        <i class="fas fa-plus"></i> Add Lab Test
                    </button>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeCreateReportModal()" class="btn-outline"
                        style="width: auto; padding: 10px 24px;">Cancel</button>
                    <button type="submit" class="btn-primary"
                        style="width: auto; padding: 10px 24px; margin-top: 0;">Save Report</button>
                </div>
            </form>
        </div>
    </div>


        @endforeach
    </div>

    <div style="grid-column: 1 / -1;">
        <!-- Reports Table -->
        <div class="glass-card">
            <div class="chart-header">
                <h2 style="font-size: 1.1rem; font-weight: 700;">Latest Issued Reports</h2>
                <form method="GET" action="{{ route('doctor.reports') }}" style="margin: 0;">
                    <div class="search-input-wrapper" style="width: 250px;">
                        <i class="fas fa-search" style="left: 12px; font-size: 0.8rem;"></i>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            style="padding: 8px 12px 8px 32px; font-size: 0.85rem;" placeholder="Search reports...">
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Patient Name</th>
                            <th>Report Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="display: flex; justify-content:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr style="font-weight: 600;">
                                <td>
                                    R-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}
                                </td>

                                <td>
                                    {{ $report->patient?->user?->name}}
                                </td>

                                <td>
                                    {{ $report->condition }}
                                    @if($report->user)
                                        - {{ $report->user->name }}
                                    @endif
                                </td>
                                <td>{{ optional($report->diagnosis_date)->format('Y-m-d') ?? $report->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="report-status success">
                                        Recorded
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; justify-content:center; gap: 8px;">
                                        <a href="{{ route('doctor.reports.edit', $report->id) }}" class="btn-icon" title="Edit Report & Prescription">
                                            <i class="fas fa-edit" style="color: #0ea5e9;"></i>
                                        </a>
                                        <a href="{{ route('doctor.reports.show', $report->id) }}" class="btn-icon" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                    <div style="background: #f8fafc; display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: 50%; margin-bottom: 16px;">
                                        <i class="fas fa-calendar-times" style="font-size: 1.5rem; color: #94A3B8;"></i>
                                    </div>
                                    <h3 style="margin: 0; font-size: 1.1rem; color: var(--text-main); font-weight: 700;">No Reports Found</h3>
                                    <p style="color:red;margin-top: 8px; font-size: 0.9rem;">There are no reports matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div style="margin-top: 16px;">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function openCreateReportModal() {
            document.getElementById('createReportModal').style.display = 'block';
        }

        function closeCreateReportModal() {
            document.getElementById('createReportModal').style.display = 'none';
        }

        const labTestsOptions = @json($labTests->map(fn ($t) => ['id' => $t->id, 'name' => $t->name, 'category' => $t->category]));
        const labsOptions = @json($labs->map(fn ($l) => ['id' => $l->id, 'name' => $l->name, 'doctor' => $l->doctor?->user?->name]));

        let itemIndex = 0;
        let labItemIndex = 0;

        function addPrescriptionItem() {
            const container = document.getElementById('prescription-items-container');
            container.insertAdjacentHTML('beforeend', getPrescriptionItemHtml('items', itemIndex));
            itemIndex++;
        }

        function getPrescriptionItemHtml(prefix, index) {
            return `
            <div style="border:1px solid #e2e8f0; border-radius:12px; padding:14px; margin-bottom:10px; position:relative;">
                <button type="button" onclick="this.parentElement.remove()" style="position:absolute; top:8px; right:8px; background:#fee2e2; color:#dc2626; border:none; border-radius:6px; width:28px; height:28px; cursor:pointer;"><i class="fas fa-times"></i></button>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div><label style="font-size:.75rem; font-weight:600;">Medicine *</label><input type="text" name="${prefix}[${index}][medicine_name]" required style="width:100%; padding:8px; border-radius:8px; border:1px solid #cbd5e1;"></div>
                    <div><label style="font-size:.75rem; font-weight:600;">Dosage *</label><input type="text" name="${prefix}[${index}][dosage]" required style="width:100%; padding:8px; border-radius:8px; border:1px solid #cbd5e1;"></div>
                    <div><label style="font-size:.75rem; font-weight:600;">Times/Day *</label><input type="number" name="${prefix}[${index}][frequency]" required min="1" max="10" style="width:100%; padding:8px; border-radius:8px; border:1px solid #cbd5e1;"></div>
                    <div><label style="font-size:.75rem; font-weight:600;">Duration (days) *</label><input type="number" name="${prefix}[${index}][duration]" required min="1" max="365" style="width:100%; padding:8px; border-radius:8px; border:1px solid #cbd5e1;"></div>
                </div>
                <div style="margin-top:8px;"><label style="font-size:.75rem; font-weight:600;">Instructions</label><input type="text" name="${prefix}[${index}][instructions]" style="width:100%; padding:8px; border-radius:8px; border:1px solid #cbd5e1;"></div>
            </div>`;
        }

        function addLabItem() {
            const container = document.getElementById('lab-items-container');
            container.insertAdjacentHTML('beforeend', getLabItemHtml('lab_items', labItemIndex));
            labItemIndex++;
        }

        function getLabItemHtml(prefix, index) {
            let testOpts = '<option value="">Select test</option>';
            labTestsOptions.forEach(t => { testOpts += `<option value="${t.id}">${t.name} (${t.category})</option>`; });
            let labOpts = '<option value="">Any lab</option>';
            labsOptions.forEach(l => {
                const suffix = l.doctor ? ` — Dr. ${l.doctor}` : '';
                labOpts += `<option value="${l.id}">${l.name}${suffix}</option>`;
            });
            return `
            <div style="border:1px solid #dbeafe; border-radius:12px; padding:14px; margin-bottom:10px; position:relative; background:#f8fafc;">
                <button type="button" onclick="this.parentElement.remove()" style="position:absolute; top:8px; right:8px; background:#fee2e2; color:#dc2626; border:none; border-radius:6px; width:28px; height:28px; cursor:pointer;"><i class="fas fa-times"></i></button>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div><label style="font-size:.75rem; font-weight:600;">Lab Test *</label><select name="${prefix}[${index}][lab_test_id]" required style="width:100%; padding:8px; border-radius:8px; border:1px solid #cbd5e1;">${testOpts}</select></div>
                    <div><label style="font-size:.75rem; font-weight:600;">Preferred Lab</label><select name="${prefix}[${index}][lab_id]" style="width:100%; padding:8px; border-radius:8px; border:1px solid #cbd5e1;">${labOpts}</select></div>
                </div>
                <div style="margin-top:8px;"><label style="font-size:.75rem; font-weight:600;">Notes for patient</label><input type="text" name="${prefix}[${index}][notes]" placeholder="e.g. Fasting required" style="width:100%; padding:8px; border-radius:8px; border:1px solid #cbd5e1;"></div>
            </div>`;
        }



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
