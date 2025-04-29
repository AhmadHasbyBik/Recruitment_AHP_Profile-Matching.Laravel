<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\AhpPairwiseComparison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AhpController extends Controller
{
    public function index()
    {
        $criterias = Criteria::orderBy('id')->get();
        $comparisons = AhpPairwiseComparison::with(['criteria1', 'criteria2'])
            ->orderBy('criteria1_id')
            ->orderBy('criteria2_id')
            ->get();

        $calculationResult = $this->calculateAhp($criterias, $comparisons);

        return view('ahp.index', array_merge([
            'criterias' => $criterias,
            'comparisons' => $comparisons,
        ], $calculationResult));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'criteria1_id' => 'required|exists:criterias,id',
            'criteria2_id' => 'required|exists:criterias,id|different:criteria1_id',
            'value' => 'required|numeric|min:0.11|max:9',
        ]);

        // Pastikan criteria1_id selalu lebih kecil untuk menghindari duplikasi
        $criteria1Id = min($validated['criteria1_id'], $validated['criteria2_id']);
        $criteria2Id = max($validated['criteria1_id'], $validated['criteria2_id']);
        $value = $validated['criteria1_id'] < $validated['criteria2_id']
            ? $validated['value']
            : 1 / $validated['value'];

        DB::transaction(function () use ($criteria1Id, $criteria2Id, $value) {
            // Simpan perbandingan
            AhpPairwiseComparison::updateOrCreate(
                [
                    'criteria1_id' => $criteria1Id,
                    'criteria2_id' => $criteria2Id
                ],
                ['value' => $value]
            );

            // Simpan kebalikannya
            if ($criteria1Id != $criteria2Id) {
                AhpPairwiseComparison::updateOrCreate(
                    [
                        'criteria1_id' => $criteria2Id,
                        'criteria2_id' => $criteria1Id
                    ],
                    ['value' => 1 / $value]
                );
            }
        });

        return redirect()->route('ahp.index')
            ->with('success', 'Perbandingan berhasil disimpan dan bobot diperbarui.');
    }

    public function destroy(AhpPairwiseComparison $comparison)
    {
        DB::transaction(function () use ($comparison) {
            // Hapus juga perbandingan kebalikannya
            if ($comparison->criteria1_id != $comparison->criteria2_id) {
                AhpPairwiseComparison::where([
                    'criteria1_id' => $comparison->criteria2_id,
                    'criteria2_id' => $comparison->criteria1_id
                ])->delete();
            }

            $comparison->delete();
        });

        return redirect()->route('ahp.index')
            ->with('success', 'Perbandingan berhasil dihapus dan bobot diperbarui.');
    }

    private function calculateAhp($criterias, $comparisons)
    {
        $size = $criterias->count();
        if ($size === 0) {
            return [
                'matrix' => [],
                'weights' => [],
                'consistency' => null,
                'steps' => []
            ];
        }

        // Step 1: Matriks Perbandingan
        $matrix = array_fill(0, $size, array_fill(0, $size, 0));
        foreach ($criterias as $i => $criteria1) {
            foreach ($criterias as $j => $criteria2) {
                if ($i == $j) {
                    $matrix[$i][$j] = 1;
                    continue;
                }

                $comparison = $comparisons->first(function ($item) use ($criteria1, $criteria2) {
                    return ($item->criteria1_id == $criteria1->id && $item->criteria2_id == $criteria2->id);
                });

                $matrix[$i][$j] = $comparison ? $comparison->value : 0;
            }
        }

        // Step 2: Hitung Jumlah Kolom
        $columnSums = array_fill(0, $size, 0);
        for ($j = 0; $j < $size; $j++) {
            for ($i = 0; $i < $size; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        // Step 3: Matriks Normalisasi
        $normalizedMatrix = array_fill(0, $size, array_fill(0, $size, 0));
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $normalizedMatrix[$i][$j] = $columnSums[$j] != 0 ? $matrix[$i][$j] / $columnSums[$j] : 0;
            }
        }

        // Step 4: Hitung Prioritas (Bobot)
        $weights = array_fill(0, $size, 0);
        $rowAverages = array_fill(0, $size, 0);
        for ($i = 0; $i < $size; $i++) {
            $rowSum = 0;
            for ($j = 0; $j < $size; $j++) {
                $rowSum += $normalizedMatrix[$i][$j];
            }
            $rowAverages[$i] = $rowSum;
            $weights[$i] = $rowSum / $size;
        }

        // Step 5: Hitung Consistency
        $consistency = $this->calculateConsistency($matrix, $weights);

        // Format weights untuk ditampilkan
        $formattedWeights = [];
        foreach ($criterias as $index => $criteria) {
            $formattedWeights[$criteria->id] = $weights[$index];
        }

        // Prepare step-by-step data
        $steps = [
            'matrix' => $matrix,
            'column_sums' => $columnSums,
            'normalized_matrix' => $normalizedMatrix,
            'row_averages' => $rowAverages,
            'weights' => $weights
        ];

        return [
            'matrix' => $matrix,
            'normalized_matrix' => $normalizedMatrix,
            'weights' => $formattedWeights,
            'consistency' => $consistency,
            'steps' => $steps
        ];
    }

    private function calculateConsistency($matrix, $weights)
    {
        $size = count($weights);
        if ($size <= 2) {
            return [
                'lambda_max' => 0,
                'ci' => 0,
                'ri' => 0,
                'cr' => 0,
                'is_consistent' => true,
                'eigen_values' => [],
                'row_sums' => []
            ];
        }

        // Hitung jumlah kolom (column sums) dari matriks perbandingan awal
        $columnSums = array_fill(0, $size, 0);
        for ($j = 0; $j < $size; $j++) {
            for ($i = 0; $i < $size; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        // Hitung eigen values (column sums * bobot)
        $eigenValues = [];
        for ($i = 0; $i < $size; $i++) {
            $eigenValues[$i] = $columnSums[$i] * $weights[$i];
        }

        $lambdaMax = array_sum($eigenValues);

        // Calculate Consistency Index (CI) - Diubah ke (λ_max - n)/n
        $ci = ($lambdaMax - $size) / ($size - 1); // Sebelumnya ($size - 1)

        // Random Index (RI) values tetap sama
        $riValues = [
            1 => 0,
            2 => 0,
            3 => 0.58,
            4 => 0.9,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49
        ];

        $ri = $riValues[$size] ?? 0;
        $cr = ($ri > 0) ? ($ci / $ri) : 0;

        return [
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'ri' => $ri,
            'cr' => $cr,
            'is_consistent' => $cr < 0.1,
            'eigen_values' => $eigenValues,
            'column_sums' => $columnSums
        ];
    }
}
