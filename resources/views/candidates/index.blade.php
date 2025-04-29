<!-- resources/views/candidates/index.blade.php -->
@extends('layouts.app')

@section('title', 'List Kandidat')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Kandidat</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <div class="card-tools">
                            <a href="{{ route('candidates.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Tambahkan Kandidat
                            </a>

                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('candidates.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vacancy_id">Position</label>
                                        <select name="vacancy_id" id="vacancy_id" class="form-control select2">
                                            <option value="">All Positions</option>
                                            @foreach ($vacancies as $vacancy)
                                                <option value="{{ $vacancy->id }}"
                                                    {{ request('vacancy_id') == $vacancy->id ? 'selected' : '' }}>
                                                    {{ $vacancy->position }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">All Statuses</option>
                                            <option value="processed"
                                                {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
                                            <option value="unprocessed"
                                                {{ request('status') == 'unprocessed' ? 'selected' : '' }}>Unprocessed
                                            </option>
                                            <option value="top_rank"
                                                {{ request('status') == 'top_rank' ? 'selected' : '' }}>Top Rank</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_from">From Date</label>
                                        <input type="date" name="date_from" id="date_from" class="form-control"
                                            value="{{ request('date_from') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_to">To Date</label>
                                        <input type="date" name="date_to" id="date_to" class="form-control"
                                            value="{{ request('date_to') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('candidates.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 20%">Name</th>
                                        <th style="width: 20%">Email</th>
                                        <th style="width: 20%">Position</th>
                                        <th style="width: 10%">Status</th>
                                        <th style="width: 15%">Applied At</th>
                                        <th style="width: 10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($candidates as $candidate)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <i class="fas fa-user mr-1 text-primary"></i>
                                                {{ $candidate->name }}
                                            </td>
                                            <td>
                                                <i class="fas fa-envelope mr-1 text-info"></i>
                                                {{ $candidate->email }}
                                            </td>
                                            <td>
                                                <span class="badge bg-indigo">
                                                    {{ $candidate->vacancy->position }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($candidate->results->isNotEmpty())
                                                    <span
                                                        class="badge bg-{{ $candidate->results->first()->rank == 1 ? 'success' : ($candidate->results->first()->rank <= 3 ? 'primary' : 'warning') }}">
                                                        Rank {{ $candidate->results->first()->rank }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Not Processed</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="far fa-calendar-alt mr-1 text-purple"></i>
                                                {{ $candidate->created_at->format('d M Y') }}
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('candidates.show', $candidate->id) }}"
                                                        class="btn btn-info" title="View" data-toggle="tooltip"
                                                        data-placement="top">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if (in_array(auth()->user()->role, ['hrd', 'super_admin']))
                                                        <a href="{{ route('candidates.edit', $candidate->id) }}"
                                                            class="btn btn-warning" title="Edit" data-toggle="tooltip"
                                                            data-placement="top">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('candidates.destroy', $candidate->id) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger" title="Delete"
                                                                data-toggle="tooltip" data-placement="top"
                                                                onclick="return confirm('Are you sure you want to delete this candidate?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No candidates found matching your
                                                criteria.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Modern Pagination -->
                        <!-- Ganti bagian pagination dengan ini: -->
                        <div class="row mt-4">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info">
                                    Showing {{ $candidates->firstItem() }} to {{ $candidates->lastItem() }} of
                                    {{ $candidates->total() }} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="d-flex justify-content-end">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination pagination-rounded">
                                            {{-- Previous Page Link --}}
                                            <li class="page-item {{ $candidates->onFirstPage() ? 'disabled' : '' }}">
                                                <a class="page-link"
                                                    href="{{ $candidates->appends(request()->query())->previousPageUrl() }}"
                                                    aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>

                                            {{-- Pagination Elements --}}
                                            @foreach ($candidates->getUrlRange(1, $candidates->lastPage()) as $page => $url)
                                                <li
                                                    class="page-item {{ $page == $candidates->currentPage() ? 'active' : '' }}">
                                                    <a class="page-link"
                                                        href="{{ $candidates->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            <li class="page-item {{ !$candidates->hasMorePages() ? 'disabled' : '' }}">
                                                <a class="page-link"
                                                    href="{{ $candidates->appends(request()->query())->nextPageUrl() }}"
                                                    aria-label="Next">
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
    <style>
        /* Custom styles */
        .card-primary.card-outline {
            border-top: 3px solid #007bff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .badge.bg-indigo {
            background-color: #6610f2;
            color: white;
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

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.765625rem;
            line-height: 1.5;
            border-radius: 0.2rem;
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
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            // Enable tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select a position",
                allowClear: true
            });

            // Set default dates if empty
            if (!$('#date_from').val()) {
                $('#date_from').val(new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().substr(0,
                    10));
            }

            if (!$('#date_to').val()) {
                $('#date_to').val(new Date().toISOString().substr(0, 10));
            }
        });
    </script>
@endpush
