<?php

namespace App\Http\Controllers;

use App\Models\Finding;
use App\Models\Category;
use App\Models\Grade;
use App\Models\Departemen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FindingController extends Controller
{
    public function index()
{
    $user = Auth::user();
    
    switch ($user->role) {
        case 'SAFETY_PATROLLER':
            // Safety Patroller bisa lihat semua status temuannya sendiri
            $findings = Finding::with(['category', 'grade', 'departemen'])
                ->where('reporter_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            break;

        case 'SAFETY_ADMIN':
            // Safety Admin bisa lihat SEMUA temuan dengan semua status
            $findings = Finding::with(['category', 'grade', 'departemen', 'reporter', 'pic'])
                ->orderBy('created_at', 'desc')
                ->get();
            break;

        case 'DEPT_PIC':
            // PIC Dept bisa lihat temuan IN_PROGRESS, COMPLETED, dan CLOSED departemennya
            $findings = Finding::with(['category', 'grade', 'departemen', 'reporter'])
                ->where('department_id', $user->departemen_id)
                ->whereIn('status', ['IN_PROGRESS', 'COMPLETED', 'CLOSED'])
                ->orderBy('created_at', 'desc')
                ->get();
            break;

        case 'DEPT_MANAGER':
            // Dept Manager bisa lihat semua status temuan departemennya
            $findings = Finding::with(['category', 'grade', 'departemen', 'reporter', 'pic'])
                ->where('department_id', $user->departemen_id)
                ->orderBy('created_at', 'desc')
                ->get();
            break;

        default:
            $findings = collect();
    }

    $categories = Category::all();
    $grades = Grade::all();
    $departemens = Departemen::all();
    
    return view('findings.index', compact('findings', 'categories', 'grades', 'departemens', 'user'));
}


    public function update(Request $request, Finding $finding)
    {
        if (Auth::user()->role !== 'SAFETY_ADMIN') {
            return back()->with('error', 'Anda tidak memiliki akses');
        }

        $validated = $request->validate([
            'departemen_id' => 'required|exists:departemen,id',
            'category_id' => 'required|exists:categories,id',
            'grade_id' => 'required|exists:grades,id',
            'section' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Mapping departemen_id ke department_id
        $validated['department_id'] = $validated['departemen_id'];
        unset($validated['departemen_id']);

        $finding->update($validated);

        return redirect()->route('findings.index')
            ->with('success', 'Temuan berhasil diupdate');
    }

    private function generateFindingNumber($departmentId)
    {
        $departemen = Departemen::find($departmentId);
        $date = Carbon::now();

        // Format nama departemen: Hapus spasi, ambil semuanya, uppercase
        $deptCode = strtoupper(str_replace(' ', '', $departemen->name));

        // Format: DD-MM-YYYY-NAMADEPT-001
        $prefix = $date->format('d-m-Y') . '-' . $deptCode;

        // Hitung nomor urut untuk departemen dan bulan ini
        $count = Finding::where('department_id', $departmentId)
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();

        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return $prefix . '-' . $sequence;
    }

    private function uploadAndCompressImage($file, $path)
    {
        $filename = time() . '_' . uniqid() . '.jpg';
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $sourceImage = imagecreatefromstring(file_get_contents($file));
        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);

        if ($width > 1200) {
            $newWidth = 1200;
            $newHeight = ($height / $width) * $newWidth;
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagejpeg($newImage, $fullPath . '/' . $filename, 80);

        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $path . '/' . $filename;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'grade_id' => 'required|exists:grades,id',
            'description' => 'required|string',
            'section' => 'required|string|max:255',
            'departemen_id' => 'required|exists:departemen,id',
            'image_before' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($request->hasFile('image_before')) {
            $validated['image_before'] = $this->uploadAndCompressImage(
                $request->file('image_before'),
                'findings/before'
            );
        }

        // Ubah key dari departemen_id ke department_id
        $validated['department_id'] = $validated['departemen_id'];
        unset($validated['departemen_id']);

        $validated['finding_number'] = $this->generateFindingNumber($validated['department_id']);
        $validated['finding_date'] = Carbon::now();
        $validated['reporter_id'] = Auth::id();
        $validated['status'] = 'PENDING';

        $manager = User::where('departemen_id', $validated['department_id'])
            ->where('role', 'DEPT_MANAGER')
            ->first();

        if ($manager) {
            $validated['manager_id'] = $manager->id;
        }

        Finding::create($validated);

        return redirect()->route('findings.index')
            ->with('success', 'Temuan berhasil dilaporkan dengan nomor: ' . $validated['finding_number']);
    }

    public function show(Finding $finding)
{
    try {
        $finding->load(['category', 'grade', 'departemen', 'reporter', 'pic', 'manager', 'verifiedBy', 'closedBy']);
        
        // Format data untuk response
        $data = [
            'id' => $finding->id,
            'finding_number' => $finding->finding_number,
            'finding_date' => $finding->finding_date ? $finding->finding_date->format('d/m/Y') : '-',
            'department' => $finding->departemen->name ?? '-',
            'category' => $finding->category->name ?? '-',
            'grade' => $finding->grade->code ?? '-',
            'section' => $finding->section ?? '-',
            'location' => $finding->location ?? '-',
            'description' => $finding->description ?? '-',
            'reporter' => $finding->reporter->name ?? '-',
            'pic' => $finding->pic->name ?? '-',
            'status' => $finding->status,
            'image_before' => $finding->image_before ? asset('storage/' . $finding->image_before) : null,
            'image_after' => $finding->image_after ? asset('storage/' . $finding->image_after) : null,
        ];
        
        return response()->json($data);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Gagal memuat data: ' . $e->getMessage()], 500);
    }
}


    /**
     * FUNGSI VERIFY YANG SUDAH DIPERBAIKI
     * - Tidak perlu modal lagi
     * - Otomatis assign ke semua PIC departemen
     * - Target date otomatis berdasarkan grade dari created_at
     */
    public function verify(Finding $finding)
    {
        if (Auth::user()->role !== 'SAFETY_ADMIN') {
            return back()->with('error', 'Anda tidak memiliki akses');
        }

        // Ambil grade untuk menentukan target selesai
        $grade = $finding->grade;
        
        // Hitung target date berdasarkan grade DARI CREATED_AT
        $days = $daysMapping[$grade->code] ?? 7; // Default 7 hari jika tidak ada
        $targetDate = $finding->created_at->copy()->addDays($days);
        
        // Ambil SEMUA PIC dari departemen yang bertanggung jawab
        $pics = User::where('departemen_id', $finding->department_id)
                    ->where('role', 'DEPT_PIC')
                    ->get();
        
        if ($pics->isEmpty()) {
            return back()->with('error', 'Tidak ada PIC di departemen ' . $finding->departemen->name);
        }
        
        // Update finding dengan PIC pertama (untuk backward compatibility dengan kolom pic_id)
        // Tapi semua PIC departemen bisa lihat karena filter di index() pakai department_id
        $finding->update([
            'pic_id' => $pics->first()->id, // PIC utama (first)
            'target_date' => $targetDate,
            'status' => 'IN_PROGRESS',
            'verified_by' => Auth::id(),
            'verified_at' => Carbon::now(),
        ]);
        
        $picNames = $pics->pluck('name')->join(', ');
        
        return redirect()->route('findings.index')
            ->with('success', "Temuan berhasil diverifikasi! Target selesai: {$targetDate->format('d/m/Y')} ({$days} hari). Diteruskan ke PIC: {$picNames}");
    }

public function updateActionPlan(Request $request, Finding $finding)
{
    if (Auth::user()->role !== 'DEPT_PIC') {
        return back()->with('error', 'Anda tidak memiliki akses');
    }

    $validated = $request->validate([
        'counter_action' => 'required|string',
        'image_after'    => 'required|image|mimes:jpeg,png,jpg',
    ]);

    if ($request->hasFile('image_after')) {
        $validated['image_after'] = $this->uploadAndCompressImage(
            $request->file('image_after'),
            'findings/after'
        );
    }

    $now = Carbon::now();

    $finding->update([
        // hilangkan action_plan sama sekali
        'counter_action'   => $validated['counter_action'],
        'image_after'      => $validated['image_after'],
        'completion_date'  => $now->toDateString(),
        'completion_time'  => $now->format('H:i'),
        'action_location'  => $finding->location,   // pakai lokasi dari temuan
        'pic_id'           => Auth::id(),           // PIC = user yang submit
        'status'           => 'COMPLETED',
    ]);

    return redirect()->route('findings.index')
        ->with('success', 'Update action berhasil, menunggu verifikasi Safety Admin');
}


    public function close(Request $request, Finding $finding)
{
    if (Auth::user()->role !== 'SAFETY_ADMIN') {
        return back()->with('error', 'Anda tidak memiliki akses');
    }

    $validated = $request->validate([
        'verification_result' => 'required|in:approved,rejected',
        'close_note' => 'nullable|string',
    ]);

    if ($validated['verification_result'] === 'approved') {
        $finding->update([
            'status' => 'CLOSED',
            'closed_by' => Auth::id(),
            'closed_at' => Carbon::now(),
            'close_note' => $validated['close_note'] ?? null,
        ]);
        $message = 'Counter action disetujui. Temuan berhasil ditutup (CLOSED).';
    } else {
        // Kembalikan ke IN_PROGRESS dan reset counter action
        $finding->update([
            'status' => 'IN_PROGRESS',
            'counter_action' => null,
            'image_after' => null,
            'completion_date' => null,
            'completion_time' => null,
            'close_note' => $validated['close_note'] ?? null,
        ]);
        $message = 'Counter action ditolak. Temuan dikembalikan ke PIC Dept untuk perbaikan.';
    }

    return redirect()->route('findings.index')
        ->with('success', $message);
}



    public function destroy(Finding $finding)
    {
        if (Auth::user()->role !== 'SAFETY_ADMIN' && $finding->reporter_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses');
        }

        if ($finding->image_before) {
            Storage::disk('public')->delete($finding->image_before);
        }

        if ($finding->image_after) {
            Storage::disk('public')->delete($finding->image_after);
        }

        $finding->delete();

        return redirect()->route('findings.index')
            ->with('success', 'Temuan berhasil dihapus');
    }
}
