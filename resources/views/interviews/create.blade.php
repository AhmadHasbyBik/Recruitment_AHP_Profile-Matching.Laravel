<!-- resources/views/interviews/create.blade.php -->
@extends('layouts.app')

@section('title', 'Schedule New Interview')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('interviews.index') }}">Interview</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Schedule New Interview</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('interviews.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="candidate_id">Candidate</label>
                            <select name="candidate_id" id="candidate_id" class="form-control @error('candidate_id') is-invalid @enderror" required>
                                <option value="">Select Candidate</option>
                                @foreach($candidates as $candidate)
                                    <option value="{{ $candidate->id }}" {{ old('candidate_id') == $candidate->id ? 'selected' : '' }}>
                                        {{ $candidate->name }} - {{ $candidate->vacancy->position }}
                                    </option>
                                @endforeach
                            </select>
                            @error('candidate_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="feedback_by">Interviewer</label>
                            <select name="feedback_by" id="feedback_by" class="form-control @error('feedback_by') is-invalid @enderror" required>
                                <option value="">Select Interviewer</option>
                                @foreach($interviewers as $interviewer)
                                    <option value="{{ $interviewer->id }}" {{ old('feedback_by') == $interviewer->id ? 'selected' : '' }}>
                                        {{ $interviewer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('feedback_by')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="schedule_date">Schedule Date & Time</label>
                            <input type="datetime-local" name="schedule_date" id="schedule_date" 
                                   class="form-control @error('schedule_date') is-invalid @enderror" 
                                   value="{{ old('schedule_date') }}" required min="{{ now()->format('Y-m-d\TH:i') }}">
                            @error('schedule_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Schedule Interview</button>
                            <a href="{{ route('interviews.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection