@extends('layouts.app')

@section('title', 'Perhitungan AHP')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Analytic Hierarchy Process (AHP) - Step by Step</h3>
                    </div>
                    <div class="card-body">
                        @include('partials.alert')

                        <!-- Step 1: Matriks Perbandingan -->
                        <div class="card card-info mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Step 1: Matriks Perbandingan Berpasangan</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Kriteria</th>
                                                @foreach ($criterias as $criteria)
                                                    <th class="text-center">{{ $criteria->code }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($criterias as $i => $criteria1)
                                                <tr>
                                                    <td class="font-weight-bold bg-light">{{ $criteria1->code }}</td>
                                                    @foreach ($criterias as $j => $criteria2)
                                                        <td class="text-center">
                                                            @if ($i == $j)
                                                                <span class="badge bg-primary">1</span>
                                                            @else
                                                                <span class="badge bg-secondary">
                                                                    {{ number_format($steps['matrix'][$i][$j], 4) }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Jumlah Kolom -->
                        <div class="card card-info mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Step 2: Jumlah Kolom</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Kriteria</th>
                                                @foreach ($criterias as $criteria)
                                                    <th class="text-center">{{ $criteria->code }}</th>
                                                @endforeach
                                                <th class="text-center">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($criterias as $i => $criteria1)
                                                <tr>
                                                    <td class="font-weight-bold bg-light">{{ $criteria1->code }}</td>
                                                    @foreach ($criterias as $j => $criteria2)
                                                        <td class="text-center">
                                                            {{ number_format($steps['matrix'][$i][$j], 4) }}
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center font-weight-bold bg-light">
                                                        {{ number_format(array_sum($steps['matrix'][$i]), 4) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-success text-white">
                                                <td class="font-weight-bold">Jumlah Kolom</td>
                                                @foreach ($steps['column_sums'] as $sum)
                                                    <td class="text-center font-weight-bold">
                                                        {{ number_format($sum, 4) }}
                                                    </td>
                                                @endforeach
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Matriks Normalisasi -->
                        <div class="card card-info mb-4">
                            <div class="card-header">
                                <h3 class="card-title mr-2">Step 3: Matriks Normalisasi</h3>
                                <small>(Setiap elemen dibagi dengan jumlah kolomnya)</small>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Kriteria</th>
                                                @foreach ($criterias as $criteria)
                                                    <th class="text-center">{{ $criteria->code }}</th>
                                                @endforeach
                                                <th class="text-center">Jumlah Baris</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($criterias as $i => $criteria1)
                                                <tr>
                                                    <td class="font-weight-bold bg-light">{{ $criteria1->code }}</td>
                                                    @foreach ($criterias as $j => $criteria2)
                                                        <td class="text-center">
                                                            {{ number_format($steps['normalized_matrix'][$i][$j], 4) }}
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center font-weight-bold bg-light">
                                                        {{ number_format($steps['row_averages'][$i], 4) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Hitung Bobot Prioritas -->
                        <div class="card card-success mb-4">
                            <div class="card-header">
                                <h3 class="card-title mr-2">Step 4: Hitung Bobot Prioritas</h3>
                                <small>(Jumlah baris dibagi jumlah kriteria)</small>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="bg-light">
                                                <th>Kriteria</th>
                                                <th class="text-right">Jumlah Baris</th>
                                                <th class="text-right">Bobot (Jumlah Baris/n)</th>
                                                <th class="text-right">Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($criterias as $index => $criteria)
                                                <tr>
                                                    <td>{{ $criteria->code }} - {{ $criteria->name }}</td>
                                                    <td class="text-right">
                                                        {{ number_format($steps['row_averages'][$index], 4) }}</td>
                                                    <td class="text-right">
                                                        {{ number_format($steps['weights'][$index], 4) }}</td>
                                                    <td class="text-right">
                                                        {{ number_format($steps['weights'][$index] * 100, 2) }}%</td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-light">
                                                <td class="font-weight-bold">Total</td>
                                                <td class="text-right font-weight-bold">
                                                    {{ number_format(array_sum($steps['row_averages']), 4) }}
                                                </td>
                                                <td class="text-right font-weight-bold">1.0000</td>
                                                <td class="text-right font-weight-bold">100%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Hitung Consistency -->
                        <div class="card card-{{ $consistency['is_consistent'] ? 'success' : 'danger' }} mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Step 5: Uji Konsistensi</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="row no-gutters">
                                    <!-- Bagian Kiri - Perhitungan Eigen Value -->
                                    <div class="col-md-6 p-4 border-right">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="mb-0">Perhitungan Eigen Value</h4>
                                            <span
                                                class="badge badge-{{ $consistency['is_consistent'] ? 'success' : 'danger' }}">
                                                {{ $consistency['is_consistent'] ? 'Konsisten' : 'Tidak Konsisten' }}
                                            </span>
                                        </div>

                                        <!-- Di bagian tabel eigen value -->
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="bg-lightblue">
                                                    <tr>
                                                        <th class="border-top-0">Kriteria</th>
                                                        <th class="border-top-0 text-right">Jumlah Baris</th>
                                                        <th class="border-top-0 text-right">Bobot</th>
                                                        <th class="border-top-0 text-right">Eigen Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($criterias as $index => $criteria)
                                                        <tr>
                                                            <td class="font-weight-medium">{{ $criteria->code }}</td>
                                                            <td class="text-right">
                                                                {{ number_format($steps['row_averages'][$index], 4) }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ number_format($steps['weights'][$index], 4) }}
                                                            </td>
                                                            <td class="text-right font-weight-bold">
                                                                {{ number_format($consistency['eigen_values'][$index], 4) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr class="bg-gray-100">
                                                        <td class="font-weight-bold">TOTAL</td>
                                                        <td class="text-right font-weight-bold">
                                                            {{ number_format(array_sum($steps['row_averages']), 4) }}
                                                        </td>
                                                        <td class="text-right font-weight-bold">1.0000</td>
                                                        <td class="text-right font-weight-bold text-primary">
                                                            {{ number_format($consistency['lambda_max'], 4) }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Bagian Kanan - Hasil Konsistensi -->
                                    <div class="col-md-6 p-4">
                                        <div class="text-center mb-4">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <h3 class="display-4 font-weight-bold mb-0 mr-2">
                                                    {{ number_format($consistency['cr'], 4) }}
                                                </h3>
                                                <div>
                                                    <span class="d-block text-sm">Consistency</span>
                                                    <span class="d-block text-sm">Ratio (CR)</span>
                                                </div>
                                            </div>

                                            <!-- Progress Bar -->
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated 
                            {{ $consistency['is_consistent'] ? 'bg-success' : 'bg-danger' }}"
                                                    role="progressbar"
                                                    style="width: {{ min(100, $consistency['cr'] * 500) }}%"
                                                    aria-valuenow="{{ $consistency['cr'] * 100 }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    <span
                                                        class="font-weight-bold">{{ number_format($consistency['cr'] * 100, 1) }}%</span>
                                                </div>
                                            </div>

                                            <!-- Alert Status -->
                                            <div
                                                class="alert alert-{{ $consistency['is_consistent'] ? 'success' : 'danger' }} mt-3 mb-4 text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i
                                                        class="fas {{ $consistency['is_consistent'] ? 'fa-check-circle' : 'fa-exclamation-triangle' }} fa-2x mr-3"></i>
                                                    <div>
                                                        <h5 class="alert-heading mb-1">
                                                            {{ $consistency['is_consistent'] ? 'KONSISTEN' : 'TIDAK KONSISTEN' }}
                                                        </h5>
                                                        <p class="mb-0 small">
                                                            {{ $consistency['is_consistent'] ? 'Matriks perbandingan konsisten (CR < 0.10)' : 'Matriks tidak konsisten (CR ≥ 0.10). Silakan periksa kembali perbandingan Anda.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detail Konsistensi -->
                                            <div class="card shadow-sm">
                                                <div class="card-body p-3">
                                                    <div class="row text-center">
                                                        <div class="col-4 border-right">
                                                            <div class="text-muted small">Lambda Max</div>
                                                            <div class="font-weight-bold">
                                                                {{ number_format($consistency['lambda_max'], 4) }}</div>
                                                        </div>
                                                        <div class="col-4 border-right">
                                                            <div class="text-muted small">CI</div>
                                                            <div class="font-weight-bold">
                                                                {{ number_format($consistency['ci'], 4) }}</div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="text-muted small">RI</div>
                                                            <div class="font-weight-bold">
                                                                {{ number_format($consistency['ri'], 4) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Form untuk menambah perbandingan -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Tambah Perbandingan</h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('ahp.store') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label>Kriteria Pertama</label>
                                        <select name="criteria1_id" class="form-control select2" required>
                                            @foreach ($criterias as $criteria)
                                                <option value="{{ $criteria->id }}">{{ $criteria->code }} -
                                                    {{ $criteria->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Kriteria Kedua</label>
                                        <select name="criteria2_id" class="form-control select2" required>
                                            @foreach ($criterias as $criteria)
                                                <option value="{{ $criteria->id }}"
                                                    @if ($criteria->id == old('criteria2_id')) selected @endif>
                                                    {{ $criteria->code }} - {{ $criteria->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nilai Perbandingan</label>
                                        <input type="number" name="value" class="form-control" step="0.01"
                                            min="0.11" max="9" value="{{ old('value') }}" required>
                                        <small class="text-muted">
                                            Gunakan skala 1-9 (1 = sama penting, 9 = jauh lebih penting)
                                        </small>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-save mr-2"></i> Simpan
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Existing Comparisons Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-info">
                                        <h3 class="card-title">Daftar Perbandingan</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Kriteria 1</th>
                                                        <th>Nilai</th>
                                                        <th>Kriteria 2</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($comparisons->where('criteria1_id', '<', 'criteria2_id') as $comparison)
                                                        <tr>
                                                            <td>{{ $comparison->criteria1->code }} -
                                                                {{ $comparison->criteria1->name }}</td>
                                                            <td class="font-weight-bold">
                                                                {{ number_format($comparison->value, 2) }}
                                                            </td>
                                                            <td>{{ $comparison->criteria2->code }} -
                                                                {{ $comparison->criteria2->name }}</td>
                                                            <td>
                                                                <form action="{{ route('ahp.destroy', $comparison->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                                        onclick="return confirm('Hapus perbandingan ini?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-lightblue {
            background-color: #f8f9fa;
        }

        .bg-gray-100 {
            background-color: #f8f9fa;
        }

        .border-right {
            border-right: 1px solid #dee2e6 !important;
        }

        .progress-bar {
            position: relative;
            overflow: visible;
            color: #fff;
            font-size: 12px;
        }

        .progress-bar span {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-shadow: 0 0 2px #000;
        }

        .card-title {
            font-weight: 600;
        }

        .font-weight-medium {
            font-weight: 500;
        }
    </style>
@endpush

@push('styles')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .progress {
            height: 20px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@endpush
