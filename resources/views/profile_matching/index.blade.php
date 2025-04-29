<!-- resources/views/profile_matching/index.blade.php -->
@extends('layouts.app')

@section('title', 'Profile Matching')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Profile Matching</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Pekerjaan untuk Profile Matching</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile-matching.process') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="vacancy_id">Pekerjaan</label>
                                <select class="form-control" id="vacancy_id" name="vacancy_id" required>
                                    <option value="">Pilih Pekerjaan</option>
                                    @foreach($vacancies as $vacancy)
                                        <option value="{{ $vacancy->id }}">{{ $vacancy->position }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calculator"></i> Process Profile Matching
                            </button>
                        </form>
                    </div>
                </div>
                
                @if(isset($results))
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Hasil Profile Matching for {{ $vacancy->position }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('profile-matching.results', $vacancy->id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-list"></i> View All Results
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
                                        @foreach($criterias as $criteria)
                                            <th>{{ $criteria->name }} ({{ $criteria->type }})</th>
                                        @endforeach
                                        <th>Total Gap</th>
                                        <th>Final Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $index => $result)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $result['candidate']->name }}</td>
                                            @foreach($criterias as $criteria)
                                                @php
                                                    $value = $result['candidate']->criteriaValues
                                                        ->where('criteria_id', $criteria->id)
                                                        ->first()->value ?? 0;
                                                    $gap = abs($idealProfile[$criteria->id] - $value);
                                                @endphp
                                                <td>
                                                    {{ $value }} 
                                                    <small class="text-muted">(Gap: {{ $gap }})</small>
                                                </td>
                                            @endforeach
                                            <td>{{ number_format($result['total_gap'], 2) }}</td>
                                            <td><strong>{{ number_format($result['final_score'], 2) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <h4>Ideal Profile</h4>
                            <ul>
                                @foreach($criterias as $criteria)
                                    <li>
                                        <strong>{{ $criteria->name }}:</strong> 
                                        {{ $idealProfile[$criteria->id] }} 
                                        ({{ $criteria->type === 'benefit' ? 'Higher is better' : 'Lower is better' }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection