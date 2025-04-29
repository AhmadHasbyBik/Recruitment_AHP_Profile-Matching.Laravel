<!-- resources/views/candidates/show.blade.php -->
@extends('layouts.app')

@section('title', 'Kandidat Details')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('candidates.index') }}">Kandidat</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if ($candidate->interviewSchedules->isNotEmpty())
                        <div class="card mt-4">
                            <div class="card-header">
                                <h3 class="card-title">Interview History</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Scheduled By</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($candidate->interviewSchedules as $interview)
                                            <tr>
                                                <td>{{ $interview->schedule_date->format('d M Y H:i') }}</td>
                                                <td>{{ $interview->user->name }}</td>
                                                <td>
                                                    @if ($interview->isPending())
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($interview->isApproved())
                                                        <span class="badge badge-success">Approved</span>
                                                    @elseif($interview->isRejected())
                                                        <span class="badge badge-danger">Rejected</span>
                                                    @else
                                                        <span class="badge badge-info">Completed</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('interviews.show', $interview->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <div class="card-header">
                        <div class="card-tools">
                            <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Name</th>
                                        <td>{{ $candidate->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $candidate->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $candidate->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Position</th>
                                        <td>{{ $candidate->vacancy->position }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if ($candidate->results->isNotEmpty())
                                                <span
                                                    class="badge badge-{{ $candidate->results->first()->rank == 1 ? 'success' : 'primary' }}">
                                                    Rank {{ $candidate->results->first()->rank }}
                                                </span>
                                                <small class="text-muted">
                                                    (Score:
                                                    {{ number_format($candidate->results->first()->final_score, 2) }})
                                                </small>
                                            @else
                                                <span class="badge badge-secondary">Not Processed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Applied At</th>
                                        <td>{{ $candidate->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </table>

                                @if ($candidate->resume)
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Resume/CV</h3>
                                        </div>
                                        <div class="card-body">
                                            <a href="{{ asset($candidate->resume) }}" target="_blank"
                                                class="btn btn-primary">
                                                <i class="fas fa-download"></i> Download Resume
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Address</h3>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($candidate->address)) !!}
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h3 class="card-title">Criteria Values</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Criteria</th>
                                                    <th>Type</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($candidate->criteriaValues as $value)
                                                    <tr>
                                                        <td>{{ $value->criteria->name }}</td>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $value->criteria->type == 'core' ? 'success' : 'warning' }}">
                                                                {{ ucfirst($value->criteria->type) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $value->value }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('candidates.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
