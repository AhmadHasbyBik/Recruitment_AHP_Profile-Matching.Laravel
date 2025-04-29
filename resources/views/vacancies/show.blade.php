<!-- resources/views/vacancies/show.blade.php -->
@extends('layouts.app')

@section('title', 'List Pekerjaan Details')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('vacancies.index') }}">List Pekerjaan</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Vacancy Details: {{ $vacancy->position }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('vacancies.edit', $vacancy->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Position</th>
                                        <td>{{ $vacancy->position }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($vacancy->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Open Date</th>
                                        <td>{{ $vacancy->open_date->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Close Date</th>
                                        <td>{{ $vacancy->close_date->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Candidates</th>
                                        <td>{{ $vacancy->candidates->count() }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $vacancy->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $vacancy->updated_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Job Description</h3>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($vacancy->description)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('vacancies.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection