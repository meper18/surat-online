@extends('layouts.app')

@section('title', 'Detail Surat Domisili Tinggal')

@section('styles')
<link href="{{ asset('css/mobile-first.css') }}" rel="stylesheet">
<style>
    .surat-detail-container {
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

    .detail-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .detail-card .card-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border: none;
        padding: 20px;
        border-radius: 15px 15px 0 0;
    }

    .detail-card .card-body {
        padding: 20px;
    }

    .info-row {
        display: flex;
        flex-direction: column;
        padding: 12px 0;
        border-bottom: 1px solid #f8f9fa;
        transition: all 0.3s ease;
    }

    .info-row:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border-radius: 8px;
        padding-left: 10px;
        margin: 0 -10px;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #667eea;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
    }

    .info-value {
        color: #2d3748;
        font-size: 0.95rem;
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border: none;
        margin: 20px 0;
        border-radius: 2px;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
    }

    .action-btn {
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        min-height: 48px; /* Touch-friendly */
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #6c757d;
        border: 2px solid #e9ecef;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        color: #495057;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
    }

    .badge-warning {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        color: white;
    }

    .badge-secondary {
        background: linear-gradient(135deg, #a0aec0 0%, #718096 100%);
        color: white;
    }

    /* Desktop styles */
    @media (min-width: 768px) {
        .surat-detail-container {
            padding-bottom: 20px;
        }
        
        .info-row {
            flex-direction: row;
            align-items: center;
        }
        
        .info-label {
            min-width: 200px;
            margin-bottom: 0;
            margin-right: 20px;
        }
        
        .action-buttons {
            flex-direction: row;
            justify-content: space-between;
        }
        
        .action-btn {
            width: auto;
            min-width: 150px;
        }
    }

    @media (min-width: 992px) {
        .page-header {
            padding: 25px;
        }
        
        .detail-card .card-body {
            padding: 25px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid surat-detail-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Page Header -->
            <div class="page-header">
                <h4 class="mb-1"><i class="fas fa-file-alt me-2"></i>Detail Surat Domisili Tinggal</h4>
                <p class="mb-0 opacity-75">Informasi lengkap surat keterangan domisili tinggal</p>
            </div>

            <div class="detail-card card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Permohonan</h5>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-code me-2"></i>Kode Permohonan:</div>
                        <div class="info-value">{{ $permohonan->kode_permohonan }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-user me-2"></i>Nama Pemohon:</div>
                        <div class="info-value">{{ $permohonan->user->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-id-card me-2"></i>NIK:</div>
                        <div class="info-value">{{ $permohonan->user->nik }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-info-circle me-2"></i>Status:</div>
                        <div class="info-value">
                            <span class="status-badge badge-{{ $permohonan->status == 'selesai' ? 'success' : ($permohonan->status == 'diproses' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($permohonan->status) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($permohonan->suratDomisiliTinggal)
                        <hr class="section-divider">
                        <h6 class="mb-3"><i class="fas fa-home me-2"></i>Data Surat Domisili Tinggal</h6>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-map-marker-alt me-2"></i>Alamat Domisili:</div>
                            <div class="info-value">{{ $permohonan->suratDomisiliTinggal->alamat_domisili }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-location-arrow me-2"></i>RT/RW:</div>
                            <div class="info-value">{{ $permohonan->suratDomisiliTinggal->rt }}/{{ $permohonan->suratDomisiliTinggal->rw }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-building me-2"></i>Kelurahan:</div>
                            <div class="info-value">{{ $permohonan->suratDomisiliTinggal->kelurahan }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-city me-2"></i>Kecamatan:</div>
                            <div class="info-value">{{ $permohonan->suratDomisiliTinggal->kecamatan }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-clipboard-list me-2"></i>Keperluan:</div>
                            <div class="info-value">{{ $permohonan->suratDomisiliTinggal->keperluan }}</div>
                        </div>
                    @endif

                    <div class="action-buttons">
                        @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('admin.permohonan.show', $permohonan) }}" class="action-btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Permohonan
                            </a>
                            @if($permohonan->suratDomisiliTinggal)
                                <a href="{{ route('admin.surat-domisili-tinggal.edit', $permohonan) }}" class="action-btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Edit Data
                                </a>
                            @endif
                        @elseif(Auth::user()->hasRole('operator'))
                            <a href="{{ route('operator.permohonan.show', $permohonan) }}" class="action-btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Permohonan
                            </a>
                            @if($permohonan->suratDomisiliTinggal)
                                <a href="{{ route('operator.surat-domisili-tinggal.edit', $permohonan) }}" class="action-btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Edit Data
                                </a>
                            @endif
                        @else
                            <a href="{{ route('warga.permohonan.show', $permohonan) }}" class="action-btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Permohonan
                            </a>
                            @if($permohonan->suratDomisiliTinggal && $permohonan->status != 'selesai')
                                <a href="{{ route('warga.surat-domisili-tinggal.edit', $permohonan) }}" class="action-btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Edit Data
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection