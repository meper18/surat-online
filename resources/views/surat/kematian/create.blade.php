@extends('layouts.app')

@section('title', 'Input Detail Surat Kematian')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Input Detail Surat Kematian</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-kematian.store', $permohonan) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <label for="nama_meninggal" class="form-label">Nama Lengkap Almarhum/Almarhumah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_meninggal') is-invalid @enderror" 
                                   id="nama_meninggal" name="nama_meninggal" required 
                                   value="{{ old('nama_meninggal') }}" placeholder="Nama lengkap yang meninggal">
                            @error('nama_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tempat_lahir_meninggal" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_lahir_meninggal') is-invalid @enderror" 
                                   id="tempat_lahir_meninggal" name="tempat_lahir_meninggal" required 
                                   value="{{ old('tempat_lahir_meninggal') }}" placeholder="Tempat lahir">
                            @error('tempat_lahir_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_lahir_meninggal" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir_meninggal') is-invalid @enderror" 
                                   id="tanggal_lahir_meninggal" name="tanggal_lahir_meninggal" required 
                                   value="{{ old('tanggal_lahir_meninggal') }}">
                            @error('tanggal_lahir_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nik_meninggal" class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik_meninggal') is-invalid @enderror" 
                                   id="nik_meninggal" name="nik_meninggal" required maxlength="16"
                                   value="{{ old('nik_meninggal') }}" placeholder="16 digit NIK">
                            @error('nik_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nomor_kk_meninggal" class="form-label">Nomor KK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nomor_kk_meninggal') is-invalid @enderror" 
                                   id="nomor_kk_meninggal" name="nomor_kk_meninggal" required maxlength="16"
                                   value="{{ old('nomor_kk_meninggal') }}" placeholder="16 digit nomor KK">
                            @error('nomor_kk_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat_meninggal" class="form-label">Alamat Terakhir <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat_meninggal') is-invalid @enderror" 
                                id="alamat_meninggal" name="alamat_meninggal" rows="3" required 
                                placeholder="Alamat lengkap terakhir almarhum/almarhumah...">{{ old('alamat_meninggal') }}</textarea>
                        @error('alamat_meninggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">
                    <h5 class="text-primary mb-3">Data Kematian</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <label for="tanggal_meninggal" class="form-label">Tanggal Meninggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_meninggal') is-invalid @enderror" 
                                   id="tanggal_meninggal" name="tanggal_meninggal" required 
                                   value="{{ old('tanggal_meninggal') }}">
                            @error('tanggal_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="waktu_meninggal" class="form-label">Waktu Meninggal <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('waktu_meninggal') is-invalid @enderror" 
                                   id="waktu_meninggal" name="waktu_meninggal" required 
                                   value="{{ old('waktu_meninggal') }}">
                            @error('waktu_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tempat_meninggal" class="form-label">Tempat Meninggal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_meninggal') is-invalid @enderror" 
                                   id="tempat_meninggal" name="tempat_meninggal" required 
                                   value="{{ old('tempat_meninggal') }}" placeholder="Rumah/Rumah Sakit/dll">
                            @error('tempat_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
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
                    <h5 class="text-primary mb-3 mt-4">Dokumen Tambahan (Opsional)</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
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
                        
                        <div class="col-md-6">
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