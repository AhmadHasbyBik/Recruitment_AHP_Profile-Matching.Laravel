<!-- resources/views/decisions/show.blade.php -->
@extends('layouts.app')

@section('title', 'Decision Details')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('decisions.index') }}">Recruitment Decisions</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Decision Details for {{ $vacancy->position }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Selected Candidate</h3>
                                    </div>
                                    <div class="card-body">
                                        <h4>{{ $topCandidate->name }}</h4>
                                        <p>
                                            <i class="fas fa-envelope"></i> {{ $topCandidate->email }}<br>
                                            <i class="fas fa-phone"></i> {{ $topCandidate->phone }}
                                        </p>
                                        <div class="alert alert-success">
                                            <h5><i class="icon fas fa-trophy"></i> Final Score: {{ number_format($topCandidate->results->first()->final_score, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Vacancy Information</h3>
                                    </div>
                                    <div class="card-body">
                                        <h4>{{ $vacancy->position }}</h4>
                                        <p>
                                            <strong>Open:</strong> {{ $vacancy->open_date->format('d M Y') }}<br>
                                            <strong>Close:</strong> {{ $vacancy->close_date->format('d M Y') }}
                                        </p>
                                        <p>
                                            <strong>Total Candidates:</strong> {{ $vacancy->candidates->count() }}<br>
                                            <strong>Processed:</strong> {{ $vacancy->candidates->whereHas('results')->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h4>All Candidates Ranking</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Rank</th>
                                        <th>Candidate</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Final Score</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $result)
                                    <tr class="{{ $result->rank == 1 ? 'table-success' : '' }}">
                                        <td>{{ $result->rank }}</td>
                                        <td>{{ $result->candidate->name }}</td>
                                        <td>{{ $result->candidate->email }}</td>
                                        <td>{{ $result->candidate->phone }}</td>
                                        <td>{{ number_format($result->final_score, 2) }}</td>
                                        <td>
                                            <a href="{{ route('candidates.show', $result->candidate->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('decisions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Decisions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection