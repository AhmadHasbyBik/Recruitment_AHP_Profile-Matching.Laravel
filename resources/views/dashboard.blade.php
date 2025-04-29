<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    @if($user->isSuperAdmin() || $user->isHRD())
        <!-- Tampilan untuk Admin/HRD -->
        <div class="row">
            <!-- List Pekerjaan Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $vacancies }}</h3>
                        <p>List Pekerjaan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <a href="{{ route('vacancies.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Candidates Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $candidates }}</h3>
                        <p>Kandidat Pelamar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('candidates.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Processed Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $processed }}</h3>
                        <p>Kandidat yang Sudah Diproses</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <a href="{{ route('profile-matching.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Process Now Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>SPK</h3>
                        <p>Profile Matching</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <a href="{{ route('profile-matching.index') }}" class="small-box-footer">
                        Process Now <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Tampilan untuk User Biasa -->
        <div class="row">
            <!-- Vacancies Card -->
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $vacancies }}</h3>
                        <p>Open Vacancies</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <a href="{{ route('vacancies.index') }}" class="small-box-footer">
                        View Vacancies <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- My Candidates Card -->
            <div class="col-lg-4 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $latestResults->count() }}</h3>
                        <p>My Processed Candidates</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('profile-matching.index') }}" class="small-box-footer">
                        View Results <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Add Candidate Card -->
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>+</h3>
                        <p>Add New Candidate</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <a href="{{ route('candidates.create') }}" class="small-box-footer">
                        Add Now <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Latest Results -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        @if($user->isSuperAdmin() || $user->isHRD())
                            Hasil Profile Matching Terbaru
                        @else
                            My Latest Results
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    @if($latestResults->isEmpty())
                        <p>No results available yet.</p>
                    @else
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Candidate</th>
                                    <th>Score</th>
                                    <th>Date</th>
                                    @if($user->isSuperAdmin() || $user->isHRD())
                                        <th>Processed By</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestResults as $result)
                                <tr>
                                    <td>{{ $result->rank }}</td>
                                    <td>{{ $result->candidate->name }}</td>
                                    <td>{{ number_format($result->final_score, 2) }}</td>
                                    <td>{{ $result->created_at->format('d M Y H:i') }}</td>
                                    @if($user->isSuperAdmin() || $user->isHRD())
                                        <td>{{ $result->processedBy->name ?? 'System' }}</td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection