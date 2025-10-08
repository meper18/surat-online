@extends('layouts.app')

@section('title', 'Laporan Surat')

@section('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .filter-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
    }

    .filter-card .card-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border: none;
        border-radius: 15px 15px 0 0;
        padding: 20px;
    }

    .stats-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .stats-card.total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stats-card.diajukan {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .stats-card.diproses {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stats-card.selesai {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .stats-card.ditolak {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .stats-card .card-body {
        padding: 25px;
        text-align: center;
    }

    .stats-card .stats-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        opacity: 0.8;
    }

    .stats-card .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stats-card .stats-label {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 500;
    }

    .modern-table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        border: none;
        margin-bottom: 25px;
    }

    .modern-table .table {
        margin-bottom: 0;
    }

    .modern-table .table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 15px;
        font-weight: 600;
    }

    .modern-table .table tbody tr {
        transition: all 0.3s ease;
        border: none;
    }

    .modern-table .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .modern-table .table tbody td {
        padding: 15px;
        border: none;
        border-bottom: 1px solid #f8f9fa;
        vertical-align: middle;
    }

    .action-btn {
        border-radius: 8px;
        padding: 8px 12px;
        margin: 2px;
        transition: all 0.3s ease;
        border: none;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .status-badge {
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .filter-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 12px 20px;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .export-btn {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 12px 20px;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 233, 123, 0.4);
        color: white;
    }

    .reset-btn {
        background: linear-gradient(135deg, #a8a8a8 0%, #8c8c8c 100%);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 12px 20px;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .reset-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(168, 168, 168, 0.4);
        color: white;
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 2px;
        border: none;
        color: #667eea;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .statistics-table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .statistics-table .table thead th {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        color: #667eea;
        border: none;
        padding: 15px;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border-radius: 15px;
        margin: 30px 0;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1"><i class="fas fa-chart-bar me-2"></i>Laporan Surat</h4>
                    <p class="mb-0 opacity-75">Analisis dan laporan permohonan surat</p>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card filter-card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Laporan</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="jenis_surat_id" class="form-label fw-bold">Jenis Surat</label>
                            <select name="jenis_surat_id" id="jenis_surat_id" class="form-select">
                                <option value="">Semua Jenis Surat</option>
                                @foreach($jenisSurat as $jenis)
                                    <option value="{{ $jenis->id }}" 
                                        {{ request('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach($statusOptions as $key => $value)
                                    <option value="{{ $key }}" 
                                        {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="tanggal_mulai" class="form-label fw-bold">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                                   class="form-control" value="{{ request('tanggal_mulai') }}">
                        </div>
                        
                        <div class="col-md-2">
                            <label for="tanggal_selesai" class="form-label fw-bold">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                                   class="form-control" value="{{ request('tanggal_selesai') }}">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="search" class="form-label fw-bold">Cari Pemohon/Kode</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Nama, email, atau kode permohonan..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="filter-btn me-2">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.reports.index') }}" class="reset-btn me-3">
                                <i class="fas fa-times me-1"></i>Reset
                            </a>
                            
                            <!-- Export Buttons -->
                            <div class="btn-group" role="group">
                                <button type="button" class="export-btn dropdown-toggle" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-download me-1"></i>Export
                                </button>
                                <ul class="dropdown-menu" style="border-radius: 10px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="exportData('excel')" style="border-radius: 8px; margin: 2px;">
                                            <i class="fas fa-file-excel me-2 text-success"></i>Export Excel
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="exportData('pdf')" style="border-radius: 8px; margin: 2px;">
                                            <i class="fas fa-file-pdf me-2 text-danger"></i>Export PDF
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card stats-card total">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <div class="stats-number">{{ $statistics['total'] }}</div>
                        <div class="stats-label">Total</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card diajukan">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-number">{{ $statistics['diajukan'] }}</div>
                        <div class="stats-label">Diajukan</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card diproses">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="stats-number">{{ $statistics['diproses'] }}</div>
                        <div class="stats-label">Diproses</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card selesai">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-number">{{ $statistics['selesai'] }}</div>
                        <div class="stats-label">Selesai</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card ditolak">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stats-number">{{ $statistics['ditolak'] }}</div>
                        <div class="stats-label">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        @if($permohonan->count() > 0)
            <div class="modern-table">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode Permohonan</th>
                                <th>Pemohon</th>
                                <th>Jenis Surat</th>
                                <th>Status</th>
                                <th>Keperluan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permohonan as $index => $item)
                                <tr>
                                    <td><strong>{{ $permohonan->firstItem() + $index }}</strong></td>
                                    <td>
                                        <div>{{ $item->tanggal_permohonan->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $item->tanggal_permohonan->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary status-badge">{{ $item->kode_permohonan }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle me-2 text-muted"></i>
                                            <div>
                                                <div class="fw-bold">{{ $item->user->name }}</div>
                                                <small class="text-muted">{{ $item->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $item->jenisSurat->nama }}</span>
                                    </td>
                                    <td>
                                        @switch($item->status)
                                            @case('diajukan')
                                                <span class="badge status-badge bg-warning">Diajukan</span>
                                                @break
                                            @case('diproses')
                                                <span class="badge status-badge bg-primary">Diproses</span>
                                                @break
                                            @case('selesai')
                                                <span class="badge status-badge bg-success">Selesai</span>
                                                @break
                                            @case('ditolak')
                                                <span class="badge status-badge bg-danger">Ditolak</span>
                                                @break
                                            @default
                                                <span class="badge status-badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $item->keperluan }}">
                                            {{ $item->keperluan }}
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.permohonan.show', $item) }}" 
                                           class="btn btn-sm btn-outline-primary action-btn" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan {{ $permohonan->firstItem() }} sampai {{ $permohonan->lastItem() }} 
                    dari {{ $permohonan->total() }} data
                </div>
                <div>
                    {{ $permohonan->links() }}
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-chart-bar fa-4x text-muted mb-4"></i>
                <h5 class="text-muted mb-3">Tidak ada data laporan</h5>
                <p class="text-muted">Tidak ada data yang sesuai dengan filter yang dipilih</p>
            </div>
        @endif

        <!-- Statistics by Jenis Surat -->
        @if($statistics['by_jenis']->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);">
                        <div class="card-header" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border: none; border-radius: 15px 15px 0 0; padding: 20px;">
                            <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistik Berdasarkan Jenis Surat</h6>
                        </div>
                        <div class="card-body">
                            <div class="statistics-table">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-file-alt me-2"></i>Jenis Surat</th>
                                                <th><i class="fas fa-hashtag me-2"></i>Jumlah</th>
                                                <th><i class="fas fa-percentage me-2"></i>Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($statistics['by_jenis'] as $stat)
                                                <tr>
                                                    <td class="fw-bold">{{ $stat->nama }}</td>
                                                    <td>
                                                        <span class="badge bg-primary status-badge">{{ $stat->total }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress me-3" style="width: 100px; height: 8px;">
                                                                <div class="progress-bar" role="progressbar" 
                                                                     style="width: {{ $statistics['total'] > 0 ? ($stat->total / $statistics['total']) * 100 : 0 }}%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                                </div>
                                                            </div>
                                                            <span class="fw-bold">
                                                                @if($statistics['total'] > 0)
                                                                    {{ number_format(($stat->total / $statistics['total']) * 100, 1) }}%
                                                                @else
                                                                    0%
                                                                @endif
                                                            </span>
                                                        </div>
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
        @endif
    </div>
</div>

<script>
function exportData(type) {
    // Get current filter parameters from form inputs
    const jenisSuratId = document.querySelector('select[name="jenis_surat_id"]').value;
    const status = document.querySelector('select[name="status"]').value;
    const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]').value;
    const tanggalSelesai = document.querySelector('input[name="tanggal_selesai"]').value;
    const search = document.querySelector('input[name="search"]').value;
    
    // Build query parameters
    const params = new URLSearchParams();
    if (jenisSuratId) params.append('jenis_surat_id', jenisSuratId);
    if (status) params.append('status', status);
    if (tanggalMulai) params.append('tanggal_mulai', tanggalMulai);
    if (tanggalSelesai) params.append('tanggal_selesai', tanggalSelesai);
    if (search) params.append('search', search);
    
    // Create export URL
    let exportUrl;
    if (type === 'excel') {
        exportUrl = '{{ route("admin.reports.export.excel") }}?' + params.toString();
    } else if (type === 'pdf') {
        exportUrl = '{{ route("admin.reports.export.pdf") }}?' + params.toString();
    }
    
    // Open export URL
    window.open(exportUrl, '_blank');
}
</script>
@endsection