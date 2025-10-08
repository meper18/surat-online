@extends('layouts.app')

@section('title', 'Detail Surat Domisili Usaha')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Surat Keterangan Domisili Usaha</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Kode Permohonan:</strong></div>
                        <div class="col-sm-9">{{ $permohonan->kode_permohonan }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Nama Pemohon:</strong></div>
                        <div class="col-sm-9">{{ $permohonan->user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>NIK:</strong></div>
                        <div class="col-sm-9">{{ $permohonan->user->nik }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Status:</strong></div>
                        <div class="col-sm-9">
                            <span class="badge badge-{{ $permohonan->status == 'selesai' ? 'success' : ($permohonan->status == 'diproses' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($permohonan->status) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($permohonan->suratDomisiliUsaha)
                        <hr>
                        <h5>Data Surat Domisili Usaha</h5>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Nama Usaha:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliUsaha->nama_usaha }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Jenis Usaha:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliUsaha->jenis_usaha }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Alamat Usaha:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliUsaha->alamat_usaha }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>RT/RW:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliUsaha->rt }}/{{ $permohonan->suratDomisiliUsaha->rw }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Kelurahan:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliUsaha->kelurahan }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Kecamatan:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliUsaha->kecamatan }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Keperluan:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliUsaha->keperluan }}</div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('admin.permohonan.show', $permohonan) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Detail Permohonan
                            </a>
                            @if($permohonan->suratDomisiliUsaha)
                                <a href="{{ route('admin.surat-domisili-usaha.edit', $permohonan) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit Data
                                </a>
                            @endif
                        @elseif(Auth::user()->hasRole('operator'))
                            <a href="{{ route('operator.permohonan.show', $permohonan) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Detail Permohonan
                            </a>
                            @if($permohonan->suratDomisiliUsaha)
                                <a href="{{ route('operator.surat-domisili-usaha.edit', $permohonan) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit Data
                                </a>
                            @endif
                        @else
                            <a href="{{ route('warga.permohonan.show', $permohonan) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Detail Permohonan
                            </a>
                            @if($permohonan->suratDomisiliUsaha && $permohonan->status != 'selesai')
                                <a href="{{ route('warga.surat-domisili-usaha.edit', $permohonan) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit Data
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