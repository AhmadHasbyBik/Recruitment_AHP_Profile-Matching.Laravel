<!-- resources/views/interview-results/show.blade.php -->
@extends('layouts.app')

@section('title', 'Interview Results')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('interviews.index') }}">Interview</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Interview Results - {{ $interview->candidate->name }}</h3>
                        <div class="card-tools">
                            @if (in_array(auth()->user()->role, ['hrd', 'super_admin']))
                                <a href="{{ route('interview-results.edit', $interviewResult->id) }}"
                                    class="btn btn-primary mr-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <form action="{{ route('interview-results.destroy', $interviewResult->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Candidate Information</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Name</th>
                                        <td>{{ $interview->candidate->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Position</th>
                                        <td>{{ $interview->candidate->vacancy->position }}</td>
                                    </tr>
                                    <tr>
                                        <th>Interview Date</th>
                                        <td>{{ $interview->schedule_date->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Interviewer</th>
                                        <td>{{ $interview->feedbackBy->name ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>Evaluation Summary</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Score</th>
                                        <td>{{ $interviewResult->score }}/100</td>
                                    </tr>
                                    <tr>
                                        <th>Decision</th>
                                        <td>{!! $interviewResult->decision_badge !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Evaluated By</th>
                                        <td>{{ $interviewResult->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Evaluation Date</th>
                                        <td>{{ $interviewResult->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h3 class="card-title">Strengths</h3>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($interviewResult->strengths)) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-danger text-white">
                                        <h3 class="card-title">Weaknesses</h3>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($interviewResult->weaknesses)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h3 class="card-title">Recommendation</h3>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($interviewResult->recommendation)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($interviewResult->notes)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <h3 class="card-title">Additional Notes</h3>
                                        </div>
                                        <div class="card-body">
                                            {!! nl2br(e($interviewResult->notes)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('interviews.show', $interview->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Interview
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
