@extends('layouts.app')

@section('title', 'Edit Permohonan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Permohonan</h3>
                    <div class="card-tools">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Navigation to Edit Specific Letter Data --}}
                    @if($permohonan->hasCompleteDetailData())
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-edit me-2"></i>Edit Data Spesifik Surat</h6>
                            <p class="mb-2">Anda dapat mengedit data spesifik untuk {{ $permohonan->jenisSurat->nama }} dengan mengklik tombol di bawah ini.</p>
                            @php
                                $editRoute = '';
                                $routePrefix = '';
                                
                                // Determine route prefix based on user role
                                if(Auth::user()->hasRole('admin')) {
                                    $routePrefix = 'admin';
                                } elseif(Auth::user()->hasRole('operator')) {
                                    $routePrefix = 'operator';
                                } else {
                                    $routePrefix = 'warga';
                                }
                                
                                switch($permohonan->jenisSurat->nama) {
                                    case 'Surat Keterangan Kematian':
                                        $editRoute = $routePrefix . '.surat-kematian.edit';
                                        break;
                                    case 'Surat Keterangan Pindah/Mandah':
                                        $editRoute = $routePrefix . '.surat-mandah.edit';
                                        break;
                                    case 'Surat Keterangan Penghasilan':
                                        $editRoute = $routePrefix . '.surat-penghasilan.edit';
                                        break;
                                    case 'Surat Keterangan Domisili Tinggal':
                                        $editRoute = $routePrefix . '.surat-domisili-tinggal.edit';
                                        break;
                                    case 'Surat Keterangan Domisili Usaha':
                                        $editRoute = $routePrefix . '.surat-domisili-usaha.edit';
                                        break;
                                    case 'Surat Keterangan Belum Menikah':
                                        $editRoute = $routePrefix . '.surat-nikah.edit';
                                        break;
                                }
                            @endphp
                            @if($editRoute)
                                <a href="{{ route($editRoute, $permohonan) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit Data {{ $permohonan->jenisSurat->nama }}
                                </a>
                            @endif
                        </div>
                    @endif

                    <form action="{{ auth()->user()->hasRole('admin') ? route('admin.permohonan.update', $permohonan) : route('operator.permohonan.update', $permohonan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_permohonan">Kode Permohonan</label>
                                    <input type="text" class="form-control" id="kode_permohonan" value="{{ $permohonan->kode_permohonan }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_surat">Jenis Surat</label>
                                    <input type="text" class="form-control" id="jenis_surat" value="{{ $permohonan->jenisSurat->nama }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_pemohon">Nama Pemohon</label>
                                    <input type="text" class="form-control" id="nama_pemohon" value="{{ $permohonan->user->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_permohonan">Tanggal Permohonan</label>
                                    <input type="text" class="form-control" id="tanggal_permohonan" value="{{ $permohonan->created_at->format('d/m/Y H:i') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keperluan">Keperluan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="keperluan" name="keperluan" rows="4" required>{{ old('keperluan', $permohonan->keperluan) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="dokumen_pendukung">Dokumen Pendukung</label>
                            <input type="text" class="form-control" id="dokumen_pendukung" name="dokumen_pendukung" 
                                   value="{{ old('dokumen_pendukung', $permohonan->dokumen_pendukung) }}" 
                                   placeholder="Sebutkan dokumen pendukung yang dilampirkan">
                            <small class="form-text text-muted">Contoh: KTP, KK, Surat Keterangan RT, dll.</small>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_surat_pernyataan">Tanggal Surat Pernyataan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_surat_pernyataan" name="tanggal_surat_pernyataan" 
                                   value="{{ old('tanggal_surat_pernyataan', $permohonan->tanggal_surat_pernyataan) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('catatan', $permohonan->catatan) }}</textarea>
                        </div>

                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operator'))
                        <div class="form-group">
                            <label for="keterangan_status">Keterangan Status</label>
                            <textarea class="form-control" id="keterangan_status" name="keterangan_status" rows="3" 
                                      placeholder="Keterangan tambahan terkait status permohonan">{{ old('keterangan_status', $permohonan->keterangan_status) }}</textarea>
                            <small class="form-text text-muted">Diisi oleh admin/operator untuk memberikan keterangan tambahan</small>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="status">Status Saat Ini</label>
                            <input type="text" class="form-control" id="status" value="{{ ucfirst($permohonan->status) }}" readonly>
                            <small class="form-text text-muted">Status dapat diubah melalui halaman detail permohonan</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection