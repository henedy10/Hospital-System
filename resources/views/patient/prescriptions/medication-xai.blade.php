@extends('layouts.dashboard')

@section('content')
<div class="container my-5">

    <h2 class="mb-4 text-primary">🧾 AI Prescription Explanation</h2>

    {{-- Prescription Header --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between">
            <div>
                <h5>👨‍⚕️ Doctor: {{ $prescription->doctor->user->name }}</h5>
                <p>📅 Date: {{ $prescription->created_at->format('Y-m-d') }}</p>
            </div>
            <div>
                <h5>🧑 Patient: {{ $prescription->patient->user->name }}</h5>
            </div>
        </div>
    </div>

    {{-- Drugs --}}
    @foreach($explanation['data'] as $drug)

    <div class="card shadow mb-4 border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">💊 {{ $drug['drug_name'] }}</h5>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- LEFT SIDE --}}
                <div class="col-md-6">

                    <h6 class="text-success">🎯 Treatment Goal</h6>
                    <p>{{ $drug['english']['usage'] }}</p>

                    <h6 class="text-info">💊 Dosage</h6>
                    <p>{{ $drug['english']['dosage'] }}</p>

                    <h6 class="text-warning">⚠️ Side Effects</h6>
                    <ul>
                        @foreach($drug['english']['side_effects'] as $effect)
                            <li>{{ $effect }}</li>
                        @endforeach
                    </ul>

                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-md-6">

                    <h6 class="text-danger">🚨 Warnings</h6>
                    @foreach($drug['english']['warnings'] as $warning)
                        <div class="alert alert-danger p-2">
                            <strong>{{ $warning['issue'] }}</strong><br>
                            {{ $warning['reason'] }}
                        </div>
                    @endforeach

                    <h6 class="text-dark mt-3">📌 Summary</h6>
                    <p>{{ $drug['english']['summary'] }}</p>

                </div>

            </div>

            {{-- XAI SECTION --}}
            @if(isset($drug['english']['xai']))
            <hr>

            <h5 class="text-primary">🧠 Explainable AI Insights</h5>

            <div class="row mt-3">

                {{-- Feature Importance --}}
                <div class="col-md-6">
                    <h6>📊 Feature Importance</h6>

                    @foreach($drug['english']['xai']['feature_importance'] as $feature => $value)

                        <div class="mb-2">
                            <small>{{ $feature }}</small>
                            <div class="progress">
                                <div class="progress-bar bg-info"
                                     style="width: {{ $value['impact'] * 100 }}%">
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>

                {{-- AI Reasoning --}}
                <div class="col-md-6">
                    <h6>🤖 AI Reasoning</h6>

                    <ul>

                            <li>{{ $drug['english']['xai']['reasoning']}}</li>

                    </ul>

                    <div class="mt-3">
                        <strong>Confidence:</strong>
                        <span class="badge bg-success">
                            {{ $drug['english']['xai']['confidence'] * 100 }}%
                        </span>
                    </div>
                </div>

            </div>
            @endif

        </div>
    </div>

    @endforeach

</div>
@endsection