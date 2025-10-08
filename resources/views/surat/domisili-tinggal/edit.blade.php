@extends('layouts.app')

@section('title', 'Edit Detail Surat Domisili Tinggal')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Detail Surat Domisili Tinggal</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-domisili-tinggal.update', $permohonan) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="alamat_sekarang" class="form-label">Alamat Tempat Tinggal Sekarang <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat_sekarang') is-invalid @enderror" 
                                id="alamat_sekarang" name="alamat_sekarang" rows="4" required 
                                placeholder="Masukkan alamat lengkap tempat tinggal sekarang...">{{ old('alamat_sekarang', $permohonan->suratDomisiliTinggal->alamat_sekarang ?? '') }}</textarea>
                        @error('alamat_sekarang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="lama_tinggal" class="form-label">Lama Tinggal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('lama_tinggal') is-invalid @enderror" 
                                   id="lama_tinggal" name="lama_tinggal" required 
                                   value="{{ old('lama_tinggal', $permohonan->suratDomisiliTinggal->lama_tinggal ?? '') }}" placeholder="Lama tinggal (contoh: 5 tahun)">
                            @error('lama_tinggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <select class="form-select @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" required>
                            <option value="">Pilih keperluan</option>
                            <option value="Pengajuan Pekerjaan" {{ old('keperluan', $permohonan->suratDomisiliTinggal->keperluan ?? '') == 'Pengajuan Pekerjaan' ? 'selected' : '' }}>Pengajuan Pekerjaan</option>
                            <option value="Pengajuan Kredit Bank" {{ old('keperluan', $permohonan->suratDomisiliTinggal->keperluan ?? '') == 'Pengajuan Kredit Bank' ? 'selected' : '' }}>Pengajuan Kredit Bank</option>
                            <option value="Persyaratan Nikah" {{ old('keperluan', $permohonan->suratDomisiliTinggal->keperluan ?? '') == 'Persyaratan Nikah' ? 'selected' : '' }}>Persyaratan Nikah</option>
                            <option value="Persyaratan Haji/Umroh" {{ old('keperluan', $permohonan->suratDomisiliTinggal->keperluan ?? '') == 'Persyaratan Haji/Umroh' ? 'selected' : '' }}>Persyaratan Haji/Umroh</option>
                            <option value="Pengajuan Visa" {{ old('keperluan', $permohonan->suratDomisiliTinggal->keperluan ?? '') == 'Pengajuan Visa' ? 'selected' : '' }}>Pengajuan Visa</option>
                            <option value="Persyaratan BPJS" {{ old('keperluan', $permohonan->suratDomisiliTinggal->keperluan ?? '') == 'Persyaratan BPJS' ? 'selected' : '' }}>Persyaratan BPJS</option>
                            <option value="custom">Lainnya (Sebutkan)</option>
                        </select>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="custom-keperluan" class="mb-3 conditional-display" data-condition="custom" data-field="keperluan" data-current="{{ old('keperluan', $permohonan->suratDomisiliTinggal->keperluan ?? '') }}">
                        <label for="keperluan_custom" class="form-label">Keperluan Lainnya <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('keperluan_custom') is-invalid @enderror" 
                               id="keperluan_custom" name="keperluan_custom" 
                               value="{{ old('keperluan_custom', (old('keperluan', $permohonan->suratDomisiliTinggal->keperluan ?? '') == 'custom' ? ($permohonan->suratDomisiliTinggal->keperluan ?? '') : '')) }}" placeholder="Sebutkan keperluan lainnya...">
                        @error('keperluan_custom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>Pastikan alamat yang dimasukkan sesuai dengan tempat tinggal sebenarnya</li>
                            <li>Data ini akan digunakan untuk generate PDF surat keterangan domisili tinggal</li>
                            <li>Surat ini dapat digunakan untuk berbagai keperluan administrasi dan persyaratan dokumen</li>
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