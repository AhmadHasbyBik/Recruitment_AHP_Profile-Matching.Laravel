<!-- resources/views/profile_matching/history/index.blade.php -->
@extends('layouts.app')

@section('title', 'Profile Matching History')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">History</li>
    </ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('profile-matching.history.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="vacancy_id">Position</label>
                                    <select name="vacancy_id" id="vacancy_id" class="form-control select2">
                                        <option value="">All Positions</option>
                                        @foreach($vacancies as $vacancy)
                                            <option value="{{ $vacancy->id }}" {{ request('vacancy_id') == $vacancy->id ? 'selected' : '' }}>
                                                {{ $vacancy->position }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="rank_from">Rank From</label>
                                    <input type="number" name="rank_from" id="rank_from" min="1"
                                           class="form-control" value="{{ request('rank_from') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="rank_to">Rank To</label>
                                    <input type="number" name="rank_to" id="rank_to" min="1"
                                           class="form-control" value="{{ request('rank_to') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="score_from">Score From</label>
                                    <input type="number" name="score_from" id="score_from" min="0" max="100" step="0.01"
                                           class="form-control" value="{{ request('score_from') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="score_to">Score To</label>
                                    <input type="number" name="score_to" id="score_to" min="0" max="100" step="0.01"
                                           class="form-control" value="{{ request('score_to') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from">From Date</label>
                                    <input type="date" name="date_from" id="date_from" 
                                           class="form-control" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to">To Date</label>
                                    <input type="date" name="date_to" id="date_to" 
                                           class="form-control" value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="processed_by">Processed By</label>
                                    <select name="processed_by" id="processed_by" class="form-control">
                                        <option value="">All</option>
                                        <option value="system" {{ request('processed_by') == 'system' ? 'selected' : '' }}>System</option>
                                        @foreach($processors as $processor)
                                            <option value="{{ $processor->id }}" {{ request('processed_by') == $processor->id ? 'selected' : '' }}>
                                                {{ $processor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="padding-top: 30px">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('profile-matching.history.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 15%">Date</th>
                                    <th style="width: 20%">Posisi Pekerjaan</th>
                                    <th style="width: 15%">Candidate</th>
                                    <th style="width: 8%">Rank</th>
                                    <th style="width: 10%">Score</th>
                                    <th style="width: 15%">Processed By</th>
                                    <th style="width: 12%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories as $history)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <i class="far fa-calendar-alt mr-1 text-primary"></i>
                                        {{ $history->processed_at->format('d M Y H:i') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $history->candidate->vacancy->position }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-user mr-1 text-success"></i>
                                        {{ $history->candidate->name }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $history->rank == 1 ? 'gold' : ($history->rank <= 3 ? 'primary' : 'secondary') }}">
                                            {{ $history->rank }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $history->final_score >= 80 ? 'success' : ($history->final_score >= 60 ? 'warning' : 'danger') }}">
                                            {{ number_format($history->final_score, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-user-tie mr-1 text-purple"></i>
                                        {{ $history->processedBy->name ?? 'System' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('profile-matching.history.show', $history->id) }}" 
                                               class="btn btn-info" title="View Details"
                                               data-toggle="tooltip" data-placement="top">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('profile-matching.history.by-vacancy', $history->candidate->vacancy_id) }}" 
                                               class="btn btn-primary" title="View by Vacancy"
                                               data-toggle="tooltip" data-placement="top">
                                                <i class="fas fa-list"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No matching results found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Modern Pagination -->
                    <div class="row mt-4">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info">
                                Showing {{ $histories->firstItem() }} to {{ $histories->lastItem() }} of {{ $histories->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="d-flex justify-content-end">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination pagination-rounded">
                                        {{-- Previous Page Link --}}
                                        <li class="page-item {{ $histories->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $histories->withQueryString()->previousPageUrl() }}" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>

                                        {{-- Pagination Elements --}}
                                        @foreach ($histories->getUrlRange(1, $histories->lastPage()) as $page => $url)
                                            <li class="page-item {{ $page == $histories->currentPage() ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $histories->withQueryString()->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        <li class="page-item {{ !$histories->hasMorePages() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $histories->withQueryString()->nextPageUrl() }}" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="float-right">
                        <small class="text-muted">
                            Last updated: {{ now()->format('d M Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Custom styles */
    .card-primary.card-outline {
        border-top: 3px solid #007bff;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.05);
    }
    
    .badge.bg-gold {
        background-color: #FFD700;
        color: #000;
    }
    
    .pagination-rounded .page-item:first-child .page-link {
        border-radius: 50% 0 0 50%;
    }
    
    .pagination-rounded .page-item:last-child .page-link {
        border-radius: 0 50% 50% 0;
    }
    
    .pagination-rounded .page-item .page-link {
        margin: 0 2px;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        padding: 0;
    }
    
    .pagination-rounded .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .pagination-rounded .page-item .page-link:hover {
        background-color: #e9ecef;
    }
    
    /* Filter form styles */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border-radius: 4px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function () {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Select a position",
            allowClear: true
        });
        
        // Set default dates if empty
        if (!$('#date_from').val()) {
            $('#date_from').val(new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().substr(0, 10));
        }
        
        if (!$('#date_to').val()) {
            $('#date_to').val(new Date().toISOString().substr(0, 10));
        }
        
        // Validate rank and score ranges
        $('#rank_from, #rank_to, #score_from, #score_to').on('change', function() {
            const from = $(this).attr('id').includes('from') ? $(this) : $('#' + $(this).attr('id').replace('to', 'from'));
            const to = $(this).attr('id').includes('to') ? $(this) : $('#' + $(this).attr('id').replace('from', 'to'));
            
            if (from.val() && to.val() && parseFloat(from.val()) > parseFloat(to.val())) {
                alert('The "from" value cannot be greater than the "to" value');
                $(this).val('');
            }
        });
    });
</script>
@endpush