@extends('layouts.app')

@section('title', 'Input Detail Surat Domisili Tinggal')

@section('styles')
<link href="{{ asset('css/mobile-first.css') }}" rel="stylesheet">
<style>
    .surat-form-container {
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

    .form-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .form-card .card-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border: none;
        padding: 20px;
        border-radius: 15px 15px 0 0;
    }

    .form-card .card-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #667eea;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 16px; /* Prevent zoom on iOS */
        transition: all 0.3s ease;
        min-height: 48px; /* Touch-friendly */
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    .alert {
        border: none;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .alert-info {
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, rgba(23, 162, 184, 0.05) 100%);
        border-left: 4px solid #17a2b8;
    }

    .alert-warning {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%);
        border-left: 4px solid #ffc107;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 30px;
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
        font-size: 16px;
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

    .required-asterisk {
        color: #dc3545;
        margin-left: 3px;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    /* Desktop styles */
    @media (min-width: 768px) {
        .surat-form-container {
            padding-bottom: 20px;
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
        
        .form-card .card-body {
            padding: 25px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid surat-form-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <!-- Page Header -->
            <div class="page-header">
                <h4 class="mb-1"><i class="fas fa-file-alt me-2"></i>Input Detail Surat Domisili Tinggal</h4>
                <p class="mb-0 opacity-75">Lengkapi data untuk pembuatan surat keterangan domisili tinggal</p>
            </div>

            <div class="form-card card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Permohonan</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                    </div>

                    <form method="POST" action="{{ route('warga.surat-domisili-tinggal.store', $permohonan) }}">
                        @csrf

                        <div class="form-group">
                            <label for="alamat_sekarang" class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Alamat Tempat Tinggal Sekarang
                                <span class="required-asterisk">*</span>
                            </label>
                            <textarea class="form-control @error('alamat_sekarang') is-invalid @enderror" 
                                    id="alamat_sekarang" name="alamat_sekarang" rows="4" required 
                                    placeholder="Masukkan alamat lengkap tempat tinggal sekarang...">{{ old('alamat_sekarang') }}</textarea>
                            @error('alamat_sekarang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Masukkan alamat lengkap termasuk RT/RW, Kelurahan, Kecamatan, Kabupaten/Kota, Provinsi</div>
                        </div>

                        <div class="form-group">
                            <label for="keperluan" class="form-label">
                                <i class="fas fa-clipboard-list"></i>
                                Keperluan Surat
                                <span class="required-asterisk">*</span>
                            </label>
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

                        <div id="custom-keperluan" class="form-group" style="display: none;">
                            <label for="keperluan_custom" class="form-label">
                                <i class="fas fa-edit"></i>
                                Keperluan Lainnya
                                <span class="required-asterisk">*</span>
                            </label>
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

                        <div class="action-buttons">
                            <a href="{{ route('warga.permohonan.show', $permohonan) }}" class="action-btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="action-btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Data Surat
                            </button>
                        </div>
                    </form>
                </div>
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