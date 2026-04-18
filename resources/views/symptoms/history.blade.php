@extends('layouts.dashboard')

@section('title', 'Symptom Check History')

@section('content')
<div class="welcome-section" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
            Symptom History
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">
            A record of your previous AI symptom analyses.
        </p>
    </div>
    <a href="{{ route('symptoms.index') }}" class="btn-primary" style="width: auto; padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-plus"></i> New Analysis
    </a>
</div>

@if($history->count() > 0)
    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="custom-table" style="text-align: left;">
                <thead style="background: #f8fafc;">
                    <tr>
                        <th style="padding: 16px 24px; text-align: left;">Date</th>
                        <th style="padding: 16px 24px; text-align: left;">Symptoms</th>
                        <th style="padding: 16px 24px; text-align: left;">Specialty</th>
                        <th style="padding: 16px 24px; text-align: left;">Urgency</th>
                        <th style="padding: 16px 24px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $check)
                        <tr>
                            <td style="padding: 16px 24px; white-space: nowrap; font-size: 0.9rem; color: var(--text-muted);">
                                <i class="far fa-clock" style="margin-right: 6px;"></i>
                                {{ $check->created_at->format('M d, Y h:ia') }}
                            </td>
                            <td style="padding: 16px 24px; max-width: 250px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $check->symptoms_text }}">
                                    "{{ $check->symptoms_text }}"
                                </div>
                            </td>
                            <td style="padding: 16px 24px; font-weight: 600;">
                                {{ $check->ai_response['recommended_specialization'] ?? 'N/A' }}
                            </td>
                            <td style="padding: 16px 24px;">
                                @php
                                    $urgency = strtolower($check->urgency_level);
                                    if($urgency === 'high') {
                                        $badgeClass = 'badge-cancelled';
                                    } elseif($urgency === 'medium') {
                                        $badgeClass = 'badge-pending';
                                    } else {
                                        $badgeClass = 'badge-confirmed';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ strtoupper($urgency) }}
                                </span>
                            </td>
                            <td style="padding: 16px 24px; text-align: right; white-space: nowrap;">
                                <a href="{{ route('symptoms.result', $check->id) }}" style="color: var(--primary); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                    View Report <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div style="margin-top: 24px;">
        {{ $history->links() }}
    </div>
@else
    <div class="glass-card" style="text-align: center; padding: 64px 24px;">
        <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; color: var(--text-muted); font-size: 2rem;">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 8px;">No History Found</h3>
        <p style="color: var(--text-muted); margin-bottom: 24px;">You haven't checked any symptoms yet.</p>
        <a href="{{ route('symptoms.index') }}" class="btn-primary">
            <i class="fas fa-play"></i> Start Your First Check
        </a>
    </div>
@endif
@endsection
