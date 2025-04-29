<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AhpPairwiseComparison extends Model
{
    use HasFactory;

    protected $fillable = ['criteria1_id', 'criteria2_id', 'value'];

    public function criteria1()
    {
        return $this->belongsTo(Criteria::class, 'criteria1_id');
    }

    public function criteria2()
    {
        return $this->belongsTo(Criteria::class, 'criteria2_id');
    }

    public static function calculateWeights()
    {
        $criterias = Criteria::orderBy('id')->get();
        $comparisons = self::with(['criteria1', 'criteria2'])
            ->orderBy('criteria1_id')
            ->orderBy('criteria2_id')
            ->get();

        $size = $criterias->count();
        if ($size === 0) {
            return [
                'matrix' => [],
                'weights' => [],
                'consistency' => null
            ];
        }

        // Initialize comparison matrix
        $matrix = array_fill(0, $size, array_fill(0, $size, 1));

        // Fill the matrix with comparison values
        foreach ($criterias as $i => $criteria1) {
            foreach ($criterias as $j => $criteria2) {
                if ($i == $j) continue;

                $comparison = $comparisons->first(function ($item) use ($criteria1, $criteria2) {
                    return ($item->criteria1_id == $criteria1->id && $item->criteria2_id == $criteria2->id);
                });

                if ($comparison) {
                    $matrix[$i][$j] = $comparison->value;
                    $matrix[$j][$i] = 1 / $comparison->value;
                }
            }
        }

        // Calculate column sums
        $columnSums = array_fill(0, $size, 0);
        for ($j = 0; $j < $size; $j++) {
            for ($i = 0; $i < $size; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        // Normalize matrix and calculate weights
        $weights = array_fill(0, $size, 0);
        $normalizedMatrix = array_fill(0, $size, array_fill(0, $size, 0));

        for ($i = 0; $i < $size; $i++) {
            $rowSum = 0;
            for ($j = 0; $j < $size; $j++) {
                $normalizedMatrix[$i][$j] = $matrix[$i][$j] / $columnSums[$j];
                $rowSum += $normalizedMatrix[$i][$j];
            }
            $weights[$i] = $rowSum / $size;
        }

        // Format weights for display and storage
        $formattedWeights = [];
        foreach ($criterias as $index => $criteria) {
            $formattedWeights[$criteria->id] = $weights[$index];
        }

        return [
            'matrix' => $matrix,
            'normalized_matrix' => $normalizedMatrix,
            'weights' => $formattedWeights,
            'consistency' => self::calculateConsistency($matrix, $weights)
        ];
    }

    private static function calculateConsistency($matrix, $weights)
    {
        $size = count($weights);
        if ($size <= 2) {
            return [
                'lambda_max' => 0,
                'ci' => 0,
                'ri' => 0,
                'cr' => 0,
                'is_consistent' => true
            ];
        }

        // Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $size; $i++) {
            $rowSum = 0;
            for ($j = 0; $j < $size; $j++) {
                $rowSum += $matrix[$i][$j] * $weights[$j];
            }
            $lambdaMax += $rowSum / $weights[$i];
        }
        $lambdaMax /= $size;

        // Calculate Consistency Index (CI)
        $ci = ($lambdaMax - $size) / ($size - 1);

        // Random Index (RI) values
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
            'is_consistent' => $cr < 0.1
        ];
    }
}
