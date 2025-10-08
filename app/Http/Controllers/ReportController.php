<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use App\Models\JenisSurat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,operator']);
    }

    /**
     * Display the report page with filters
     */
    public function index(Request $request)
    {
        // Get filter options
        $jenisSurat = JenisSurat::all();
        $statusOptions = [
            'diajukan' => 'Diajukan',
            'diproses' => 'Diproses', 
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak'
        ];

        // Build query with filters
        $query = $this->buildFilteredQuery($request);
        
        // Get paginated results
        $permohonan = $query->paginate(20);
        $permohonan->appends($request->query());

        // Get summary statistics
        $statistics = $this->getStatistics($request);

        return view('admin.reports.index', compact(
            'permohonan', 
            'jenisSurat', 
            'statusOptions', 
            'statistics'
        ));
    }

    /**
     * Export reports to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $filters = $request->only(['jenis_surat_id', 'status', 'tanggal_mulai', 'tanggal_selesai', 'search']);
        
        $export = new \App\Exports\ReportExport($query, $filters);
        $csvContent = $export->toCsv();
        $filename = $export->getFilename();
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Export reports to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $filters = $request->only(['jenis_surat_id', 'status', 'tanggal_mulai', 'tanggal_selesai', 'search']);
        
        $export = new \App\Exports\ReportPdfExport($query, $filters);
        $pdf = $export->toPdf();
        $filename = $export->getFilename();
        
        return $pdf->download($filename);
    }

    /**
     * Build filtered query based on request parameters
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = Permohonan::with(['user', 'jenisSurat'])
            ->select('permohonans.*');

        // Filter by jenis surat
        if ($request->filled('jenis_surat_id') && $request->jenis_surat_id != '') {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }

        // Filter by status
        if ($request->filled('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_permohonan', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_permohonan', '<=', $request->tanggal_selesai);
        }

        // Filter by pemohon (search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            })->orWhere('kode_permohonan', 'like', '%' . $search . '%')
              ->orWhere('keperluan', 'like', '%' . $search . '%');
        }

        return $query->orderBy('tanggal_permohonan', 'desc');
    }

    /**
     * Get statistics for the filtered data
     */
    private function getStatistics(Request $request)
    {
        $baseQuery = $this->buildFilteredQuery($request);
        
        return [
            'total' => $baseQuery->count(),
            'diajukan' => (clone $baseQuery)->where('status', 'diajukan')->count(),
            'diproses' => (clone $baseQuery)->where('status', 'diproses')->count(),
            'selesai' => (clone $baseQuery)->where('status', 'selesai')->count(),
            'ditolak' => (clone $baseQuery)->where('status', 'ditolak')->count(),
            'by_jenis' => (clone $baseQuery)
                ->join('jenis_surats', 'permohonans.jenis_surat_id', '=', 'jenis_surats.id')
                ->select('jenis_surats.nama', DB::raw('count(*) as total'))
                ->groupBy('jenis_surats.id', 'jenis_surats.nama')
                ->get()
        ];
    }

    /**
     * Get filter information for display
     */
    private function getFilterInfo(Request $request)
    {
        $info = [];
        
        if ($request->filled('jenis_surat_id')) {
            $jenisSurat = JenisSurat::find($request->jenis_surat_id);
            $info['jenis_surat'] = $jenisSurat ? $jenisSurat->nama : 'Semua';
        } else {
            $info['jenis_surat'] = 'Semua';
        }

        $info['status'] = $request->filled('status') ? ucfirst($request->status) : 'Semua';
        $info['tanggal_mulai'] = $request->filled('tanggal_mulai') ? 
            Carbon::parse($request->tanggal_mulai)->format('d/m/Y') : 'Tidak dibatasi';
        $info['tanggal_selesai'] = $request->filled('tanggal_selesai') ? 
            Carbon::parse($request->tanggal_selesai)->format('d/m/Y') : 'Tidak dibatasi';
        $info['search'] = $request->filled('search') ? $request->search : 'Tidak ada';

        return $info;
    }

    /**
     * Generate Excel export (using simple HTML table approach)
     */
    private function generateExcelExport($data, $filename, $request)
    {
        $filterInfo = $this->getFilterInfo($request);
        $statistics = $this->getStatistics($request);

        // Create Excel content as HTML
        $html = view('admin.reports.excel', compact('data', 'filterInfo', 'statistics'))->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }
}