<!-- resources/views/interviews/show.blade.php -->
@extends('layouts.app')

@section('title', 'Interview Details')

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
                        <h3 class="card-title">Interview Details</h3>
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
                                        <th>Email</th>
                                        <td>{{ $interview->candidate->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $interview->candidate->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Position</th>
                                        <td>{{ $interview->candidate->vacancy->position }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>Interview Details</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Date</th>
                                        <td>{{ $interview->schedule_date->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
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
                                    </tr>
                                    <tr>
                                        <th>Notes</th>
                                        <td>{{ $interview->notes ?? '-' }}</td>
                                    </tr>
                                    @if ($interview->isRejected())
                                        <tr>
                                            <th>Rejection Reason</th>
                                            <td>{{ $interview->feedback }}</td>
                                        </tr>
                                    @endif
                                    @if (in_array(auth()->user()->role, ['hrd', 'super_admin']) && $interview->isApproved() && !$interview->isCompleted())
                                        <form action="{{ route('interviews.complete', $interview->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success mb-3">
                                                <i class="fas fa-check-circle"></i> Complete Interview & Input Results
                                            </button>
                                        </form>
                                    @endif

                                    @if ($interview->isCompleted())
                                        @if ($interview->hasResult())
                                            <div class="mb-3">
                                                <a href="{{ route('interview-results.show', $interview->result->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-clipboard-list"></i> View Interview Results
                                                </a>
                                            </div>
                                        @elseif(in_array(auth()->user()->role, ['hrd', 'super_admin']))
                                            <div class="mb-3">
                                                <a href="{{ route('interview-results.create', $interview->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Add Interview Results
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </table>
                            </div>
                        </div>

                        @if ($interview->isPending() && auth()->user()->role === 'user' && auth()->id() == $interview->candidate->user_id)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between">
                                        <form action="{{ route('interviews.approve', $interview->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Approve Schedule
                                            </button>
                                        </form>

                                        <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                            <i class="fas fa-times"></i> Reject Schedule
                                        </button>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Interview Schedule</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('interviews.reject', $interview->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="feedback">Rejection Reason</label>
                                                            <textarea name="feedback" id="feedback" class="form-control" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Submit
                                                            Rejection</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($interview->isCompleted())
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Interview Results</h3>
                                                        @if (in_array(auth()->user()->role, ['hrd', 'super_admin']))
                                                            @if (!$interview->hasResult())
                                                                <div class="card-tools">
                                                                    <a href="{{ route('interview-results.create', $interview->id) }}"
                                                                        class="btn btn-success">
                                                                        <i class="fas fa-plus"></i> Add Results
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="card-body">
                                                        @if ($interview->hasResult())
                                                            <div class="alert alert-info">
                                                                <strong>Score:</strong> {{ $interview->result->score }}/100
                                                                <br>
                                                                <strong>Decision:</strong> {!! $interview->result->decision_badge !!}
                                                            </div>
                                                            <a href="{{ route('interview-results.show', $interview->result->id) }}"
                                                                class="btn btn-primary">
                                                                View Full Results
                                                            </a>
                                                        @else
                                                            <p>No results have been recorded yet.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('interviews.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
