<!-- resources/views/interview-results/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Interview Results')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('interviews.index') }}">Interview</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Interview Results - {{ $interview->candidate->name }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('interview-results.update', $interviewResult->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="score">Score (0-100)</label>
                                <input type="number" name="score" id="score"
                                    class="form-control @error('score') is-invalid @enderror"
                                    value="{{ old('score', $interviewResult->score) }}" min="0" max="100"
                                    step="0.01" required>
                                @error('score')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="strengths">Strengths</label>
                                <textarea name="strengths" id="strengths" rows="3" class="form-control @error('strengths') is-invalid @enderror"
                                    required>{{ old('strengths', $interviewResult->strengths) }}</textarea>
                                @error('strengths')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="weaknesses">Weaknesses</label>
                                <textarea name="weaknesses" id="weaknesses" rows="3"
                                    class="form-control @error('weaknesses') is-invalid @enderror" required>{{ old('weaknesses', $interviewResult->weaknesses) }}</textarea>
                                @error('weaknesses')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="recommendation">Recommendation</label>
                                <textarea name="recommendation" id="recommendation" rows="3"
                                    class="form-control @error('recommendation') is-invalid @enderror" required>{{ old('recommendation', $interviewResult->recommendation) }}</textarea>
                                @error('recommendation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="notes">Additional Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $interviewResult->notes) }}</textarea>
                                @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="decision">Decision</label>
                                <select name="decision" id="decision"
                                    class="form-control @error('decision') is-invalid @enderror" required>
                                    <option value="hold"
                                        {{ old('decision', $interviewResult->decision) == 'hold' ? 'selected' : '' }}>On
                                        Hold</option>
                                    <option value="accepted"
                                        {{ old('decision', $interviewResult->decision) == 'accepted' ? 'selected' : '' }}>
                                        Accepted</option>
                                    <option value="rejected"
                                        {{ old('decision', $interviewResult->decision) == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                                @error('decision')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Results</button>
                                <a href="{{ route('interview-results.show', $interviewResult->id) }}"
                                    class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
