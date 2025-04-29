<!-- resources/views/profile_matching/results_list.blade.php -->
@extends('layouts.app')

@section('title', 'Hasil Profile Matching')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('profile-matching.index') }}">Profile Matching</a></li>
        <li class="breadcrumb-item active">Hasil {{ $vacancy->position }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $vacancy->position }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('profile-matching.index') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Rank</th>
                                        <th>Candidate</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Final Score</th>
                                        <th>Processed At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($results as $result)
                                        <tr>
                                            <td>{{ $result->rank }}</td>
                                            <td>{{ $result->candidate->name }}</td>
                                            <td>{{ $result->candidate->email }}</td>
                                            <td>{{ $result->candidate->phone }}</td>
                                            <td><strong>{{ number_format($result->final_score, 2) }}</strong></td>
                                            <td>{{ $result->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('candidates.show', $result->candidate->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No results available for this vacancy.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection