@extends('layouts.app')

@section('title', 'Dashboard Operator')

@section('styles')
<style>
    .welcome-alert {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .stats-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        height: 120px;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .stats-card.total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stats-card.new {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .stats-card.processing {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stats-card.completed {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .stats-label {
        font-size: 0.9rem;
        margin-bottom: 5px;
        opacity: 0.9;
    }

    .stats-number {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0;
    }

    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.7;
    }

    .menu-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .menu-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px;
    }

    .menu-item {
        border: none;
        padding: 15px 20px;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .menu-item:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-left-color: #667eea;
        transform: translateX(5px);
    }

    .recent-item {
        border: none;
        padding: 15px 20px;
        border-left: 4px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .recent-item:hover {
        border-left-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .action-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Welcome Message -->
        <div class="welcome-alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-cog me-3" style="font-size: 2rem;"></i>
                <div>
                    <h4 class="mb-1">Selamat datang, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0 opacity-75">Panel Operator Sistem Layanan Surat Online Kelurahan</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-4">
                <div class="card stats-card total">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="stats-label">Total Permohonan</p>
                                <h2 class="stats-number">{{ $totalPermohonan }}</h2>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card stats-card new">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="stats-label">Permohonan Baru</p>
                                <h2 class="stats-number">{{ $permohonanBaru }}</h2>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card stats-card processing">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="stats-label">Sedang Diverifikasi</p>
                                <h2 class="stats-number">{{ $permohonanDiproses }}</h2>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-spinner"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card stats-card completed">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="stats-label">Selesai</p>
                                <h2 class="stats-number">{{ $permohonanSelesai }}</h2>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu and Recent Requests -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card menu-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Menu Utama</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('operator.permohonan.index') }}" class="list-group-item menu-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-list me-2 text-primary"></i>Kelola Permohonan Surat
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $totalPermohonan }}</span>
                                </div>
                            </a>
                            <a href="{{ route('operator.permohonan.index', ['status' => 'diajukan']) }}" class="list-group-item menu-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-clock me-2 text-warning"></i>Permohonan Diajukan
                                    </div>
                                    <span class="badge bg-warning rounded-pill">{{ $permohonanBaru }}</span>
                                </div>
                            </a>
                            <a href="{{ route('operator.permohonan.index', ['status' => 'diverifikasi']) }}" class="list-group-item menu-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-spinner me-2 text-info"></i>Sedang Diverifikasi
                                    </div>
                                    <span class="badge bg-info rounded-pill">{{ $permohonanDiproses }}</span>
                                </div>
                            </a>
                            <a href="{{ route('operator.reports.index') }}" class="list-group-item menu-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-chart-bar me-2 text-secondary"></i>Laporan Surat
                                    </div>
                                    <span class="badge bg-secondary rounded-pill">Report</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card menu-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Permohonan Terbaru</h5>
                    </div>
                    <div class="card-body">
                        @if($recentPermohonan->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentPermohonan as $permohonan)
                                    <div class="list-group-item recent-item">
                                        <div class="d-flex w-100 justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $permohonan->jenisSurat->nama }}</h6>
                                                <p class="mb-1 text-muted">{{ $permohonan->user->name }}</p>
                                                <small class="text-muted">{{ $permohonan->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="text-end">
                                                @if($permohonan->status == 'diajukan')
                                                    <span class="badge bg-warning">Diajukan</span>
                                                @elseif($permohonan->status == 'diverifikasi')
                                                    <span class="badge bg-info">Diverifikasi</span>
                                                @elseif($permohonan->status == 'ditandatangani')
                                                    <span class="badge bg-primary">Ditandatangani</span>
                                                @elseif($permohonan->status == 'selesai')
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif($permohonan->status == 'ditolak')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 text-center">
                                <a href="{{ route('operator.permohonan.index') }}" class="btn action-btn">
                                    <i class="fas fa-eye me-1"></i>Lihat Semua
                                </a>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Belum ada permohonan</h6>
                                <p class="text-muted">Permohonan baru akan muncul di sini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection