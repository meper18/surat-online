@extends('layouts.app')

@section('title', 'Input Detail Surat Nikah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Input Detail Surat Nikah</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-nikah.store', $permohonan) }}" enctype="multipart/form-data">
                    @csrf

                    <h5 class="text-primary mb-3">Data Ayah</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_ayah" class="form-label">Nama Lengkap Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror" 
                                   id="nama_ayah" name="nama_ayah" required 
                                   value="{{ old('nama_ayah') }}" placeholder="Nama lengkap ayah">
                            @error('nama_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nik_ayah" class="form-label">NIK Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik_ayah') is-invalid @enderror" 
                                   id="nik_ayah" name="nik_ayah" required maxlength="16"
                                   value="{{ old('nik_ayah') }}" placeholder="16 digit NIK ayah">
                            @error('nik_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tempat_lahir_ayah" class="form-label">Tempat Lahir Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_lahir_ayah') is-invalid @enderror" 
                                   id="tempat_lahir_ayah" name="tempat_lahir_ayah" required 
                                   value="{{ old('tempat_lahir_ayah') }}" placeholder="Tempat lahir ayah">
                            @error('tempat_lahir_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_lahir_ayah" class="form-label">Tanggal Lahir Ayah <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir_ayah') is-invalid @enderror" 
                                   id="tanggal_lahir_ayah" name="tanggal_lahir_ayah" required 
                                   value="{{ old('tanggal_lahir_ayah') }}">
                            @error('tanggal_lahir_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="pekerjaan_ayah" class="form-label">Pekerjaan Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pekerjaan_ayah') is-invalid @enderror" 
                                   id="pekerjaan_ayah" name="pekerjaan_ayah" required 
                                   value="{{ old('pekerjaan_ayah') }}" placeholder="Pekerjaan ayah">
                            @error('pekerjaan_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="alamat_ayah" class="form-label">Alamat Ayah <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat_ayah') is-invalid @enderror" 
                                    id="alamat_ayah" name="alamat_ayah" rows="2" required 
                                    placeholder="Alamat lengkap ayah...">{{ old('alamat_ayah') }}</textarea>
                            @error('alamat_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="text-primary mb-3">Data Ibu</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_ibu" class="form-label">Nama Lengkap Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror" 
                                   id="nama_ibu" name="nama_ibu" required 
                                   value="{{ old('nama_ibu') }}" placeholder="Nama lengkap ibu">
                            @error('nama_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nik_ibu" class="form-label">NIK Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik_ibu') is-invalid @enderror" 
                                   id="nik_ibu" name="nik_ibu" required maxlength="16"
                                   value="{{ old('nik_ibu') }}" placeholder="16 digit NIK ibu">
                            @error('nik_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tempat_lahir_ibu" class="form-label">Tempat Lahir Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_lahir_ibu') is-invalid @enderror" 
                                   id="tempat_lahir_ibu" name="tempat_lahir_ibu" required 
                                   value="{{ old('tempat_lahir_ibu') }}" placeholder="Tempat lahir ibu">
                            @error('tempat_lahir_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_lahir_ibu" class="form-label">Tanggal Lahir Ibu <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir_ibu') is-invalid @enderror" 
                                   id="tanggal_lahir_ibu" name="tanggal_lahir_ibu" required 
                                   value="{{ old('tanggal_lahir_ibu') }}">
                            @error('tanggal_lahir_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="pekerjaan_ibu" class="form-label">Pekerjaan Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pekerjaan_ibu') is-invalid @enderror" 
                                   id="pekerjaan_ibu" name="pekerjaan_ibu" required 
                                   value="{{ old('pekerjaan_ibu') }}" placeholder="Pekerjaan ibu">
                            @error('pekerjaan_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
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
                    <h5 class="text-primary mb-3 mt-4">Dokumen Tambahan (Opsional)</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
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
                        
                        <div class="col-md-6">
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