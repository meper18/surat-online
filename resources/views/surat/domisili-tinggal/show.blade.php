@extends('layouts.app')

@section('title', 'Detail Surat Domisili Tinggal')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Surat Keterangan Domisili Tinggal</h3>
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
                    
                    @if($permohonan->suratDomisiliTinggal)
                        <hr>
                        <h5>Data Surat Domisili Tinggal</h5>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Alamat Domisili:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliTinggal->alamat_domisili }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>RT/RW:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliTinggal->rt }}/{{ $permohonan->suratDomisiliTinggal->rw }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Kelurahan:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliTinggal->kelurahan }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Kecamatan:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliTinggal->kecamatan }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Keperluan:</strong></div>
                            <div class="col-sm-9">{{ $permohonan->suratDomisiliTinggal->keperluan }}</div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('admin.permohonan.show', $permohonan) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Detail Permohonan
                            </a>
                            @if($permohonan->suratDomisiliTinggal)
                                <a href="{{ route('admin.surat-domisili-tinggal.edit', $permohonan) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit Data
                                </a>
                            @endif
                        @elseif(Auth::user()->hasRole('operator'))
                            <a href="{{ route('operator.permohonan.show', $permohonan) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Detail Permohonan
                            </a>
                            @if($permohonan->suratDomisiliTinggal)
                                <a href="{{ route('operator.surat-domisili-tinggal.edit', $permohonan) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit Data
                                </a>
                            @endif
                        @else
                            <a href="{{ route('warga.permohonan.show', $permohonan) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Detail Permohonan
                            </a>
                            @if($permohonan->suratDomisiliTinggal && $permohonan->status != 'selesai')
                                <a href="{{ route('warga.surat-domisili-tinggal.edit', $permohonan) }}" class="btn btn-primary">
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