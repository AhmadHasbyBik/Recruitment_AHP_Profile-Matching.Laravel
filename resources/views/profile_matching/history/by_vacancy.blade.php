<!-- resources/views/profile_matching/history/by_vacancy.blade.php -->
@extends('layouts.app')

@section('title', 'Profile Matching History per Lowongan')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">History per Lowongan</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $vacancy->position }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('profile-matching.history.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Kandidat</span>
                                        <span class="info-box-number">{{ $results->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-success"><i class="fas fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Tanggal Tutup</span>
                                        <span
                                            class="info-box-number">{{ \Carbon\Carbon::parse($vacancy->close_date)->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Nama Kandidat</th>
                                        <th>Skor Akhir</th>
                                        <th>Diproses Pada</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $result)
                                        <tr>
                                            <td>{{ $result->rank }}</td>
                                            <td>{{ $result->candidate->name }}</td>
                                            <td>{{ number_format($result->final_score, 2) }}</td>
                                            <td>{{ $result->processed_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('profile-matching.history.show', $result->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
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
