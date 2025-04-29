<!-- resources/views/profile_matching/results.blade.php -->
@extends('layouts.app')

@section('title', 'Hasil Profile Matching')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('profile-matching.index') }}">Profile Matching</a></li>
        <li class="breadcrumb-item active">Hasil</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Hasil Profile Matching - {{ $vacancy->position }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('profile-matching.view-results', $vacancy->id) }}"
                                class="btn btn-sm btn-success">
                                <i class="fas fa-file-excel"></i> Lihat Perangkingan
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Candidates</span>
                                        <span class="info-box-number">{{ count($results) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-success"><i class="fas fa-trophy"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Top Score</span>
                                        <span
                                            class="info-box-number">{{ number_format($results[0]['final_score'] ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Rank</th>
                                        <th>Candidate</th>
                                        @foreach ($criterias as $criteria)
                                            <th>
                                                {{ $criteria->name }}
                                                <small class="d-block text-muted">{{ $criteria->type }}</small>
                                            </th>
                                        @endforeach
                                        <th>Total Gap</th>
                                        <th>Final Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $result)
                                        <tr class="{{ $loop->first ? 'table-success' : '' }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $result['candidate']->name }}</strong>
                                                <div class="text-muted small">
                                                    {{ $result['candidate']->email }}
                                                </div>
                                            </td>
                                            @foreach ($criterias as $criteria)
                                                @php
                                                    $value =
                                                        $result['candidate']->criteriaValues
                                                            ->where('criteria_id', $criteria->id)
                                                            ->first()->value ?? 0;
                                                    $gap = abs($idealProfile[$criteria->id] - $value);
                                                @endphp
                                                <td class="text-center">
                                                    <div>{{ $value }}</div>
                                                    <small class="text-muted">(Gap: {{ $gap }})</small>
                                                </td>
                                            @endforeach
                                            <td class="text-center">{{ number_format($result['total_gap'], 2) }}</td>
                                            <td class="text-center font-weight-bold">
                                                {{ number_format($result['final_score'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Add this section to the view -->
                        @if (auth()->user()->isSuperAdmin())
                            <div class="mt-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h4 class="card-title text-white">Set Ideal Profile Values</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('profile-matching.save-ideal-values') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="vacancy_id" value="{{ $vacancy->id }}">

                                            <div class="row">
                                                @foreach ($criterias as $criteria)
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label
                                                                class="d-flex justify-content-between align-items-center">
                                                                <span>{{ $criteria->name }}</span>
                                                                <span
                                                                    class="badge badge-{{ $criteria->type == 'core' ? 'success' : 'warning' }}">
                                                                    {{ ucfirst($criteria->type) }}
                                                                </span>
                                                            </label>
                                                            <select name="ideal_values[{{ $criteria->id }}]"
                                                                class="form-control select2">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <option value="{{ $i }}"
                                                                        {{ ($idealProfile[$criteria->id] ?? 0) == $i ? 'selected' : '' }}>
                                                                        {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-12 text-right">
                                                    <button type="submit" class="btn btn-primary px-4">
                                                        <i class="fas fa-save mr-2"></i> Save Ideal Values
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('profile-matching.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Profile Matching
                            </a>
                            <form action="{{ route('profile-matching.save-results') }}" method="POST"
                                class="d-inline float-right">
                                @csrf
                                <input type="hidden" name="vacancy_id" value="{{ $vacancy->id }}">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-eye"></i> Lihat Perangkingan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .info-box {
            cursor: default;
            margin-bottom: 0;
        }

        .info-box-content {
            padding: 5px 10px;
        }

        .info-box-text {
            font-size: 0.9rem;
        }

        .info-box-number {
            font-size: 1.4rem;
            font-weight: bold;
        }
    </style>
@endpush
@push('styles')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@endpush
