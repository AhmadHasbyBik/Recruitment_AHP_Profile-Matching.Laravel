<!-- resources/views/decisions/index.blade.php -->
@extends('layouts.app')

@section('title', 'Recruitment Decisions')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Recruitment Decisions</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recruitment Decisions Based on Profile Matching</h3>
                        <div class="card-tools">
                            <a href="{{ route('profile-matching.index') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-calculator"></i> Process Matching
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Vacancy</th>
                                        <th>Top Candidate</th>
                                        <th>Score</th>
                                        <th>Processed At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vacancies as $vacancy)
                                        @php
                                            $topCandidate = $vacancy->candidates()
                                                ->whereHas('results')
                                                ->with('results')
                                                ->orderByDesc('final_score')
                                                ->first();
                                        @endphp
                                        
                                        @if($topCandidate)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $vacancy->position }}</td>
                                            <td>{{ $topCandidate->name }}</td>
                                            <td>{{ number_format($topCandidate->results->first()->final_score, 2) }}</td>
                                            <td>{{ $topCandidate->results->first()->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('profile-matching.results', $vacancy->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-list"></i> View All
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {
            $('.datatable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "order": [[3, 'desc']]
            });
        });
    </script>
@endpush