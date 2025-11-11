<?php

namespace App\Http\Controllers;

use App\Models\Finding;
use App\Models\Category;
use App\Models\Grade;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanFindingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Data untuk filter
        $departemens = collect();
        if (in_array($user->role, ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN'])) {
            $departemens = Departemen::all();
        }

        // Jika tanggal tidak dipilih, return view dengan data kosong
        if (!$request->filled('start_date') || !$request->filled('end_date')) {
            return view('findings.laporan', [
                'findings' => collect(), // Collection kosong
                'departemens' => $departemens,
                'user' => $user
            ]);
        }

        // Base query dengan relasi
        $query = Finding::with(['category', 'grade', 'departemen', 'reporter', 'pic', 'verifiedBy']);

        // Filter berdasarkan role
        switch ($user->role) {
            case 'ADMINISTRATOR':
            case 'SAFETY_PATROLLER':
            case 'SAFETY_ADMIN':
                // Bisa lihat semua, tapi ada filter departemen
                if ($request->filled('department_id')) {
                    $query->where('department_id', $request->department_id);
                }
                break;

            case 'DEPT_PIC':
            case 'DEPT_MANAGER':
                // Hanya bisa lihat temuan departemennya
                $query->where('department_id', $user->departemen_id);
                break;

            default:
                $query->whereRaw('1 = 0'); // Tidak ada akses
        }

        // Filter tanggal (WAJIB)
        $query->whereDate('finding_date', '>=', $request->start_date);
        $query->whereDate('finding_date', '<=', $request->end_date);

        // Get data
        $findings = $query->orderBy('finding_date', 'desc')->get();

        return view('findings.laporan', compact('findings', 'departemens', 'user'));
    }

    public function show(Finding $finding)
    {
        $user = Auth::user();
        
        // Cek akses
        if (in_array($user->role, ['DEPT_PIC', 'DEPT_MANAGER'])) {
            if ($finding->department_id !== $user->departemen_id) {
                return response()->json(['error' => 'Tidak memiliki akses'], 403);
            }
        }

        $finding->load(['category', 'grade', 'departemen', 'reporter', 'pic', 'manager', 'verifiedBy', 'closedBy']);
        return response()->json($finding);
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        
        // Validasi: Tanggal harus diisi
        if (!$request->filled('start_date') || !$request->filled('end_date')) {
            return back()->with('error', 'Silakan pilih tanggal terlebih dahulu untuk export.');
        }
        
        // Query sama seperti index
        $query = Finding::with(['category', 'grade', 'departemen', 'reporter', 'pic', 'verifiedBy']);

        // Filter berdasarkan role
        switch ($user->role) {
            case 'ADMINISTRATOR':
            case 'SAFETY_PATROLLER':
            case 'SAFETY_ADMIN':
                if ($request->filled('department_id')) {
                    $query->where('department_id', $request->department_id);
                }
                break;

            case 'DEPT_PIC':
            case 'DEPT_MANAGER':
                $query->where('department_id', $user->departemen_id);
                break;

            default:
                return back()->with('error', 'Tidak memiliki akses');
        }

        // Filter tanggal (WAJIB)
        $query->whereDate('finding_date', '>=', $request->start_date);
        $query->whereDate('finding_date', '<=', $request->end_date);

        $findings = $query->orderBy('finding_date', 'desc')->get();

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'LAPORAN TEMUAN SAFETY PATROL');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Periode & Tanggal export
        $sheet->setCellValue('A2', 'Periode: ' . date('d/m/Y', strtotime($request->start_date)) . ' - ' . date('d/m/Y', strtotime($request->end_date)));
        $sheet->mergeCells('A2:J2');
        
        $sheet->setCellValue('A3', 'Tanggal Export: ' . now()->format('d/m/Y H:i'));
        $sheet->mergeCells('A3:J3');

        // Header kolom
        $headerRow = 5;
        $headers = [
            'A' => 'No',
            'B' => 'Tanggal Temuan',
            'C' => 'Patroller',
            'D' => 'Safety Admin',
            'E' => 'Lokasi',
            'F' => 'Section',
            'G' => 'Kategori',
            'H' => 'Grade',
            'I' => 'PIC',
            'J' => 'Status',
            'K' => 'Tanggal Close',
        ];

        // Tambahkan kolom Departemen untuk role tertentu
        if (in_array($user->role, ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN'])) {
            $headers = [
                'A' => 'No',
                'B' => 'Tanggal Temuan',
                'C' => 'Patroller',
                'D' => 'Safety Admin',
                'E' => 'Departemen',
                'F' => 'Lokasi',
                'G' => 'Section',
                'H' => 'Kategori',
                'I' => 'Grade',
                'J' => 'PIC',
                'K' => 'Status',
                'L' => 'Tanggal Close',
            ];
        }

        foreach ($headers as $col => $header) {
            $sheet->setCellValue($col . $headerRow, $header);
        }

        // Style header
        $headerRange = in_array($user->role, ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN']) 
            ? 'A' . $headerRow . ':L' . $headerRow 
            : 'A' . $headerRow . ':K' . $headerRow;
        
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Data
        $row = $headerRow + 1;
        $no = 1;

        foreach ($findings as $finding) {
            if (in_array($user->role, ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN'])) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $finding->finding_date ? $finding->finding_date->format('d/m/Y H:i') : '-');
                $sheet->setCellValue('C' . $row, $finding->reporter->name ?? '-');
                $sheet->setCellValue('D' . $row, $finding->verifiedBy->name ?? '-');
                $sheet->setCellValue('E' . $row, $finding->departemen->name ?? '-');
                $sheet->setCellValue('F' . $row, $finding->location ?? '-');
                $sheet->setCellValue('G' . $row, $finding->section ?? '-');
                $sheet->setCellValue('H' . $row, $finding->category->name ?? '-');
                $sheet->setCellValue('I' . $row, $finding->grade->code ?? '-');
                $sheet->setCellValue('J' . $row, $finding->pic->name ?? '-');
                $sheet->setCellValue('K' . $row, $finding->status);
                $sheet->setCellValue('L' . $row, $finding->closed_at ? $finding->closed_at->format('d/m/Y H:i') : '-');
            } else {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $finding->finding_date ? $finding->finding_date->format('d/m/Y H:i') : '-');
                $sheet->setCellValue('C' . $row, $finding->reporter->name ?? '-');
                $sheet->setCellValue('D' . $row, $finding->verifiedBy->name ?? '-');
                $sheet->setCellValue('E' . $row, $finding->location ?? '-');
                $sheet->setCellValue('F' . $row, $finding->section ?? '-');
                $sheet->setCellValue('G' . $row, $finding->category->name ?? '-');
                $sheet->setCellValue('H' . $row, $finding->grade->code ?? '-');
                $sheet->setCellValue('I' . $row, $finding->pic->name ?? '-');
                $sheet->setCellValue('J' . $row, $finding->status);
                $sheet->setCellValue('K' . $row, $finding->closed_at ? $finding->closed_at->format('d/m/Y H:i') : '-');
            }

            // Border untuk data
            $dataRange = in_array($user->role, ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN']) 
                ? 'A' . $row . ':L' . $row 
                : 'A' . $row . ':K' . $row;
            
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ]);

            $row++;
        }

        // Auto size columns
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Generate file
        $filename = 'Laporan_Temuan_' . now()->format('Y-m-d_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
