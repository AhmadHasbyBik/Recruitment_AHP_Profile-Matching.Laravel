<!-- resources/views/profile_matching/history/show.blade.php -->
@extends('layouts.app')

@section('title', 'Profile Matching Detail')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('profile-matching.history.index') }}">History</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Profile Matching</h3>
                        <div class="card-tools">
                            <a href="{{ route('profile-matching.history.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h4>Informasi Kandidat</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Nama</th>
                                        <td>{{ $result->candidate->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Lowongan</th>
                                        <td>{{ $result->candidate->vacancy->position }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ranking</th>
                                        <td>{{ $result->rank }}</td>
                                    </tr>
                                    <tr>
                                        <th>Skor Akhir</th>
                                        <td>{{ number_format($result->final_score, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>Informasi Proses</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Diproses Pada</th>
                                        <td>{{ $result->processed_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Diproses Oleh</th>
                                        <td>{{ $result->processedBy->name ?? 'System' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Catatan</th>
                                        <td>{{ $result->notes ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <h4>Detail Penilaian</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Kriteria</th>
                                        <th>Tipe</th>
                                        <th>Nilai Kandidat</th>
                                        <th>Nilai Ideal</th>
                                        <th>Gap</th>
                                        <th>Bobot AHP</th>
                                        <th>Skor Tertimbang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalWeight = 0;
                                        $totalWeightedGap = 0;
                                    @endphp

                                    @foreach ($result->criteriaValues as $value)
                                        @php
                                            $criteria = $value->criteria;
                                            $idealValue = $value->ideal_value;
                                            $gap = abs($idealValue - $value->value);
                                            $weightedGap = $gap * $criteria->ahp_weight;
                                            $totalWeight += $criteria->ahp_weight;
                                            $totalWeightedGap += $weightedGap;
                                        @endphp
                                        <tr>
                                            <td>{{ $criteria->name }}</td>
                                            <td><span
                                                    class="badge {{ $criteria->type == 'core' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($criteria->type) }}
                                                </span></td>
                                            <td>{{ $value->value }}</td>
                                            <td>{{ $idealValue }}</td>
                                            <td>{{ $gap }}</td>
                                            <td>{{ number_format($criteria->ahp_weight * 100, 2) }}%</td>
                                            <td>{{ number_format($weightedGap, 4) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr class="table-active font-weight-bold">
                                        <td colspan="5" class="text-right">Total</td>
                                        <td>{{ number_format($totalWeight * 100, 2) }}%</td>
                                        <td>{{ number_format($totalWeightedGap, 4) }}</td>
                                    </tr>
                                    <tr class="table-primary font-weight-bold">
                                        <td colspan="6" class="text-right">Rata-rata Gap Tertimbang</td>
                                        <td>{{ $totalWeight > 0 ? number_format($totalWeightedGap / $totalWeight, 4) : 0 }}
                                        </td>
                                    </tr>
                                    <tr class="table-success font-weight-bold">
                                        <td colspan="6" class="text-right">Skor Final (100 - (Avg Gap * 20))</td>
                                        <td>{{ number_format($result->final_score, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <h4>Keterangan:</h4>
                            <ul>
                                <li><strong>Nilai Ideal</strong>: Nilai yang diharapkan untuk setiap kriteria pada lowongan
                                    ini</li>
                                <li><strong>Gap</strong>: Selisih antara nilai kandidat dan nilai ideal</li>
                                <li><strong>Skor Tertimbang</strong>: Gap dikalikan dengan bobot AHP kriteria</li>
                                <li><strong>Skor Final</strong>: Dihitung dengan formula 100 - (Rata-rata Gap Tertimbang ×
                                    20)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
