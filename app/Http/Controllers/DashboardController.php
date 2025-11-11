<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Finding; // Perbaikan: Finding bukan Findings
use App\Models\Departemen;
use App\Models\Category;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistik umum
        $totalUsers = User::count();
        $totalDepartemen = Departemen::count();
        $totalCategories = Category::count();
        $totalGrades = Grade::count();
        
        // Default values untuk findings
        $totalFindings = 0;
        $findingsOpen = 0;
        $findingsInProgress = 0;
        $findingsCompleted = 0;
        $findingsClosed = 0;
        $recentFindings = collect();
        $findingsByDepartment = collect();
        $findingsByCategory = collect();
        
        // Cek apakah tabel findings ada
        try {
            // Statistik findings - PERBAIKAN: Finding bukan Findings
            $totalFindings = Finding::count();
            $findingsOpen = Finding::where('status', 'OPEN')->count();
            $findingsInProgress = Finding::where('status', 'IN_PROGRESS')->count();
            $findingsCompleted = Finding::where('status', 'COMPLETED')->count();
            $findingsClosed = Finding::where('status', 'CLOSED')->count();
            
            // Recent findings (5 terbaru) - PERBAIKAN: recentFindings bukan recentFindingss
            $recentFindings = Finding::with(['reporter', 'department', 'category', 'grade'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            // Statistik per departemen
            $findingsByDepartment = DB::table('findings as f')
                ->join('departemen as d', 'f.department_id', '=', 'd.id')
                ->select('d.name', DB::raw('COUNT(*) as total'))
                ->groupBy('d.id', 'd.name')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
            
            // Statistik per kategori
            $findingsByCategory = DB::table('findings as f')
                ->join('categories as c', 'f.category_id', '=', 'c.id')
                ->select('c.name', DB::raw('COUNT(*) as total'))
                ->groupBy('c.id', 'c.name')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Jika tabel findings belum ada atau error, gunakan default values di atas
        }

        return view('dashboard.index', compact(
            'user',
            'totalUsers',
            'totalDepartemen',
            'totalCategories',
            'totalGrades',
            'totalFindings', // PERBAIKAN: totalFindings bukan totalFindingss
            'findingsOpen',
            'findingsInProgress',
            'findingsCompleted',
            'findingsClosed',
            'recentFindings', // PERBAIKAN: recentFindings bukan recentFindingss
            'findingsByDepartment',
            'findingsByCategory'
        ));
    }
}
