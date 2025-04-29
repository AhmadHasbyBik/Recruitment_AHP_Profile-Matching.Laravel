<!-- resources/views/criteria_statuses/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Kriteria Status')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('criteria_statuses.index') }}">Kriteria Status</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('criteria_statuses.update', $criteriaStatus->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="code">Status Code</label>
                            <input type="text" class="form-control" id="code" name="code" 
                                   value="{{ old('code', $criteriaStatus->code) }}" required>
                            <small class="text-muted">Unique code identifier (e.g. C1, C2, C3)</small>
                            @error('code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Status Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name', $criteriaStatus->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('criteria_statuses.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection