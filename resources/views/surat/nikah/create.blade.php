@extends('layouts.app')

@section('title', 'Input Detail Surat Nikah')

@section('styles')
<link href="{{ asset('css/mobile-first.css') }}" rel="stylesheet">
<style>
/* Mobile-first responsive styles */
.page-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    padding: 1rem;
    margin: -1rem -1rem 1.5rem -1rem;
    border-radius: 0.5rem 0.5rem 0 0;
}

.page-header h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.form-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
    overflow: hidden;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    display: block;
    font-size: 0.9rem;
}

.form-control, .form-select {
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 0.75rem;
    font-size: 1rem;
    transition: all 0.2s ease;
    width: 100%;
}

.form-control:focus, .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

.section-divider {
    border: none;
    height: 2px;
    background: linear-gradient(90deg, #3b82f6, #e5e7eb);
    margin: 2rem 0 1.5rem 0;
}

.section-title {
    color: #1f2937;
    font-weight: 700;
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 0.5rem;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.2s ease;
    cursor: pointer;
    background: #f9fafb;
}

.upload-area:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.upload-area.drag-over {
    border-color: #3b82f6;
    background: #eff6ff;
}

.upload-placeholder {
    color: #6b7280;
}

.file-preview {
    background: #f3f4f6;
    border-radius: 0.375rem;
    padding: 0.75rem;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.file-name {
    flex: 1;
    font-size: 0.875rem;
    color: #374151;
}

.alert {
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    border: none;
}

.alert-info {
    background: #eff6ff;
    color: #1e40af;
    border-left: 4px solid #3b82f6;
}

.alert-warning {
    background: #fffbeb;
    color: #92400e;
    border-left: 4px solid #f59e0b;
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
    margin-top: 2rem;
    flex-direction: column;
}

.action-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    color: white;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    transform: translateY(-1px);
}

.text-danger {
    color: #ef4444 !important;
}

.is-invalid {
    border-color: #ef4444 !important;
}

.invalid-feedback {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Grid system for mobile */
.form-row {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1rem;
}

.three-col {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1rem;
}

/* Desktop styles */
@media (min-width: 768px) {
    .page-header h4 {
        font-size: 1.25rem;
    }
    
    .form-row {
        flex-direction: row;
    }
    
    .form-row .form-group {
        flex: 1;
        margin-bottom: 0;
    }
    
    .three-col {
        flex-direction: row;
    }
    
    .three-col .form-group {
        flex: 1;
        margin-bottom: 0;
    }
    
    .action-buttons {
        flex-direction: row;
        justify-content: space-between;
    }
    
    .action-btn {
        width: auto;
    }
}

/* Tablet styles */
@media (min-width: 640px) and (max-width: 767px) {
    .three-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .three-col .form-group:first-child {
        grid-column: 1 / -1;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid px-3">
    <div class="form-card">
        <div class="page-header">
            <h4><i class="fas fa-file-alt me-2"></i>Input Detail Surat Nikah</h4>
        </div>
        <div class="p-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-nikah.store', $permohonan) }}" enctype="multipart/form-data">
                    @csrf

                    <h5 class="section-title">Data Ayah</h5>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama_ayah" class="form-label">Nama Lengkap Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror" 
                                   id="nama_ayah" name="nama_ayah" required 
                                   value="{{ old('nama_ayah') }}" placeholder="Nama lengkap ayah">
                            @error('nama_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nik_ayah" class="form-label">NIK Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik_ayah') is-invalid @enderror" 
                                   id="nik_ayah" name="nik_ayah" required maxlength="16"
                                   value="{{ old('nik_ayah') }}" placeholder="16 digit NIK ayah">
                            @error('nik_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="three-col">
                        <div class="form-group">
                            <label for="tempat_lahir_ayah" class="form-label">Tempat Lahir Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_lahir_ayah') is-invalid @enderror" 
                                   id="tempat_lahir_ayah" name="tempat_lahir_ayah" required 
                                   value="{{ old('tempat_lahir_ayah') }}" placeholder="Tempat lahir ayah">
                            @error('tempat_lahir_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir_ayah" class="form-label">Tanggal Lahir Ayah <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir_ayah') is-invalid @enderror" 
                                   id="tanggal_lahir_ayah" name="tanggal_lahir_ayah" required 
                                   value="{{ old('tanggal_lahir_ayah') }}">
                            @error('tanggal_lahir_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="agama_ayah" class="form-label">Agama Ayah <span class="text-danger">*</span></label>
                            <select class="form-select @error('agama_ayah') is-invalid @enderror" 
                                    id="agama_ayah" name="agama_ayah" required>
                                <option value="">Pilih agama</option>
                                <option value="Islam" {{ old('agama_ayah') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama_ayah') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama_ayah') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama_ayah') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama_ayah') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama_ayah') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="pekerjaan_ayah" class="form-label">Pekerjaan Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pekerjaan_ayah') is-invalid @enderror" 
                                   id="pekerjaan_ayah" name="pekerjaan_ayah" required 
                                   value="{{ old('pekerjaan_ayah') }}" placeholder="Pekerjaan ayah">
                            @error('pekerjaan_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="alamat_ayah" class="form-label">Alamat Ayah <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat_ayah') is-invalid @enderror" 
                                    id="alamat_ayah" name="alamat_ayah" rows="2" required 
                                    placeholder="Alamat lengkap ayah...">{{ old('alamat_ayah') }}</textarea>
                            @error('alamat_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="section-divider">
                    <h5 class="section-title">Data Ibu</h5>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama_ibu" class="form-label">Nama Lengkap Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror" 
                                   id="nama_ibu" name="nama_ibu" required 
                                   value="{{ old('nama_ibu') }}" placeholder="Nama lengkap ibu">
                            @error('nama_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nik_ibu" class="form-label">NIK Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik_ibu') is-invalid @enderror" 
                                   id="nik_ibu" name="nik_ibu" required maxlength="16"
                                   value="{{ old('nik_ibu') }}" placeholder="16 digit NIK ibu">
                            @error('nik_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="three-col">
                        <div class="form-group">
                            <label for="tempat_lahir_ibu" class="form-label">Tempat Lahir Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_lahir_ibu') is-invalid @enderror" 
                                   id="tempat_lahir_ibu" name="tempat_lahir_ibu" required 
                                   value="{{ old('tempat_lahir_ibu') }}" placeholder="Tempat lahir ibu">
                            @error('tempat_lahir_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir_ibu" class="form-label">Tanggal Lahir Ibu <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir_ibu') is-invalid @enderror" 
                                   id="tanggal_lahir_ibu" name="tanggal_lahir_ibu" required 
                                   value="{{ old('tanggal_lahir_ibu') }}">
                            @error('tanggal_lahir_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="agama_ibu" class="form-label">Agama Ibu <span class="text-danger">*</span></label>
                            <select class="form-select @error('agama_ibu') is-invalid @enderror" 
                                    id="agama_ibu" name="agama_ibu" required>
                                <option value="">Pilih agama</option>
                                <option value="Islam" {{ old('agama_ibu') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama_ibu') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama_ibu') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama_ibu') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama_ibu') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama_ibu') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="pekerjaan_ibu" class="form-label">Pekerjaan Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pekerjaan_ibu') is-invalid @enderror" 
                                   id="pekerjaan_ibu" name="pekerjaan_ibu" required 
                                   value="{{ old('pekerjaan_ibu') }}" placeholder="Pekerjaan ibu">
                            @error('pekerjaan_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="alamat_ibu" class="form-label">Alamat Ibu <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat_ibu') is-invalid @enderror" 
                                    id="alamat_ibu" name="alamat_ibu" rows="2" required 
                                    placeholder="Alamat lengkap ibu...">{{ old('alamat_ibu') }}</textarea>
                            @error('alamat_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Dokumen Tambahan -->
                    <h5 class="section-title">Dokumen Tambahan (Opsional)</h5>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ktp_ayah" class="form-label">KTP Ayah</label>
                            <div class="upload-area" id="ktp-ayah-area" data-custom-handler="true">
                                <input type="file" class="form-control @error('ktp_ayah') is-invalid @enderror" 
                                       id="ktp_ayah" name="ktp_ayah" 
                                       accept=".jpg,.jpeg,.png,.pdf">
                                <div class="upload-placeholder">
                                    <i class="fas fa-id-card fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Upload KTP Ayah</p>
                                    <small class="text-muted">Format: JPG, JPEG, PNG, PDF (Max: 2MB)</small>
                                </div>
                                <div class="file-preview" style="display: none;">
                                    <div class="file-info">
                                        <i class="fas fa-id-card text-primary"></i>
                                        <span class="file-name"></span>
                                        <button type="button" class="btn btn-sm btn-danger remove-file">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('ktp_ayah')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="ktp_ibu" class="form-label">KTP Ibu</label>
                            <div class="upload-area" id="ktp-ibu-area" data-custom-handler="true">
                                <input type="file" class="form-control @error('ktp_ibu') is-invalid @enderror" 
                                       id="ktp_ibu" name="ktp_ibu" 
                                       accept=".jpg,.jpeg,.png,.pdf">
                                <div class="upload-placeholder">
                                    <i class="fas fa-id-card fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Upload KTP Ibu</p>
                                    <small class="text-muted">Format: JPG, JPEG, PNG, PDF (Max: 2MB)</small>
                                </div>
                                <div class="file-preview" style="display: none;">
                                    <div class="file-info">
                                        <i class="fas fa-id-card text-primary"></i>
                                        <span class="file-name"></span>
                                        <button type="button" class="btn btn-sm btn-danger remove-file">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('ktp_ibu')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Pastikan semua data yang dimasukkan sudah benar. Data ini akan digunakan untuk generate PDF surat nikah.
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('warga.permohonan.show', $permohonan) }}" class="action-btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="action-btn btn-primary">
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
// Auto-format NIK inputs
document.getElementById('nik_ayah').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').substring(0, 16);
});

document.getElementById('nik_ibu').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').substring(0, 16);
});

// File upload handlers for KTP Ayah and KTP Ibu
function setupFileUpload(inputId, areaId) {
    const fileInput = document.getElementById(inputId);
    const uploadArea = document.getElementById(areaId);
    const placeholder = uploadArea.querySelector('.upload-placeholder');
    const preview = uploadArea.querySelector('.file-preview');
    const fileName = preview.querySelector('.file-name');
    const removeBtn = preview.querySelector('.remove-file');

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                fileInput.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau PDF.');
                fileInput.value = '';
                return;
            }

            // Show preview
            fileName.textContent = file.name;
            placeholder.style.display = 'none';
            preview.style.display = 'block';
        }
    });

    // Handle drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('drag-over');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    // Handle remove file
    removeBtn.addEventListener('click', function() {
        fileInput.value = '';
        placeholder.style.display = 'block';
        preview.style.display = 'none';
    });

    // Handle click on upload area
    uploadArea.addEventListener('click', function(e) {
        if (e.target === uploadArea || e.target === placeholder || placeholder.contains(e.target)) {
            fileInput.click();
        }
    });
}

// Initialize file upload handlers
setupFileUpload('ktp_ayah', 'ktp-ayah-area');
setupFileUpload('ktp_ibu', 'ktp-ibu-area');
</script>
@endsection