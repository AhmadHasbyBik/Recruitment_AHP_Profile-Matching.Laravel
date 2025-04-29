<!-- resources/views/candidates/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Kandidat')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('candidates.index') }}">Kandidat</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                            id="name" name="name" value="{{ old('name', $candidate->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                            id="email" name="email" value="{{ old('email', $candidate->email) }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                            id="phone" name="phone" value="{{ old('phone', $candidate->phone) }}" required>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vacancy_id">Applying for Position</label>
                                        <select class="form-control @error('vacancy_id') is-invalid @enderror" 
                                            id="vacancy_id" name="vacancy_id" required>
                                            <option value="">Select Position</option>
                                            @foreach($vacancies as $vacancy)
                                                <option value="{{ $vacancy->id }}" {{ old('vacancy_id', $candidate->vacancy_id) == $vacancy->id ? 'selected' : '' }}>
                                                    {{ $vacancy->position }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('vacancy_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="resume">Resume/CV (Optional)</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('resume') is-invalid @enderror" 
                                                id="resume" name="resume">
                                            <label class="custom-file-label" for="resume">
                                                {{ $candidate->resume ? basename($candidate->resume) : 'Choose file' }}
                                            </label>
                                            @error('resume')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        @if($candidate->resume)
                                            <small class="form-text text-muted">
                                                Current file: <a href="{{ asset($candidate->resume) }}" target="_blank">View</a>
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                    id="address" name="address" rows="3" required>{{ old('address', $candidate->address) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <hr>
                            @if(in_array(auth()->user()->role, ['hrd', 'super_admin']))
                            <h4>Criteria Values</h4>
                            <div class="row">
                                @foreach($criterias as $criteria)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="criteria_{{ $criteria->id }}">
                                            {{ $criteria->name }}
                                            <span class="badge badge-{{ $criteria->type == 'core' ? 'success' : 'warning' }}">
                                                {{ ucfirst($criteria->type) }}
                                            </span>
                                        </label>
                                        @php
                                            $value = $candidate->criteriaValues->where('criteria_id', $criteria->id)->first()->value ?? old('criteria_'.$criteria->id);
                                        @endphp
                                        <input type="number" min="1" max="5" step="0.1"
                                            class="form-control @error('criteria_'.$criteria->id) is-invalid @enderror" 
                                            id="criteria_{{ $criteria->id }}" 
                                            name="criteria_{{ $criteria->id }}" 
                                            value="{{ $value }}" required>
                                        @error('criteria_'.$criteria->id)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small class="text-muted">{{ $criteria->description }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="{{ route('candidates.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endpush