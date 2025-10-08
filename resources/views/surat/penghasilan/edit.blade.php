@extends('layouts.app')

@section('title', 'Edit Detail Surat Penghasilan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Detail Surat Penghasilan</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-penghasilan.update', $permohonan) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="jumlah_penghasilan" class="form-label">Penghasilan Bulanan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('jumlah_penghasilan') is-invalid @enderror" 
                                   id="jumlah_penghasilan" name="jumlah_penghasilan" required min="0"
                                   value="{{ old('jumlah_penghasilan', $permohonan->suratPenghasilan->jumlah_penghasilan ?? '') }}" placeholder="0">
                            <span class="input-group-text">,00</span>
                        </div>
                        @error('jumlah_penghasilan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Masukkan nominal penghasilan bulanan dalam rupiah</div>
                    </div>

                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <select class="form-select @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" required>
                            <option value="">Pilih keperluan</option>
                            <option value="Pengajuan Beasiswa" {{ old('keperluan', $permohonan->suratPenghasilan->keperluan ?? '') == 'Pengajuan Beasiswa' ? 'selected' : '' }}>Pengajuan Beasiswa</option>
                            <option value="Pengajuan Kredit Bank" {{ old('keperluan', $permohonan->suratPenghasilan->keperluan ?? '') == 'Pengajuan Kredit Bank' ? 'selected' : '' }}>Pengajuan Kredit Bank</option>
                            <option value="Persyaratan Kerja" {{ old('keperluan', $permohonan->suratPenghasilan->keperluan ?? '') == 'Persyaratan Kerja' ? 'selected' : '' }}>Persyaratan Kerja</option>
                            <option value="Pengajuan Bantuan Sosial" {{ old('keperluan', $permohonan->suratPenghasilan->keperluan ?? '') == 'Pengajuan Bantuan Sosial' ? 'selected' : '' }}>Pengajuan Bantuan Sosial</option>
                            <option value="Persyaratan Administrasi" {{ old('keperluan', $permohonan->suratPenghasilan->keperluan ?? '') == 'Persyaratan Administrasi' ? 'selected' : '' }}>Persyaratan Administrasi</option>
                            <option value="custom">Lainnya (Sebutkan)</option>
                        </select>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="custom-keperluan" class="mb-3 conditional-display" data-condition="custom" data-field="keperluan" data-current="{{ old('keperluan', $permohonan->suratPenghasilan->keperluan ?? '') }}">
                        <label for="keperluan_custom" class="form-label">Keperluan Lainnya <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('keperluan_custom') is-invalid @enderror" 
                               id="keperluan_custom" name="keperluan_custom" 
                               value="{{ old('keperluan_custom', (old('keperluan', $permohonan->suratPenghasilan->keperluan ?? '') == 'custom' ? ($permohonan->suratPenghasilan->keperluan ?? '') : '')) }}" placeholder="Sebutkan keperluan lainnya...">
                        @error('keperluan_custom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>Pastikan nominal penghasilan yang dimasukkan sesuai dengan kenyataan</li>
                            <li>Data ini akan digunakan untuk generate PDF surat keterangan penghasilan</li>
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