@extends('layouts.app')

@section('title', 'Input Detail Surat Penghasilan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Input Detail Surat Penghasilan</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-penghasilan.store', $permohonan) }}">
                    @csrf

                    <div class="mb-3">
                        <label for="jumlah_penghasilan" class="form-label">Jumlah Penghasilan per Bulan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('jumlah_penghasilan') is-invalid @enderror" 
                                   id="jumlah_penghasilan" name="jumlah_penghasilan" required min="0" step="1000"
                                   value="{{ old('jumlah_penghasilan') }}" placeholder="0">
                            <span class="input-group-text">,00</span>
                        </div>
                        @error('jumlah_penghasilan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Masukkan jumlah penghasilan dalam rupiah (tanpa titik atau koma)</div>
                    </div>

                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan Surat <span class="text-danger">*</span></label>
                        <select class="form-select @error('keperluan') is-invalid @enderror" 
                                id="keperluan" name="keperluan" required onchange="toggleCustomKeperluan()">
                            <option value="">Pilih keperluan</option>
                            <option value="Pengajuan Kredit Bank" {{ old('keperluan') == 'Pengajuan Kredit Bank' ? 'selected' : '' }}>Pengajuan Kredit Bank</option>
                            <option value="Pengajuan Beasiswa" {{ old('keperluan') == 'Pengajuan Beasiswa' ? 'selected' : '' }}>Pengajuan Beasiswa</option>
                            <option value="Persyaratan Kerja" {{ old('keperluan') == 'Persyaratan Kerja' ? 'selected' : '' }}>Persyaratan Kerja</option>
                            <option value="Pengajuan Bantuan Sosial" {{ old('keperluan') == 'Pengajuan Bantuan Sosial' ? 'selected' : '' }}>Pengajuan Bantuan Sosial</option>
                            <option value="Persyaratan Administrasi" {{ old('keperluan') == 'Persyaratan Administrasi' ? 'selected' : '' }}>Persyaratan Administrasi</option>
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
                            <li>Pastikan jumlah penghasilan yang dimasukkan sesuai dengan kondisi sebenarnya</li>
                            <li>Data ini akan digunakan untuk generate PDF surat keterangan penghasilan</li>
                            <li>Surat ini dapat digunakan untuk berbagai keperluan administrasi</li>
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

// Format number input with thousands separator for display
document.getElementById('jumlah_penghasilan').addEventListener('input', function(e) {
    // Remove any non-digit characters
    let value = this.value.replace(/\D/g, '');
    
    // Update the actual value (without formatting)
    this.value = value;
});

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