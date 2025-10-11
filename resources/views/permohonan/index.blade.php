@extends('layouts.app')

@section('title', 'Daftar Permohonan Surat')

@section('styles')
<link href="{{ asset('css/mobile-first.css') }}" rel="stylesheet">
<style>
    /* Mobile-first optimized styles for permohonan index */
    .permohonan-container {
        padding-bottom: 80px; /* Space for mobile nav */
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .filter-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .filter-card .card-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border: none;
        border-radius: 15px 15px 0 0;
        padding: 15px 20px;
    }

    /* Mobile-optimized table styles */
    .mobile-table-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .desktop-table {
        display: none;
    }

    .mobile-card-list {
        display: block;
    }

    .permohonan-card {
        background: white;
        border-radius: 12px;
        margin-bottom: 15px;
        padding: 15px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 4px solid #667eea;
    }

    .permohonan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    .permohonan-header {
        display: flex;
        justify-content: between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .permohonan-title {
        font-weight: 600;
        color: #667eea;
        margin-bottom: 5px;
        font-size: 1rem;
    }

    .permohonan-meta {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 8px;
    }

    .permohonan-content {
        margin-bottom: 12px;
    }

    .permohonan-keperluan {
        color: #495057;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .permohonan-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .action-btn {
        border-radius: 8px;
        padding: 8px 12px;
        margin: 2px;
        transition: all 0.3s ease;
        border: none;
        min-height: 44px; /* Touch target */
        min-width: 44px; /* Touch target */
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        white-space: nowrap;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border-radius: 15px;
        margin: 20px 0;
    }

    .create-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        transition: all 0.3s ease;
        font-weight: 600;
        min-height: 48px; /* Touch target */
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .filter-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        border-radius: 8px;
        transition: all 0.3s ease;
        min-height: 48px; /* Touch target */
        padding: 12px 16px;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 12px 15px;
        transition: all 0.3s ease;
        min-height: 48px; /* Touch target */
        font-size: 16px; /* Prevent zoom on iOS */
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 2px;
        border: none;
        color: #667eea;
        transition: all 0.3s ease;
        min-height: 44px; /* Touch target */
        min-width: 44px; /* Touch target */
        display: flex;
        align-items: center;
        justify-content: center;
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

    /* Desktop styles */
    @media (min-width: 768px) {
        .permohonan-container {
            padding-bottom: 20px;
        }
        
        .page-header {
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .filter-card {
            margin-bottom: 25px;
        }
        
        .filter-card .card-header {
            padding: 20px;
        }
        
        .desktop-table {
            display: block;
        }
        
        .mobile-card-list {
            display: none;
        }
        
        .modern-table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border: none;
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
        
        .empty-state {
            padding: 60px 20px;
            margin: 30px 0;
        }
        
        .form-control, .form-select {
            font-size: 14px;
        }
    }

    /* Tablet adjustments */
    @media (min-width: 576px) and (max-width: 767px) {
        .permohonan-card {
            padding: 20px;
        }
        
        .permohonan-title {
            font-size: 1.1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid permohonan-container">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h4 class="mb-1"><i class="fas fa-list me-2"></i>Daftar Permohonan Surat</h4>
                        <p class="mb-0 opacity-75">Kelola dan pantau semua permohonan surat</p>
                    </div>
                    @if(Auth::user()->hasRole('warga'))
                        <a href="{{ route('warga.permohonan.create') }}" class="create-btn">
                            <i class="fas fa-plus me-2"></i>Buat Permohonan Baru
                        </a>
                    @endif
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filter Card -->
            <div class="card filter-card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter & Pencarian</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <form method="GET" action="{{ request()->url() }}">
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                        <option value="diverifikasi" {{ request('status') == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                        <option value="ditandatangani" {{ request('status') == 'ditandatangani' ? 'selected' : '' }}>Ditandatangani</option>
                                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                    <button class="btn filter-btn" type="submit">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-12 col-md-8 mb-3">
                            <form method="GET" action="{{ request()->url() }}">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan jenis surat atau keperluan..." value="{{ request('search') }}">
                                    <button class="btn filter-btn" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if($permohonan->count() > 0)
                <!-- Desktop Table -->
                <div class="desktop-table">
                    <div class="modern-table">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('operator'))
                                            <th>Pemohon</th>
                                        @endif
                                        <th>Jenis Surat</th>
                                        <th>Keperluan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permohonan as $index => $item)
                                        <tr>
                                            <td><strong>{{ $permohonan->firstItem() + $index }}</strong></td>
                                            <td>
                                                <div>{{ $item->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                                            </td>
                                            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('operator'))
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user-circle me-2 text-muted"></i>
                                                        <div>
                                                            <div>{{ $item->user->name }}</div>
                                                            <small class="text-muted">ID: {{ $item->id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                            <td>
                                                <span class="fw-bold">{{ $item->jenisSurat->nama }}</span>
                                            </td>
                                            <td>
                                                <div>{{ Str::limit($item->keperluan, 50) }}</div>
                                                <small class="text-muted">Updated: {{ $item->updated_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($item->status == 'diajukan')
                                                    <span class="badge status-badge bg-warning">Diajukan</span>
                                                @elseif($item->status == 'diverifikasi')
                                                    <span class="badge status-badge bg-info">Diverifikasi</span>
                                                @elseif($item->status == 'ditandatangani')
                                                    <span class="badge status-badge bg-primary">Ditandatangani</span>
                                                @elseif($item->status == 'selesai')
                                                    <span class="badge status-badge bg-success">Selesai</span>
                                                @elseif($item->status == 'ditolak')
                                                    <span class="badge status-badge bg-danger">Ditolak</span>
                                                @endif
                                                @if($item->status == 'pending' && !$item->hasCompleteDetailData())
                                                    <br><small class="text-muted">Data detail belum lengkap</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(Auth::user()->hasRole('admin'))
                                                        <a href="{{ route('admin.permohonan.show', $item) }}" class="btn btn-sm btn-outline-primary action-btn" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.permohonan.edit', $item) }}" class="btn btn-sm btn-outline-warning action-btn" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if($item->status == 'selesai' && $item->file_surat)
                                                            <a href="{{ route('admin.permohonan.download', $item) }}" class="btn btn-sm btn-outline-success action-btn" title="Download">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        @endif
                                            @php
                                                $adminDeleteUrl = route('admin.permohonan.destroy', $item);
                                                $safeKodePermohonan = addslashes($item->kode_permohonan);
                                            @endphp
                                                <button type="button" class="btn btn-sm btn-outline-danger action-btn" title="Hapus" 
                                                        onclick="confirmDelete('{{ $safeKodePermohonan }}', '{{ $adminDeleteUrl }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @elseif(Auth::user()->hasRole('operator'))
                                                <a href="{{ route('operator.permohonan.show', $item) }}" class="btn btn-sm btn-outline-primary action-btn" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('operator.permohonan.edit', $item) }}" class="btn btn-sm btn-outline-warning action-btn" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($item->status == 'selesai' && $item->file_surat)
                                                    <a href="{{ route('operator.permohonan.download', $item) }}" class="btn btn-sm btn-outline-success action-btn" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                            @php
                                                $operatorDeleteUrl = route('operator.permohonan.destroy', $item);
                                                $safeKodePermohonan = addslashes($item->kode_permohonan);
                                            @endphp
                                                <button type="button" class="btn btn-sm btn-outline-danger action-btn" title="Hapus" 
                                                        onclick="confirmDelete('{{ $safeKodePermohonan }}', '{{ $operatorDeleteUrl }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('warga.permohonan.show', $item) }}" class="btn btn-sm btn-outline-primary action-btn">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($item->status == 'selesai' && $item->file_surat)
                                                    <a href="{{ route('warga.permohonan.download', $item) }}" class="btn btn-sm btn-outline-success action-btn">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mobile Card List -->
        <div class="mobile-card-list">
            @foreach($permohonan as $index => $item)
                <div class="permohonan-card">
                    <div class="permohonan-header">
                        <div class="permohonan-title">{{ $item->jenisSurat->nama }}</div>
                        <div>
                            @if($item->status == 'diajukan')
                                <span class="badge status-badge bg-warning">Diajukan</span>
                            @elseif($item->status == 'diverifikasi')
                                <span class="badge status-badge bg-info">Diverifikasi</span>
                            @elseif($item->status == 'ditandatangani')
                                <span class="badge status-badge bg-primary">Ditandatangani</span>
                            @elseif($item->status == 'selesai')
                                <span class="badge status-badge bg-success">Selesai</span>
                            @elseif($item->status == 'ditolak')
                                <span class="badge status-badge bg-danger">Ditolak</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="permohonan-meta">
                        <i class="fas fa-calendar me-1"></i>{{ $item->created_at->format('d/m/Y H:i') }}
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('operator'))
                            <span class="ms-3"><i class="fas fa-user me-1"></i>{{ $item->user->name }}</span>
                        @endif
                    </div>
                    
                    <div class="permohonan-content">
                        <div class="permohonan-keperluan">{{ Str::limit($item->keperluan, 100) }}</div>
                        @if($item->status == 'pending' && !$item->hasCompleteDetailData())
                            <small class="text-muted">Data detail belum lengkap</small>
                        @endif
                    </div>
                    
                    <div class="permohonan-footer">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>Updated: {{ $item->updated_at->format('d/m/Y H:i') }}
                        </small>
                        <div class="d-flex flex-wrap gap-2">
                            @if(Auth::user()->hasRole('admin'))
                                <a href="{{ route('admin.permohonan.show', $item) }}" class="btn btn-sm btn-outline-primary action-btn" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.permohonan.edit', $item) }}" class="btn btn-sm btn-outline-warning action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($item->status == 'selesai' && $item->file_surat)
                                    <a href="{{ route('admin.permohonan.download', $item) }}" class="btn btn-sm btn-outline-success action-btn" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                                @php
                                    $adminDeleteUrl = route('admin.permohonan.destroy', $item);
                                    $safeKodePermohonan = addslashes($item->kode_permohonan);
                                @endphp
                                <button type="button" class="btn btn-sm btn-outline-danger action-btn" title="Hapus" 
                                        onclick="confirmDelete('{{ $safeKodePermohonan }}', '{{ $adminDeleteUrl }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @elseif(Auth::user()->hasRole('operator'))
                                <a href="{{ route('operator.permohonan.show', $item) }}" class="btn btn-sm btn-outline-primary action-btn" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('operator.permohonan.edit', $item) }}" class="btn btn-sm btn-outline-warning action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($item->status == 'selesai' && $item->file_surat)
                                    <a href="{{ route('operator.permohonan.download', $item) }}" class="btn btn-sm btn-outline-success action-btn" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                                @php
                                    $operatorDeleteUrl = route('operator.permohonan.destroy', $item);
                                    $safeKodePermohonan = addslashes($item->kode_permohonan);
                                @endphp
                                <button type="button" class="btn btn-sm btn-outline-danger action-btn" title="Hapus" 
                                        onclick="confirmDelete('{{ $safeKodePermohonan }}', '{{ $operatorDeleteUrl }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @else
                                <a href="{{ route('warga.permohonan.show', $item) }}" class="btn btn-sm btn-outline-primary action-btn">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($item->status == 'selesai' && $item->file_surat)
                                    <a href="{{ route('warga.permohonan.download', $item) }}" class="btn btn-sm btn-outline-success action-btn">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
            
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $permohonan->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
            <h5 class="text-muted mb-3">Belum ada permohonan surat</h5>
            <p class="text-muted mb-4">Permohonan surat yang dibuat akan muncul di sini</p>
            @if(Auth::user()->hasRole('warga'))
                <a href="{{ route('warga.permohonan.create') }}" class="create-btn">
                    <i class="fas fa-plus me-2"></i>Buat Permohonan Pertama
                </a>
            @endif
        </div>
    @endif
</div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <p>Apakah Anda yakin ingin menghapus permohonan <strong id="deleteItemName"></strong>?</p>
                <div class="alert alert-warning" style="border-radius: 10px; border: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>Tindakan ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer" style="border: none; padding: 20px 25px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="border-radius: 10px;">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(itemName, deleteUrl) {
    document.getElementById('deleteItemName').textContent = itemName;
    document.getElementById('deleteForm').action = deleteUrl;
    
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection