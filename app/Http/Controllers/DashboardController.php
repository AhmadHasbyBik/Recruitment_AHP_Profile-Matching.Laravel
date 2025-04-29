<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vacancy;
use App\Models\Candidate;
use App\Models\ProfileMatchingResult;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Periksa role dengan cara yang lebih aman
        $isAdmin = $user->role === 'super_admin';
        $isHRD = $user->role === 'hrd';
        
        if ($isAdmin || $isHRD) {
            // Tampilkan semua data untuk admin/HRD
            $vacancies = Vacancy::count();
            $candidates = Candidate::count();
            $processed = ProfileMatchingResult::count();
            
            $latestResults = ProfileMatchingResult::with(['candidate', 'processedBy'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } else {
            // Tampilkan data terbatas untuk user biasa
            $vacancies = Vacancy::where('is_active', true)->count();
            $candidates = 0;
            $processed = 0;
            
            $latestResults = ProfileMatchingResult::with(['candidate', 'processedBy'])
                ->whereHas('candidate', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }
        
        return view('dashboard', compact(
            'vacancies', 
            'candidates', 
            'processed', 
            'latestResults',
            'user'
        ));
    }
}