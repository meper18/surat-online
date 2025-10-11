@extends('layouts.app')

@section('title', 'Input Detail Surat Kematian')

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
    background: linear-gradient(90deg, #e5e7eb 0%, #3b82f6 50%, #e5e7eb 100%);
    margin: 2rem 0;
}

.section-title {
    color: #1f2937;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
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

.upload-area.dragover {
    border-color: #3b82f6;
    background: #dbeafe;
}

.upload-placeholder {
    color: #6b7280;
}

.file-preview {
    background: #f3f4f6;
    border-radius: 0.375rem;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
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

.btn-danger {
    background: #ef4444;
    color: white;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.btn-danger:hover {
    background: #dc2626;
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
    .form-row.three-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .form-row.three-col .form-group:last-child {
        grid-column: 1 / -1;
    }
}

/* Large desktop styles */
@media (min-width: 1024px) {
    .form-row.three-col {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid px-3">
    <div class="form-card">
        <div class="page-header">
            <h4><i class="fas fa-file-alt me-2"></i>Input Detail Surat Kematian</h4>
        </div>
        <div class="p-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-kematian.store', $permohonan) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label for="hubungan_keluarga" class="form-label">Hubungan Keluarga dengan Almarhum/Almarhumah <span class="text-danger">*</span></label>
                            <select class="form-select @error('hubungan_keluarga') is-invalid @enderror" 
                                    id="hubungan_keluarga" name="hubungan_keluarga" required>
                                <option value="">Pilih hubungan keluarga</option>
                                <option value="Ayah" {{ old('hubungan_keluarga') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                <option value="Ibu" {{ old('hubungan_keluarga') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                <option value="Suami" {{ old('hubungan_keluarga') == 'Suami' ? 'selected' : '' }}>Suami</option>
                                <option value="Istri" {{ old('hubungan_keluarga') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                <option value="Anak" {{ old('hubungan_keluarga') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                <option value="Saudara" {{ old('hubungan_keluarga') == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                                <option value="Lainnya" {{ old('hubungan_keluarga') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('hubungan_keluarga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama_meninggal" class="form-label">Nama Lengkap Almarhum/Almarhumah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_meninggal') is-invalid @enderror" 
                                   id="nama_meninggal" name="nama_meninggal" required 
                                   value="{{ old('nama_meninggal') }}" placeholder="Nama lengkap yang meninggal">
                            @error('nama_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row three-col">
                        <div class="form-group">
                            <label for="tempat_lahir_meninggal" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_lahir_meninggal') is-invalid @enderror" 
                                   id="tempat_lahir_meninggal" name="tempat_lahir_meninggal" required 
                                   value="{{ old('tempat_lahir_meninggal') }}" placeholder="Tempat lahir">
                            @error('tempat_lahir_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir_meninggal" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir_meninggal') is-invalid @enderror" 
                                   id="tanggal_lahir_meninggal" name="tanggal_lahir_meninggal" required 
                                   value="{{ old('tanggal_lahir_meninggal') }}">
                            @error('tanggal_lahir_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="agama_meninggal" class="form-label">Agama <span class="text-danger">*</span></label>
                            <select class="form-select @error('agama_meninggal') is-invalid @enderror" 
                                    id="agama_meninggal" name="agama_meninggal" required>
                                <option value="">Pilih agama</option>
                                <option value="Islam" {{ old('agama_meninggal') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama_meninggal') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama_meninggal') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama_meninggal') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama_meninggal') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama_meninggal') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nik_meninggal" class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik_meninggal') is-invalid @enderror" 
                                   id="nik_meninggal" name="nik_meninggal" required maxlength="16"
                                   value="{{ old('nik_meninggal') }}" placeholder="16 digit NIK">
                            @error('nik_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nomor_kk_meninggal" class="form-label">Nomor KK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nomor_kk_meninggal') is-invalid @enderror" 
                                   id="nomor_kk_meninggal" name="nomor_kk_meninggal" required maxlength="16"
                                   value="{{ old('nomor_kk_meninggal') }}" placeholder="16 digit nomor KK">
                            @error('nomor_kk_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat_meninggal" class="form-label">Alamat Terakhir <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat_meninggal') is-invalid @enderror" 
                                id="alamat_meninggal" name="alamat_meninggal" rows="3" required 
                                placeholder="Alamat lengkap terakhir almarhum/almarhumah...">{{ old('alamat_meninggal') }}</textarea>
                        @error('alamat_meninggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="section-divider">
                    <h5 class="section-title">Data Kematian</h5>

                    <div class="form-row three-col">
                        <div class="form-group">
                            <label for="hari_meninggal" class="form-label">Hari Meninggal <span class="text-danger">*</span></label>
                            <select class="form-select @error('hari_meninggal') is-invalid @enderror" 
                                    id="hari_meninggal" name="hari_meninggal" required>
                                <option value="">Pilih hari</option>
                                <option value="Senin" {{ old('hari_meninggal') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ old('hari_meninggal') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ old('hari_meninggal') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ old('hari_meninggal') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ old('hari_meninggal') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ old('hari_meninggal') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                <option value="Minggu" {{ old('hari_meninggal') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                            </select>
                            @error('hari_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_meninggal" class="form-label">Tanggal Meninggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_meninggal') is-invalid @enderror" 
                                   id="tanggal_meninggal" name="tanggal_meninggal" required 
                                   value="{{ old('tanggal_meninggal') }}">
                            @error('tanggal_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="waktu_meninggal" class="form-label">Waktu Meninggal <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('waktu_meninggal') is-invalid @enderror" 
                                   id="waktu_meninggal" name="waktu_meninggal" required 
                                   value="{{ old('waktu_meninggal') }}">
                            @error('waktu_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tempat_meninggal" class="form-label">Tempat Meninggal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_meninggal') is-invalid @enderror" 
                                   id="tempat_meninggal" name="tempat_meninggal" required 
                                   value="{{ old('tempat_meninggal') }}" placeholder="Rumah/Rumah Sakit/dll">
                            @error('tempat_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="penentu_kematian" class="form-label">Penentu Kematian <span class="text-danger">*</span></label>
                            <select class="form-select @error('penentu_kematian') is-invalid @enderror" 
                                    id="penentu_kematian" name="penentu_kematian" required>
                                <option value="">Pilih penentu kematian</option>
                                <option value="Dokter" {{ old('penentu_kematian') == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                                <option value="Tenaga Kesehatan" {{ old('penentu_kematian') == 'Tenaga Kesehatan' ? 'selected' : '' }}>Tenaga Kesehatan</option>
                                <option value="Kepolisian" {{ old('penentu_kematian') == 'Kepolisian' ? 'selected' : '' }}>Kepolisian</option>
                                <option value="Lainnya" {{ old('penentu_kematian') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('penentu_kematian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Dokumen Tambahan -->
                    <h5 class="section-title">Dokumen Tambahan (Opsional)</h5>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="surat_rs" class="form-label">Surat Keterangan Rumah Sakit</label>
                            <div class="upload-area" id="surat-rs-area" data-custom-handler="true">
                                <input type="file" class="form-control @error('surat_rs') is-invalid @enderror" 
                                       id="surat_rs" name="surat_rs" 
                                       accept=".jpg,.jpeg,.png,.pdf">
                                <div class="upload-placeholder">
                                    <i class="fas fa-file-medical fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Upload surat keterangan RS</p>
                                    <small class="text-muted">Format: JPG, JPEG, PNG, PDF (Max: 2MB)</small>
                                </div>
                                <div class="file-preview" style="display: none;">
                                    <div class="file-info">
                                        <i class="fas fa-file-medical text-primary"></i>
                                        <span class="file-name"></span>
                                        <button type="button" class="btn btn-sm btn-danger remove-file">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('surat_rs')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="foto_makam" class="form-label">Foto Makam</label>
                            <div class="upload-area" id="foto-makam-area" data-custom-handler="true">
                                <input type="file" class="form-control @error('foto_makam') is-invalid @enderror" 
                                       id="foto_makam" name="foto_makam" 
                                       accept=".jpg,.jpeg,.png">
                                <div class="upload-placeholder">
                                    <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Upload foto makam</p>
                                    <small class="text-muted">Format: JPG, JPEG, PNG (Max: 2MB)</small>
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
                            @error('foto_makam')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Pastikan semua data yang dimasukkan sudah benar. Data ini akan digunakan untuk generate PDF surat kematian.
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
// File upload handling for additional documents
document.addEventListener('DOMContentLoaded', function() {
    // Handle surat RS upload
    const suratRsInput = document.getElementById('surat_rs');
    const suratRsArea = document.getElementById('surat-rs-area');
    setupFileUpload(suratRsInput, suratRsArea, ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf']);

    // Handle foto makam upload
    const fotoMakamInput = document.getElementById('foto_makam');
    const fotoMakamArea = document.getElementById('foto-makam-area');
    setupFileUpload(fotoMakamInput, fotoMakamArea, ['image/jpeg', 'image/jpg', 'image/png']);
});

function setupFileUpload(input, area, allowedTypes) {
    const placeholder = area.querySelector('.upload-placeholder');
    const preview = area.querySelector('.file-preview');
    const fileName = preview.querySelector('.file-name');
    const removeBtn = preview.querySelector('.remove-file');

    // Handle file selection
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                input.value = '';
                return;
            }

            // Validate file type
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung.');
                input.value = '';
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
        input.value = '';
        placeholder.style.display = 'block';
        preview.style.display = 'none';
    });

    // Drag and drop functionality
    area.addEventListener('dragover', function(e) {
        e.preventDefault();
        area.classList.add('dragover');
    });

    area.addEventListener('dragleave', function(e) {
        e.preventDefault();
        area.classList.remove('dragover');
    });

    area.addEventListener('drop', function(e) {
        e.preventDefault();
        area.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            input.files = files;
            input.dispatchEvent(new Event('change'));
        }
    });
}

// Auto-format NIK and KK number inputs
document.getElementById('nik_meninggal').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').substring(0, 16);
});

document.getElementById('nomor_kk_meninggal').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').substring(0, 16);
});

// Auto-update hari based on tanggal_meninggal
document.getElementById('tanggal_meninggal').addEventListener('change', function(e) {
    if (this.value) {
        const date = new Date(this.value);
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const dayName = days[date.getDay()];
        document.getElementById('hari_meninggal').value = dayName;
    }
});
</script>
@endsection