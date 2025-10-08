@extends('layouts.app')

@section('title', 'Edit Detail Surat Domisili Usaha')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Detail Surat Domisili Usaha</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-domisili-usaha.update', $permohonan) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_usaha" class="form-label">Nama Usaha <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_usaha') is-invalid @enderror" 
                               id="nama_usaha" name="nama_usaha" required 
                               value="{{ old('nama_usaha', $permohonan->suratDomisiliUsaha->nama_usaha ?? '') }}" placeholder="Masukkan nama usaha/perusahaan">
                        @error('nama_usaha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jenis_usaha" class="form-label">Jenis Usaha <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('jenis_usaha') is-invalid @enderror" 
                                   id="jenis_usaha" name="jenis_usaha" required 
                                   value="{{ old('jenis_usaha', $permohonan->suratDomisiliUsaha->jenis_usaha ?? '') }}" placeholder="Jenis usaha">
                            @error('jenis_usaha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="lama_usaha" class="form-label">Lama Usaha <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('lama_usaha') is-invalid @enderror" 
                                   id="lama_usaha" name="lama_usaha" required 
                                   value="{{ old('lama_usaha', $permohonan->suratDomisiliUsaha->lama_usaha ?? '') }}" placeholder="Lama usaha (contoh: 2 tahun)">
                            @error('lama_usaha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat_usaha" class="form-label">Alamat Usaha <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat_usaha') is-invalid @enderror" 
                                id="alamat_usaha" name="alamat_usaha" rows="4" required 
                                placeholder="Masukkan alamat lengkap lokasi usaha...">{{ old('alamat_usaha', $permohonan->suratDomisiliUsaha->alamat_usaha ?? '') }}</textarea>
                        @error('alamat_usaha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <select class="form-select @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" required>
                            <option value="">Pilih keperluan</option>
                            <option value="Pengajuan Izin Usaha" {{ old('keperluan', $permohonan->suratDomisiliUsaha->keperluan ?? '') == 'Pengajuan Izin Usaha' ? 'selected' : '' }}>Pengajuan Izin Usaha</option>
                            <option value="Perpanjangan Izin Usaha" {{ old('keperluan', $permohonan->suratDomisiliUsaha->keperluan ?? '') == 'Perpanjangan Izin Usaha' ? 'selected' : '' }}>Perpanjangan Izin Usaha</option>
                            <option value="Pengajuan Kredit Bank" {{ old('keperluan', $permohonan->suratDomisiliUsaha->keperluan ?? '') == 'Pengajuan Kredit Bank' ? 'selected' : '' }}>Pengajuan Kredit Bank</option>
                            <option value="Persyaratan Tender" {{ old('keperluan', $permohonan->suratDomisiliUsaha->keperluan ?? '') == 'Persyaratan Tender' ? 'selected' : '' }}>Persyaratan Tender</option>
                            <option value="Pengajuan SIUP" {{ old('keperluan', $permohonan->suratDomisiliUsaha->keperluan ?? '') == 'Pengajuan SIUP' ? 'selected' : '' }}>Pengajuan SIUP</option>
                            <option value="Pengajuan TDP" {{ old('keperluan', $permohonan->suratDomisiliUsaha->keperluan ?? '') == 'Pengajuan TDP' ? 'selected' : '' }}>Pengajuan TDP</option>
                            <option value="Persyaratan Administrasi" {{ old('keperluan', $permohonan->suratDomisiliUsaha->keperluan ?? '') == 'Persyaratan Administrasi' ? 'selected' : '' }}>Persyaratan Administrasi</option>
                            <option value="custom">Lainnya (Sebutkan)</option>
                        </select>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="custom-keperluan" class="mb-3 conditional-display" data-condition="custom" data-field="keperluan" data-current="{{ old('keperluan', $permohonan->suratDomisiliUsaha->keperluan ?? '') }}">
                        <label for="keperluan_custom" class="form-label">Keperluan Lainnya <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('keperluan_custom') is-invalid @enderror" 
                               id="keperluan_custom" name="keperluan_custom" 
                               value="{{ old('keperluan_custom', (old('keperluan', $permohonan->suratDomisiliUsaha->keperluan ?? '') == 'custom' ? ($permohonan->suratDomisiliUsaha->keperluan ?? '') : '')) }}" placeholder="Sebutkan keperluan lainnya...">
                        @error('keperluan_custom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jumlah_karyawan" class="form-label">Jumlah Karyawan</label>
                            <input type="number" class="form-control @error('jumlah_karyawan') is-invalid @enderror" 
                                   id="jumlah_karyawan" name="jumlah_karyawan" 
                                   value="{{ old('jumlah_karyawan', $permohonan->suratDomisiliUsaha->jumlah_karyawan ?? '') }}" placeholder="Jumlah karyawan">
                            @error('jumlah_karyawan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_usaha" class="form-label">Modal Usaha</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('modal_usaha') is-invalid @enderror" 
                                   id="modal_usaha" name="modal_usaha" min="0"
                                   value="{{ old('modal_usaha', $permohonan->suratDomisiliUsaha->modal_usaha ?? '') }}" placeholder="0">
                            <span class="input-group-text">,00</span>
                        </div>
                        @error('modal_usaha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Masukkan modal usaha dalam rupiah (opsional)</div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>Pastikan data usaha yang dimasukkan sesuai dengan kondisi sebenarnya</li>
                            <li>Alamat usaha harus berada di wilayah desa setempat</li>
                            <li>Data ini akan digunakan untuk generate PDF surat keterangan domisili usaha</li>
                            <li>Surat ini dapat digunakan untuk berbagai keperluan perizinan dan administrasi usaha</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('warga.permohonan.show', $permohonan) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const keperluanSelect = document.getElementById('keperluan');
    const customKeperluanDiv = document.getElementById('custom-keperluan');
    
    keperluanSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customKeperluanDiv.style.display = 'block';
            document.getElementById('keperluan_custom').required = true;
        } else {
            customKeperluanDiv.style.display = 'none';
            document.getElementById('keperluan_custom').required = false;
        }
    });
});
</script>
@endsection