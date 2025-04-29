<!-- resources/views/interviews/index.blade.php -->
@extends('layouts.app')

@section('title', 'Interview Schedules')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Interviews</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if (in_array(auth()->user()->role, ['hrd', 'super_admin']))
                            <div class="card-tools">
                                <a href="{{ route('interviews.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Schedule New Interview
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Candidate</th>
                                        <th>Position</th>
                                        <th>Schedule Date</th>
                                        <th>Status</th>
                                        <th>Result</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schedules as $schedule)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $schedule->candidate->name }}</td>
                                            <td>{{ $schedule->candidate->vacancy->position }}</td>
                                            <td>
                                                {{ $schedule->schedule_date->format('d M Y H:i') }}
                                                @if ($schedule->isOverdue())
                                                    <span class="badge bg-danger">Overdue</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($schedule->isCompleted())
                                                    @if ($schedule->hasResult())
                                                        <span
                                                            class="badge {{ $schedule->result->isAccepted() ? 'bg-success' : ($schedule->result->isRejected() ? 'bg-danger' : 'bg-warning') }}">
                                                            {{ $schedule->result->score }}/100 -
                                                            {{ ucfirst($schedule->result->decision) }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">No result</span>
                                                        @if (in_array(auth()->user()->role, ['hrd', 'super_admin']))
                                                            <a href="{{ route('interview-results.create', $schedule->id) }}"
                                                                class="btn btn-xs btn-primary">
                                                                <i class="fas fa-plus"></i> Add
                                                            </a>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="badge bg-dark">Interview not schedule yet</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($schedule->isPending())
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($schedule->isApproved())
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif($schedule->isRejected())
                                                    <span class="badge badge-danger">Rejected</span>
                                                @else
                                                    <span class="badge badge-info">Completed</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('interviews.show', $schedule->id) }}"
                                                    class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if (in_array(auth()->user()->role, ['hrd', 'super_admin']))
                                                    <a href="{{ route('interviews.edit', $schedule->id) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('interviews.destroy', $schedule->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No interview schedules found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $schedules->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
