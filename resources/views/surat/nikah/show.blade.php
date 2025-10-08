@extends('layouts.app')

@section('title', 'Detail Surat Nikah')

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

    .detail-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .detail-card .card-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border: none;
        padding: 20px;
        border-radius: 15px 15px 0 0;
    }

    .detail-card .card-body {
        padding: 25px;
    }

    .info-row {
        display: flex;
        align-items: center;
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
        min-width: 200px;
        display: flex;
        align-items: center;
    }

    .info-label i {
        margin-right: 8px;
        width: 20px;
        text-align: center;
    }

    .info-value {
        flex: 1;
        color: #333;
        font-weight: 500;
    }

    .status-badge {
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
    }

    .status-badge i {
        margin-right: 5px;
    }

    .status-badge.selesai {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .status-badge.diproses {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .status-badge.diajukan {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .status-badge.ditolak {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .action-btn {
        border-radius: 10px;
        padding: 12px 20px;
        transition: all 0.3s ease;
        border: none;
        font-weight: 600;
        margin: 5px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-back {
        background: linear-gradient(135deg, #a8a8a8 0%, #8c8c8c 100%);
        color: white;
    }

    .btn-back:hover {
        color: white;
        box-shadow: 0 5px 15px rgba(168, 168, 168, 0.4);
    }

    .btn-edit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-edit:hover {
        color: white;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .section-divider {
        border: none;
        height: 2px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
        margin: 30px 0;
        opacity: 0.3;
    }

    .section-title {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 10px;
        font-size: 1.2em;
    }

    .actions-container {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border-radius: 15px;
        padding: 20px;
        margin-top: 30px;
    }

    .marriage-status {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: white;
        padding: 10px 15px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    .marriage-status i {
        margin-right: 8px;
    }

    .single-status {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #333;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-heart me-2"></i>Detail Surat Nikah</h4>
                        <p class="mb-0 opacity-75">Informasi lengkap surat keterangan nikah</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-rings-wedding fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Detail Card -->
            <div class="card detail-card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Permohonan</h6>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-barcode"></i>
                            Kode Permohonan:
                        </div>
                        <div class="info-value">
                            <span class="badge bg-secondary status-badge">{{ $permohonan->kode_permohonan }}</span>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-user"></i>
                            Nama Pemohon:
                        </div>
                        <div class="info-value">{{ $permohonan->user->name }}</div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-id-card"></i>
                            NIK:
                        </div>
                        <div class="info-value">{{ $permohonan->user->nik }}</div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-flag"></i>
                            Status:
                        </div>
                        <div class="info-value">
                            @switch($permohonan->status)
                                @case('selesai')
                                    <span class="status-badge selesai">
                                        <i class="fas fa-check-circle"></i>Selesai
                                    </span>
                                    @break
                                @case('diproses')
                                    <span class="status-badge diproses">
                                        <i class="fas fa-cog"></i>Diproses
                                    </span>
                                    @break
                                @case('diajukan')
                                    <span class="status-badge diajukan">
                                        <i class="fas fa-clock"></i>Diajukan
                                    </span>
                                    @break
                                @case('ditolak')
                                    <span class="status-badge ditolak">
                                        <i class="fas fa-times-circle"></i>Ditolak
                                    </span>
                                    @break
                                @default
                                    <span class="status-badge bg-secondary">
                                        <i class="fas fa-question-circle"></i>{{ ucfirst($permohonan->status) }}
                                    </span>
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
            
            @if($permohonan->suratNikah)
                <!-- Data Surat Nikah -->
                <div class="card detail-card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-heart me-2"></i>Data Surat Nikah</h6>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-heart-circle-check"></i>
                                Status Pernikahan:
                            </div>
                            <div class="info-value">
                                <span class="marriage-status {{ $permohonan->suratNikah->status_pernikahan == 'belum menikah' ? 'single-status' : '' }}">
                                    <i class="fas fa-{{ $permohonan->suratNikah->status_pernikahan == 'menikah' ? 'heart' : 'user' }}"></i>
                                    {{ ucfirst($permohonan->suratNikah->status_pernikahan) }}
                                </span>
                            </div>
                        </div>
                        
                        @if($permohonan->suratNikah->status_pernikahan == 'menikah')
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-user-heart"></i>
                                    Nama Pasangan:
                                </div>
                                <div class="info-value">{{ $permohonan->suratNikah->nama_pasangan }}</div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-id-card"></i>
                                    NIK Pasangan:
                                </div>
                                <div class="info-value">{{ $permohonan->suratNikah->nik_pasangan }}</div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-calendar-heart"></i>
                                    Tanggal Pernikahan:
                                </div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($permohonan->suratNikah->tanggal_pernikahan)->format('d F Y') }}</div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Tempat Pernikahan:
                                </div>
                                <div class="info-value">{{ $permohonan->suratNikah->tempat_pernikahan }}</div>
                            </div>
                        @endif
                        
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-male"></i>
                                Nama Ayah:
                            </div>
                            <div class="info-value">{{ $permohonan->suratNikah->nama_ayah }}</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-id-card"></i>
                                NIK Ayah:
                            </div>
                            <div class="info-value">{{ $permohonan->suratNikah->nik_ayah }}</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-female"></i>
                                Nama Ibu:
                            </div>
                            <div class="info-value">{{ $permohonan->suratNikah->nama_ibu }}</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-id-card"></i>
                                NIK Ibu:
                            </div>
                            <div class="info-value">{{ $permohonan->suratNikah->nik_ibu }}</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-clipboard-list"></i>
                                Keperluan:
                            </div>
                            <div class="info-value">{{ $permohonan->suratNikah->keperluan }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="actions-container">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.permohonan.show', $permohonan) }}" class="btn btn-back action-btn">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Permohonan
                        </a>
                        @if($permohonan->suratNikah)
                            <a href="{{ route('admin.surat-nikah.edit', $permohonan) }}" class="btn btn-edit action-btn">
                                <i class="fas fa-edit me-2"></i>Edit Data
                            </a>
                        @endif
                    @elseif(Auth::user()->hasRole('operator'))
                        <a href="{{ route('operator.permohonan.show', $permohonan) }}" class="btn btn-back action-btn">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Permohonan
                        </a>
                        @if($permohonan->suratNikah)
                            <a href="{{ route('operator.surat-nikah.edit', $permohonan) }}" class="btn btn-edit action-btn">
                                <i class="fas fa-edit me-2"></i>Edit Data
                            </a>
                        @endif
                    @else
                        <a href="{{ route('warga.permohonan.show', $permohonan) }}" class="btn btn-back action-btn">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Permohonan
                        </a>
                        @if($permohonan->suratNikah && $permohonan->status != 'selesai')
                            <a href="{{ route('warga.surat-nikah.edit', $permohonan) }}" class="btn btn-edit action-btn">
                                <i class="fas fa-edit me-2"></i>Edit Data
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection