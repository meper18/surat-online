@extends('layouts.app')

@section('title', 'Input Detail Surat Domisili Usaha')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Input Detail Surat Domisili Usaha</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-domisili-usaha.store', $permohonan) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="nama_usaha" class="form-label">Nama Usaha <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_usaha') is-invalid @enderror" 
                               id="nama_usaha" name="nama_usaha" required 
                               value="{{ old('nama_usaha') }}" placeholder="Masukkan nama usaha/perusahaan">
                        @error('nama_usaha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Contoh: Toko Sembako Berkah, CV. Maju Jaya, Warung Makan Sederhana</div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat_usaha" class="form-label">Alamat Usaha <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat_usaha') is-invalid @enderror" 
                                id="alamat_usaha" name="alamat_usaha" rows="4" required 
                                placeholder="Masukkan alamat lengkap tempat usaha...">{{ old('alamat_usaha') }}</textarea>
                        @error('alamat_usaha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Masukkan alamat lengkap termasuk RT/RW, Kelurahan, Kecamatan</div>
                    </div>

                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan Surat <span class="text-danger">*</span></label>
                        <select class="form-select @error('keperluan') is-invalid @enderror" 
                                id="keperluan" name="keperluan" required onchange="toggleCustomKeperluan()">
                            <option value="">Pilih keperluan</option>
                            <option value="Pengajuan Izin Usaha" {{ old('keperluan') == 'Pengajuan Izin Usaha' ? 'selected' : '' }}>Pengajuan Izin Usaha</option>
                            <option value="Perpanjangan Izin Usaha" {{ old('keperluan') == 'Perpanjangan Izin Usaha' ? 'selected' : '' }}>Perpanjangan Izin Usaha</option>
                            <option value="Pengajuan Kredit Bank" {{ old('keperluan') == 'Pengajuan Kredit Bank' ? 'selected' : '' }}>Pengajuan Kredit Bank</option>
                            <option value="Persyaratan Tender" {{ old('keperluan') == 'Persyaratan Tender' ? 'selected' : '' }}>Persyaratan Tender</option>
                            <option value="Pengajuan SIUP" {{ old('keperluan') == 'Pengajuan SIUP' ? 'selected' : '' }}>Pengajuan SIUP</option>
                            <option value="Pengajuan TDP" {{ old('keperluan') == 'Pengajuan TDP' ? 'selected' : '' }}>Pengajuan TDP</option>
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

                    <!-- Foto Tempat Usaha Upload -->
                    <div class="mb-3">
                        <label for="foto_tempat_usaha" class="form-label">Foto Tempat Usaha <span class="text-danger">*</span></label>
                        <div class="upload-area" id="foto-tempat-usaha-area" data-custom-handler="true">
                            <input type="file" class="form-control @error('foto_tempat_usaha') is-invalid @enderror" 
                                   id="foto_tempat_usaha" name="foto_tempat_usaha" 
                                   accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="upload-placeholder">
                                <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Klik untuk upload atau drag & drop foto tempat usaha</p>
                                <small class="text-muted">Format: JPG, JPEG, PNG, PDF (Max: 2MB)</small>
                            </div>
                            <div class="file-preview" style="display: none;">
                                <div class="file-info">
                                    <i class="fas fa-file-image text-primary"></i>
                                    <span class="file-name"></span>
                                    <button type="button" class="btn btn-sm btn-danger remove-file">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('foto_tempat_usaha')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Upload foto yang menunjukkan tempat usaha dengan jelas</div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>Pastikan nama usaha dan alamat yang dimasukkan sesuai dengan kondisi sebenarnya</li>
                            <li>Data ini akan digunakan untuk generate PDF surat keterangan domisili usaha</li>
                            <li>Surat ini dapat digunakan untuk berbagai keperluan perizinan dan administrasi usaha</li>
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
// File upload handling for foto tempat usaha
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('foto_tempat_usaha');
    const fotoArea = document.getElementById('foto-tempat-usaha-area');
    const placeholder = fotoArea.querySelector('.upload-placeholder');
    const preview = fotoArea.querySelector('.file-preview');
    const fileName = preview.querySelector('.file-name');
    const removeBtn = preview.querySelector('.remove-file');

    // Handle file selection
    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                fotoInput.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau PDF.');
                fotoInput.value = '';
                return;
            }

            // Show preview
            fileName.textContent = file.name;
            placeholder.style.display = 'none';
            preview.style.display = 'block';
        }
    });

    // Handle remove file
    removeBtn.addEventListener('click', function() {
        fotoInput.value = '';
        placeholder.style.display = 'block';
        preview.style.display = 'none';
    });

    // Drag and drop functionality
    fotoArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        fotoArea.classList.add('dragover');
    });

    fotoArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        fotoArea.classList.remove('dragover');
    });

    fotoArea.addEventListener('drop', function(e) {
        e.preventDefault();
        fotoArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fotoInput.files = files;
            fotoInput.dispatchEvent(new Event('change'));
        }
    });
});

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