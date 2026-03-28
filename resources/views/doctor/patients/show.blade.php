@extends('layouts.dashboard')

@section('title', 'Patient Clinical Profile')

@section('content')
    <div class="welcome-section"
        style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
        <a href="{{ route('doctor.patients') }}"
            style="text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Back to Patients List
        </a>
        <div style="display: flex; gap: 12px;">
            <button class="btn-primary" style="width: auto; padding: 10px 24px; margin-top: 0;"
                onclick="openHistoryModal()">
                <i class="fas fa-plus"></i> Add Medical Record
            </button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px;">
        <!-- Patient Sidebar (Profile Card) -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="glass-card" style="padding: 32px; text-align: center;">
                <div style="position: relative; display: inline-block; margin-bottom: 20px;">
                    <img src="{{ $patient->user->profile_image ? asset('storage/' . $patient->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($patient->user->name) . '&background=0D9488&color=fff' }}"
                        alt="Patient"
                        style="width: 120px; height: 120px; border-radius: 32px; object-fit: cover; border: 4px solid #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.05);">
                    @if($patient->blood_type)
                        <span
                            style="position: absolute; bottom: 8px; right: 0; background: var(--primary); color: #fff; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #fff; font-size: 0.8rem; font-weight: 800;">
                            {{ $patient->blood_type }}
                        </span>
                    @endif
                </div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 8px;">
                    {{ $patient->user->name }}
                </h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">Patient ID:
                    #{{ $patient->patient_id}}</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; text-align: left;">
                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px;">
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">Age</span>
                        <span
                            style="font-weight: 700;">{{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : '--' }}
                            Years</span>
                    </div>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px;">
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">Gender</span>
                        <span style="font-weight: 700;">{{ $patient->gender ? ucfirst($patient->gender) : '--' }}</span>
                    </div>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px;">
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">Weight</span>
                        <span
                            style="font-weight: 700;">{{ $patient->weight ?? ($patient->vitals->first() ? $patient->vitals->first()->weight : '--') }}
                            kg</span>
                    </div>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px;">
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">Height</span>
                        <span
                            style="font-weight: 700;">{{ $patient->height ?? ($patient->vitals->first() ? $patient->vitals->first()->height : '--') }}
                            cm</span>
                    </div>
                </div>
            </div>

            <div class="glass-card" style="padding: 24px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;">Allergies ⚠️</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @if(is_array($patient->allergies) && count($patient->allergies) > 0)
                        @foreach($patient->allergies as $allergy)
                            <span
                                style="background: #FEF2F2; color: #DC2626; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; border: 1px solid #FEE2E2;">
                                {{ $allergy }}
                            </span>
                        @endforeach
                    @else
                        <span style="color: var(--text-muted); font-size: 0.9rem; font-style: italic;">No data available</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Profile Content -->
        <div style="display: flex; flex-direction: column; gap: 24px;">

            <div class="glass-card" style="padding: 24px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">Vitals Progression 📈</h3>
                <canvas id="vitalsChart" height="200"></canvas>
            </div>


            <div class="glass-card" style="padding: 24px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">Medical History & Visits ⏳</h3>
                <div class="timeline">
                    @forelse($patient->medicalHistories as $history)
                        <div class="timeline-item">
                            <div class="timeline-icon teal">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="timeline-content" style="position: relative;">
                                <div class="timeline-date">{{ $history->diagnosis_date }}</div>
                                <div class="timeline-type">{{ $history->condition }}</div>
                                <p class="timeline-desc">{{ $history->treatment }}</p>
                                <div style="margin-top: 8px;">
                                    <a href="{{ route('doctor.reports.show', $history) }}" class="btn-primary-sm"
                                        style="text-decoration: none; font-size: 0.75rem; padding: 4px 10px;">
                                        View Report
                                    </a>
                                </div>
                                @if($history->doctor && $history->doctor->user_id === Auth::id())
                                    <div style="position: absolute; top: 0; right: 0; display: flex; gap: 8px;">
                                        <button type="button" class="btn-icon"
                                            onclick="openEditHistoryModal({{ $history->id }}, '{{ addslashes($history->condition) }}', '{{ $history->diagnosis_date }}', '{{ addslashes($history->treatment) }}')"
                                            style="color: var(--primary); background: transparent; border: none; cursor: pointer;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('doctor.medical-history.destroy', $history->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this record?');"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon"
                                                style="color: var(--red); background: transparent; border: none; cursor: pointer;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p style="text-align: center; color: var(--text-muted); padding: 20px;">No medical records available.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add History Modal -->
    <div id="historyModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);">
        <div class="glass-card"
            style="background: white; margin: 10% auto; padding: 32px; width: 500px; border-radius: 24px; position: relative;">
            <h3 style="margin-top: 0; margin-bottom: 24px; font-weight: 800;">Add New Medical Record</h3>
            <form action="{{ route('doctor.medical-history.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

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
                        Notes</label>
                    <textarea name="treatment" rows="4"
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;"></textarea>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeHistoryModal()" class="btn-outline"
                        style="width: auto; padding: 10px 24px;">Cancel</button>
                    <button type="submit" class="btn-primary" style="width: auto; padding: 10px 24px; margin-top: 0;">Save
                        Record</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit History Modal -->
    <div id="editHistoryModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);">
        <div class="glass-card"
            style="background: white; margin: 10% auto; padding: 32px; width: 500px; border-radius: 24px; position: relative;">
            <h3 style="margin-top: 0; margin-bottom: 24px; font-weight: 800;">Edit Medical Record</h3>
            <form id="editHistoryForm" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Medical
                        Condition / Diagnosis</label>
                    <input type="text" id="edit_condition" name="condition" required
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Diagnosis
                        Date</label>
                    <input type="date" id="edit_diagnosis_date" name="diagnosis_date" required
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Treatment /
                        Notes</label>
                    <textarea id="edit_treatment" name="treatment" rows="4"
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;"></textarea>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeEditHistoryModal()" class="btn-outline"
                        style="width: auto; padding: 10px 24px;">Cancel</button>
                    <button type="submit" class="btn-primary" style="width: auto; padding: 10px 24px; margin-top: 0;">Update
                        Record</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function openHistoryModal() {
            document.getElementById('historyModal').style.display = 'block';
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').style.display = 'none';
        }

        function openEditHistoryModal(id, condition, date, treatment) {
            document.getElementById('editHistoryForm').action = `/doctor/medical-history/${id}`;
            document.getElementById('edit_condition').value = condition;
            document.getElementById('edit_diagnosis_date').value = date;
            document.getElementById('edit_treatment').value = treatment;
            document.getElementById('editHistoryModal').style.display = 'block';
        }

        function closeEditHistoryModal() {
            document.getElementById('editHistoryModal').style.display = 'none';
        }

        // document.addEventListener('DOMContentLoaded', function () {
        //     // Vitals Chart
        //     const vtCtx = document.getElementById('vitalsChart').getContext('2d');

        //     const vitalsData = @json($patient->vitals->reverse()->values());
        //     const labels = vitalsData.map(v => new Date(v.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }));
        //     const weightData = vitalsData.map(v => v.weight);
        //     const pulseData = vitalsData.map(v => v.pulse);

        //     new Chart(vtCtx, {
        //         type: 'line',
        //         data: {
        //             labels: labels.length ? labels : ['No Data'],
        //             datasets: [
        //                 {
        //                     label: 'Weight (kg)',
        //                     data: weightData.length ? weightData : [0],
        //                     borderColor: '#0D9488',
        //                     backgroundColor: 'rgba(13, 148, 136, 0.1)',
        //                     fill: true,
        //                     tension: 0.4
        //                 },
        //                 {
        //                     label: 'Pulse Rate',
        //                     data: pulseData.length ? pulseData : [0],
        //                     borderColor: '#0EA5E9',
        //                     backgroundColor: 'transparent',
        //                     borderDash: [5, 5],
        //                     tension: 0.4
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: true,
        //             maintainAspectRatio: false,
        //             plugins: {
        //                 legend: { position: 'top', labels: { font: { family: 'Inter', size: 11 } } }
        //             },
        //             scales: {
        //                 y: { beginAtZero: false, grid: { color: '#f1f5f9' } },
        //                 x: { grid: { display: false } }
        //             }
        //         }
        //     });
        // });
    </script>
@endsection