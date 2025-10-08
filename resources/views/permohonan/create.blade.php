@extends('layouts.app')

@section('title', 'Buat Permohonan Surat Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Buat Permohonan Surat Baru</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('warga.permohonan.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="jenis_surat_id" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                        <select class="form-select @error('jenis_surat_id') is-invalid @enderror" id="jenis_surat_id" name="jenis_surat_id" required>
                            <option value="">Pilih Jenis Surat</option>
                            @foreach($jenisSurat as $jenis)
                                <option value="{{ $jenis->id }}" {{ old('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama }} - {{ $jenis->kode }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_surat_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" rows="3" required placeholder="Jelaskan keperluan surat ini...">{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_surat_pernyataan" class="form-label">Tanggal Surat Pernyataan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_surat_pernyataan') is-invalid @enderror" id="tanggal_surat_pernyataan" name="tanggal_surat_pernyataan" value="{{ old('tanggal_surat_pernyataan', date('Y-m-d')) }}" required>
                        @error('tanggal_surat_pernyataan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Tanggal yang akan tertera pada surat pernyataan
                        </div>
                    </div>

                    <!-- Required Documents Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i>Dokumen Wajib <span class="text-danger">*</span></h5>
                            <small class="text-muted">Semua dokumen berikut wajib diupload</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- KTP Pemohon -->
                                <div class="col-md-6 mb-3">
                                    <label for="ktp_pemohon" class="form-label">KTP Pemohon <span class="text-danger">*</span></label>
                                    <div class="upload-area border border-2 border-dashed rounded p-3 text-center" data-target="ktp_pemohon">
                                        <input type="file" class="form-control d-none @error('ktp_pemohon') is-invalid @enderror" id="ktp_pemohon" name="ktp_pemohon" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                            <p class="mb-1">Klik untuk upload KTP Pemohon</p>
                                            <small class="text-muted">PDF, JPG, PNG (max 2MB)</small>
                                        </div>
                                        <div class="file-preview d-none">
                                            <i class="fas fa-file fa-2x text-success mb-2"></i>
                                            <p class="file-name mb-1"></p>
                                            <small class="file-size text-muted"></small>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-file">
                                                    <i class="fas fa-times me-1"></i>Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('ktp_pemohon')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- KK Pemohon -->
                                <div class="col-md-6 mb-3">
                                    <label for="kk_pemohon" class="form-label">Kartu Keluarga Pemohon <span class="text-danger">*</span></label>
                                    <div class="upload-area border border-2 border-dashed rounded p-3 text-center" data-target="kk_pemohon">
                                        <input type="file" class="form-control d-none @error('kk_pemohon') is-invalid @enderror" id="kk_pemohon" name="kk_pemohon" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                            <p class="mb-1">Klik untuk upload KK Pemohon</p>
                                            <small class="text-muted">PDF, JPG, PNG (max 2MB)</small>
                                        </div>
                                        <div class="file-preview d-none">
                                            <i class="fas fa-file fa-2x text-success mb-2"></i>
                                            <p class="file-name mb-1"></p>
                                            <small class="file-size text-muted"></small>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-file">
                                                    <i class="fas fa-times me-1"></i>Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('kk_pemohon')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Surat Pernyataan Kepala Lingkungan -->
                                <div class="col-md-6 mb-3">
                                    <label for="surat_pernyataan_kaling" class="form-label">Surat Pernyataan + TTD Kepala Lingkungan <span class="text-danger">*</span></label>
                                    <div class="upload-area border border-2 border-dashed rounded p-3 text-center" data-target="surat_pernyataan_kaling">
                                        <input type="file" class="form-control d-none @error('surat_pernyataan_kaling') is-invalid @enderror" id="surat_pernyataan_kaling" name="surat_pernyataan_kaling" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                            <p class="mb-1">Klik untuk upload Surat Pernyataan</p>
                                            <small class="text-muted">PDF, JPG, PNG (max 2MB)</small>
                                        </div>
                                        <div class="file-preview d-none">
                                            <i class="fas fa-file fa-2x text-success mb-2"></i>
                                            <p class="file-name mb-1"></p>
                                            <small class="file-size text-muted"></small>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-file">
                                                    <i class="fas fa-times me-1"></i>Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('surat_pernyataan_kaling')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- KTP Saksi 1 -->
                                <div class="col-md-6 mb-3">
                                    <label for="ktp_saksi1" class="form-label">KTP Saksi 1 <span class="text-danger">*</span></label>
                                    <div class="upload-area border border-2 border-dashed rounded p-3 text-center" data-target="ktp_saksi1">
                                        <input type="file" class="form-control d-none @error('ktp_saksi1') is-invalid @enderror" id="ktp_saksi1" name="ktp_saksi1" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                            <p class="mb-1">Klik untuk upload KTP Saksi 1</p>
                                            <small class="text-muted">PDF, JPG, PNG (max 2MB)</small>
                                        </div>
                                        <div class="file-preview d-none">
                                            <i class="fas fa-file fa-2x text-success mb-2"></i>
                                            <p class="file-name mb-1"></p>
                                            <small class="file-size text-muted"></small>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-file">
                                                    <i class="fas fa-times me-1"></i>Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('ktp_saksi1')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- KTP Saksi 2 -->
                                <div class="col-md-6 mb-3">
                                    <label for="ktp_saksi2" class="form-label">KTP Saksi 2 <span class="text-danger">*</span></label>
                                    <div class="upload-area border border-2 border-dashed rounded p-3 text-center" data-target="ktp_saksi2">
                                        <input type="file" class="form-control d-none @error('ktp_saksi2') is-invalid @enderror" id="ktp_saksi2" name="ktp_saksi2" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                            <p class="mb-1">Klik untuk upload KTP Saksi 2</p>
                                            <small class="text-muted">PDF, JPG, PNG (max 2MB)</small>
                                        </div>
                                        <div class="file-preview d-none">
                                            <i class="fas fa-file fa-2x text-success mb-2"></i>
                                            <p class="file-name mb-1"></p>
                                            <small class="file-size text-muted"></small>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-file">
                                                    <i class="fas fa-times me-1"></i>Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('ktp_saksi2')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan Tambahan</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="2" placeholder="Catatan atau informasi tambahan (opsional)">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Pastikan semua dokumen wajib sudah diupload</li>
                            <li>Ukuran maksimal setiap file adalah 2MB</li>
                            <li>Format file yang diterima: PDF, JPG, JPEG, PNG</li>
                            <li>Permohonan akan diproses dalam 1-3 hari kerja</li>
                            <li>Anda akan mendapat notifikasi jika status permohonan berubah</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('warga.permohonan.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="btn-text">
                                <i class="fas fa-paper-plane me-1"></i>Kirim Permohonan
                            </span>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Disable the global file upload initialization for this page
        // since we have custom handling here
        
        // Mark upload areas as having custom handlers to prevent duplicate initialization
        document.querySelectorAll('.upload-area').forEach(area => {
            area.setAttribute('data-custom-handler', 'true');
        });

        // Handle multiple file uploads
        const uploadAreas = document.querySelectorAll('.upload-area');
        
        uploadAreas.forEach(function(uploadArea) {
            const targetId = uploadArea.getAttribute('data-target');
            const fileInput = document.getElementById(targetId);
            const uploadContent = uploadArea.querySelector('.upload-content');
            const filePreview = uploadArea.querySelector('.file-preview');
            const fileName = uploadArea.querySelector('.file-name');
            const fileSize = uploadArea.querySelector('.file-size');
            const removeBtn = uploadArea.querySelector('.remove-file');

            // Remove any existing event listeners to prevent duplicates
            uploadArea.replaceWith(uploadArea.cloneNode(true));
            const newUploadArea = document.querySelector(`[data-target="${targetId}"]`);
            const newFileInput = document.getElementById(targetId);
            const newUploadContent = newUploadArea.querySelector('.upload-content');
            const newFilePreview = newUploadArea.querySelector('.file-preview');
            const newFileName = newUploadArea.querySelector('.file-name');
            const newFileSize = newUploadArea.querySelector('.file-size');
            const newRemoveBtn = newUploadArea.querySelector('.remove-file');

            // Click to upload
            newUploadArea.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-file') || e.target.closest('.remove-file')) {
                    return;
                }
                if (!newFilePreview.classList.contains('d-none')) return;
                newFileInput.click();
            });

            // Drag and drop functionality
            newUploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                newUploadArea.classList.add('border-primary', 'bg-light');
            });

            newUploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                newUploadArea.classList.remove('border-primary', 'bg-light');
            });

            newUploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                newUploadArea.classList.remove('border-primary', 'bg-light');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    newFileInput.files = files;
                    handleFileSelect(files[0], newUploadArea);
                }
            });

            // File input change
            newFileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    handleFileSelect(e.target.files[0], newUploadArea);
                }
            });

            // Remove file
            newRemoveBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                newFileInput.value = '';
                showUploadContent(newUploadArea);
            });
        });

        function handleFileSelect(file, uploadArea) {
            // Validate file type
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Tipe file tidak diizinkan. Hanya PDF, JPG, dan PNG yang diperbolehkan.');
                uploadArea.querySelector('input[type="file"]').value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                uploadArea.querySelector('input[type="file"]').value = '';
                return;
            }

            // Show file preview
            const fileName = uploadArea.querySelector('.file-name');
            const fileSize = uploadArea.querySelector('.file-size');
            
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            showFilePreview(uploadArea);
        }

        function showFilePreview(uploadArea) {
            const uploadContent = uploadArea.querySelector('.upload-content');
            const filePreview = uploadArea.querySelector('.file-preview');
            
            uploadContent.classList.add('d-none');
            filePreview.classList.remove('d-none');
            uploadArea.classList.add('border-success');
        }

        function showUploadContent(uploadArea) {
            const uploadContent = uploadArea.querySelector('.upload-content');
            const filePreview = uploadArea.querySelector('.file-preview');
            
            filePreview.classList.add('d-none');
            uploadContent.classList.remove('d-none');
            uploadArea.classList.remove('border-success');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Form validation before submit
        document.getElementById('submitBtn').addEventListener('click', function(e) {
            const requiredFiles = ['ktp_pemohon', 'kk_pemohon', 'surat_pernyataan_kaling', 'ktp_saksi1', 'ktp_saksi2'];
            let allFilesUploaded = true;
            let missingFiles = [];

            requiredFiles.forEach(function(fileId) {
                const fileInput = document.getElementById(fileId);
                if (!fileInput.files.length) {
                    allFilesUploaded = false;
                    const label = document.querySelector(`label[for="${fileId}"]`).textContent.replace(' *', '');
                    missingFiles.push(label);
                }
            });

            if (!allFilesUploaded) {
                e.preventDefault();
                alert('Mohon lengkapi dokumen berikut:\n- ' + missingFiles.join('\n- '));
                return false;
            }
        });
    });
</script>
@endsection