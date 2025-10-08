@extends('layouts.app')

@section('title', 'Dashboard Warga')

@section('styles')
<style>
    .stats-card {
        border: none;
        border-radius: 20px;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stats-card:hover::before {
        opacity: 1;
    }
    
    .stats-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }
    
    .stats-card.total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stats-card.pending {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stats-card.verified {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stats-card.completed {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .stats-icon {
        font-size: 3rem;
        opacity: 0.8;
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }
    
    .stats-label {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0;
    }
    
    .menu-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    
    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .menu-item {
        border: none;
        border-radius: 12px;
        margin-bottom: 8px;
        padding: 15px 20px;
        transition: all 0.3s ease;
        background: rgba(52, 152, 219, 0.05);
    }
    
    .menu-item:hover {
        background: rgba(52, 152, 219, 0.1);
        transform: translateX(5px);
    }
    
    .recent-item {
        border: none;
        border-radius: 12px;
        margin-bottom: 10px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.8);
        border-left: 4px solid #3498db;
    }
    
    .welcome-alert {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 15px;
        color: white;
        padding: 20px;
    }
    
    .action-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        color: white;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    .empty-state {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        padding: 30px;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Welcome Message -->
        <div class="welcome-alert mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-circle me-3" style="font-size: 2rem;"></i>
                <div>
                    <h4 class="mb-1">Selamat datang, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0 opacity-75">Sistem Layanan Surat Online Kelurahan</p>
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
                <div class="card stats-card pending">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="stats-label">Diajukan</p>
                                <h2 class="stats-number">{{ $permohonanPending }}</h2>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card stats-card verified">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="stats-label">Diverifikasi</p>
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
                        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Menu Utama</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('warga.permohonan.create') }}" class="list-group-item menu-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-plus me-2 text-success"></i>Buat Permohonan Baru
                                    </div>
                                    <span class="badge bg-success rounded-pill">Buat</span>
                                </div>
                            </a>
                            <a href="{{ route('warga.permohonan.index') }}" class="list-group-item menu-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-list me-2 text-primary"></i>Lihat Permohonan Saya
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $totalPermohonan }}</span>
                                </div>
                            </a>
                            <a href="{{ route('warga.permohonan.index', ['status' => 'diajukan']) }}" class="list-group-item menu-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-clock me-2 text-warning"></i>Permohonan Diajukan
                                    </div>
                                    <span class="badge bg-warning rounded-pill">{{ $permohonanPending }}</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card menu-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Permohonan Terbaru</h5>
                    </div>
                    <div class="card-body">
                        @if($recentPermohonan->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentPermohonan as $permohonan)
                                    <div class="list-group-item recent-item">
                                        <div class="d-flex w-100 justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-primary">{{ $permohonan->jenisSurat->nama }}</h6>
                                                <p class="mb-1 text-muted">{{ Str::limit($permohonan->keperluan, 50) }}</p>
                                                <small class="text-muted">{{ $permohonan->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div>
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
                                                
                                                @if($permohonan->status == 'pending' && !$permohonan->hasCompleteDetailData())
                                                    <span class="badge bg-secondary ms-1">Data Detail Belum Lengkap</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 text-center">
                                <a href="{{ route('warga.permohonan.index') }}" class="btn action-btn">
                                    <i class="fas fa-eye me-1"></i>Lihat Semua
                                </a>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Belum ada permohonan</h6>
                                <p class="text-muted">Silakan buat permohonan surat baru</p>
                                <a href="{{ route('warga.permohonan.create') }}" class="btn action-btn">
                                    <i class="fas fa-plus me-1"></i>Buat Permohonan
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection