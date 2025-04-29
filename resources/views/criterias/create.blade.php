<!-- resources/views/criteria/create.blade.php -->
@extends('layouts.app')

@section('title', 'Tambah Kriteria')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('criterias.index') }}">Kriteria</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('criterias.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code">Criteria Code</label>
                                        <input type="text" class="form-control" id="code" name="code" required>
                                        <small class="text-muted">Unique code identifier</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Criteria Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="criteria_status_id">Status</label>
                                        <select class="form-control select2" id="criteria_status_id"
                                            name="criteria_status_id" required>
                                            <option value="">Select Status</option>
                                            @foreach ($statuses as $id => $displayText)
                                                <option value="{{ $id }}">{{ $displayText }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('criterias.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
