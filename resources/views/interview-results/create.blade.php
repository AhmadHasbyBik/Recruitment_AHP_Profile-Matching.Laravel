<!-- resources/views/interview-results/create.blade.php -->
@extends('layouts.app')

@section('title', 'Input Interview Results')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('interviews.index') }}">Interview</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Input Interview Results - {{ $interview->candidate->name }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('interview-results.store', $interview->id) }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="score">Score (0-100)</label>
                                <input type="number" name="score" id="score"
                                    class="form-control @error('score') is-invalid @enderror" value="{{ old('score') }}"
                                    min="0" max="100" step="0.01" required>
                                @error('score')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="strengths">Strengths</label>
                                <textarea name="strengths" id="strengths" rows="3" class="form-control @error('strengths') is-invalid @enderror"
                                    required>{{ old('strengths') }}</textarea>
                                @error('strengths')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="weaknesses">Weaknesses</label>
                                <textarea name="weaknesses" id="weaknesses" rows="3"
                                    class="form-control @error('weaknesses') is-invalid @enderror" required>{{ old('weaknesses') }}</textarea>
                                @error('weaknesses')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="recommendation">Recommendation</label>
                                <textarea name="recommendation" id="recommendation" rows="3"
                                    class="form-control @error('recommendation') is-invalid @enderror" required>{{ old('recommendation') }}</textarea>
                                @error('recommendation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="notes">Additional Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
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
                                    <option value="hold" {{ old('decision') == 'hold' ? 'selected' : '' }}>On Hold
                                    </option>
                                    <option value="accepted" {{ old('decision') == 'accepted' ? 'selected' : '' }}>Accepted
                                    </option>
                                    <option value="rejected" {{ old('decision') == 'rejected' ? 'selected' : '' }}>Rejected
                                    </option>
                                </select>
                                @error('decision')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Results</button>
                                <a href="{{ route('interviews.show', $interview->id) }}"
                                    class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
