<!-- resources/views/criteria_statuses/create.blade.php -->
@extends('layouts.app')

@section('title', 'Tambah Kriteria Status')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('criteria_statuses.index') }}">Kriteria Status</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('criteria_statuses.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="code">Status Code</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                            <small class="text-muted">Unique code identifier (e.g. C1, C2, C3)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Status Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('criteria_statuses.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection