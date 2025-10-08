@extends('layouts.app')

@section('title', 'Input Detail Surat Domisili Tinggal')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Input Detail Surat Domisili Tinggal</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-domisili-tinggal.store', $permohonan) }}">
                    @csrf

                    <div class="mb-3">
                        <label for="alamat_sekarang" class="form-label">Alamat Tempat Tinggal Sekarang <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat_sekarang') is-invalid @enderror" 
                                id="alamat_sekarang" name="alamat_sekarang" rows="4" required 
                                placeholder="Masukkan alamat lengkap tempat tinggal sekarang...">{{ old('alamat_sekarang') }}</textarea>
                        @error('alamat_sekarang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Masukkan alamat lengkap termasuk RT/RW, Kelurahan, Kecamatan, Kabupaten/Kota, Provinsi</div>
                    </div>

                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan Surat <span class="text-danger">*</span></label>
                        <select class="form-select @error('keperluan') is-invalid @enderror" 
                                id="keperluan" name="keperluan" required onchange="toggleCustomKeperluan()">
                            <option value="">Pilih keperluan</option>
                            <option value="Persyaratan Administrasi" {{ old('keperluan') == 'Persyaratan Administrasi' ? 'selected' : '' }}>Persyaratan Administrasi</option>
                            <option value="Pengajuan KTP" {{ old('keperluan') == 'Pengajuan KTP' ? 'selected' : '' }}>Pengajuan KTP</option>
                            <option value="Pengajuan Kartu Keluarga" {{ old('keperluan') == 'Pengajuan Kartu Keluarga' ? 'selected' : '' }}>Pengajuan Kartu Keluarga</option>
                            <option value="Pendaftaran Sekolah" {{ old('keperluan') == 'Pendaftaran Sekolah' ? 'selected' : '' }}>Pendaftaran Sekolah</option>
                            <option value="Pengajuan Beasiswa" {{ old('keperluan') == 'Pengajuan Beasiswa' ? 'selected' : '' }}>Pengajuan Beasiswa</option>
                            <option value="Pengajuan Pekerjaan" {{ old('keperluan') == 'Pengajuan Pekerjaan' ? 'selected' : '' }}>Pengajuan Pekerjaan</option>
                            <option value="Pengajuan Kredit Bank" {{ old('keperluan') == 'Pengajuan Kredit Bank' ? 'selected' : '' }}>Pengajuan Kredit Bank</option>
                            <option value="Persyaratan Nikah" {{ old('keperluan') == 'Persyaratan Nikah' ? 'selected' : '' }}>Persyaratan Nikah</option>
                            <option value="Persyaratan Haji/Umroh" {{ old('keperluan') == 'Persyaratan Haji/Umroh' ? 'selected' : '' }}>Persyaratan Haji/Umroh</option>
                            <option value="Pengajuan Visa" {{ old('keperluan') == 'Pengajuan Visa' ? 'selected' : '' }}>Pengajuan Visa</option>
                            <option value="Persyaratan BPJS" {{ old('keperluan') == 'Persyaratan BPJS' ? 'selected' : '' }}>Persyaratan BPJS</option>
                            <option value="custom">Lainnya (Sebutkan)</option>
                        </select>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="custom-keperluan" class="mb-3" style="display: none;">
                        <label for="keperluan_custom" class="form-label">Keperluan Lainnya <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('keperluan_custom') is-invalid @enderror" 
                               id="keperluan_custom" name="keperluan_custom" 
                               value="{{ old('keperluan_custom') }}" placeholder="Sebutkan keperluan lainnya...">
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
                            <i class="fas fa-save me-1"></i>Simpan Data Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleCustomKeperluan() {
    const keperluanSelect = document.getElementById('keperluan');
    const customDiv = document.getElementById('custom-keperluan');
    const customInput = document.getElementById('keperluan_custom');
    
    if (keperluanSelect.value === 'custom') {
        customDiv.style.display = 'block';
        customInput.required = true;
    } else {
        customDiv.style.display = 'none';
        customInput.required = false;
        customInput.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCustomKeperluan();
    
    // Set custom keperluan if old value exists and not in predefined options
    const oldKeperluan = '{{ old("keperluan") }}';
    const keperluanSelect = document.getElementById('keperluan');
    const customInput = document.getElementById('keperluan_custom');
    
    if (oldKeperluan && !Array.from(keperluanSelect.options).some(option => option.value === oldKeperluan)) {
        keperluanSelect.value = 'custom';
        customInput.value = oldKeperluan;
        toggleCustomKeperluan();
    }
});

// Handle form submission to use custom value if selected
document.querySelector('form').addEventListener('submit', function(e) {
    const keperluanSelect = document.getElementById('keperluan');
    const customInput = document.getElementById('keperluan_custom');
    
    if (keperluanSelect.value === 'custom' && customInput.value.trim()) {
        // Create a hidden input with the custom value
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'keperluan';
        hiddenInput.value = customInput.value.trim();
        this.appendChild(hiddenInput);
        
        // Disable the select to prevent it from being submitted
        keperluanSelect.disabled = true;
    }
});
</script>
@endsection