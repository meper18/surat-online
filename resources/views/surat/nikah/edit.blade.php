@extends('layouts.app')

@section('title', 'Edit Detail Surat Keterangan Nikah/Belum Menikah')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .edit-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
    }

    .form-section {
        background: #f8f9ff;
        padding: 1.5rem;
        margin: 1.5rem 0;
        border-radius: 15px;
        border-left: 4px solid #667eea;
    }

    .section-title {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        background: #667eea;
        color: white;
        padding: 0.5rem;
        border-radius: 50%;
        font-size: 0.8rem;
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #e53e3e;
        box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
    }

    .invalid-feedback {
        display: block;
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .text-danger {
        color: #e53e3e !important;
    }

    .alert {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .alert-info {
        background: linear-gradient(135deg, #bee3f8 0%, #90cdf4 100%);
        color: #2c5282;
        border-left: 4px solid #3182ce;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fef5e7 0%, #fed7aa 100%);
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    .conditional-card {
        background: #f7fafc;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        margin: 1rem 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .conditional-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .conditional-card-body {
        padding: 1.5rem;
    }

    .actions-container {
        background: #f7fafc;
        padding: 2rem;
        border-radius: 15px;
        margin-top: 2rem;
    }

    .action-btn {
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-back {
        background: #718096;
        color: white;
    }

    .btn-back:hover {
        background: #4a5568;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border: none;
        margin: 2rem 0;
        border-radius: 2px;
    }

    .required-field::after {
        content: " *";
        color: #e53e3e;
    }

    .conditional-display {
        display: none;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem 0;
            margin-bottom: 1rem;
        }
        
        .edit-card {
            margin: 0 0.5rem;
        }
        
        .actions-container {
            padding: 1rem;
        }
        
        .action-btn {
            width: 100%;
            justify-content: center;
            margin-bottom: 0.5rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-1">
                        <i class="fas fa-edit me-3"></i>Edit Surat Keterangan Nikah/Belum Menikah
                    </h2>
                    <p class="mb-0 opacity-90">Permohonan #{{ $permohonan->kode_permohonan }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <i class="fas fa-heart fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="edit-card">
            <div class="card-body p-4">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-nikah.update', $permohonan) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-user-check"></i>
                            Status Pernikahan
                        </h5>
                        <div class="mb-3">
                            <label for="status_nikah" class="form-label required-field">Status Pernikahan</label>
                            <select class="form-select @error('status_nikah') is-invalid @enderror" id="status_nikah" name="status_nikah" required>
                                <option value="">Pilih status pernikahan</option>
                                <option value="Belum Menikah" {{ old('status_nikah', $permohonan->suratNikah->status_nikah ?? '') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="Menikah" {{ old('status_nikah', $permohonan->suratNikah->status_nikah ?? '') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="Cerai Hidup" {{ old('status_nikah', $permohonan->suratNikah->status_nikah ?? '') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                <option value="Cerai Mati" {{ old('status_nikah', $permohonan->suratNikah->status_nikah ?? '') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                            </select>
                            @error('status_nikah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="detail-nikah" class="conditional-display" data-condition="Menikah" data-field="status_nikah" data-current="{{ old('status_nikah', $permohonan->suratNikah->status_nikah ?? '') }}">
                        <div class="conditional-card">
                            <div class="conditional-card-header">
                                <i class="fas fa-ring"></i>
                                Detail Pernikahan
                            </div>
                            <div class="conditional-card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nama_pasangan" class="form-label">Nama Pasangan</label>
                                        <input type="text" class="form-control @error('nama_pasangan') is-invalid @enderror" 
                                               id="nama_pasangan" name="nama_pasangan" 
                                               value="{{ old('nama_pasangan', $permohonan->suratNikah->nama_pasangan ?? '') }}" placeholder="Masukkan nama lengkap pasangan">
                                        @error('nama_pasangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tanggal_nikah" class="form-label">Tanggal Pernikahan</label>
                                        <input type="date" class="form-control @error('tanggal_nikah') is-invalid @enderror" 
                                               id="tanggal_nikah" name="tanggal_nikah" 
                                               value="{{ old('tanggal_nikah', $permohonan->suratNikah->tanggal_nikah ?? '') }}">
                                        @error('tanggal_nikah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="tempat_nikah" class="form-label">Tempat Pernikahan</label>
                                        <input type="text" class="form-control @error('tempat_nikah') is-invalid @enderror" 
                                               id="tempat_nikah" name="tempat_nikah" 
                                               value="{{ old('tempat_nikah', $permohonan->suratNikah->tempat_nikah ?? '') }}" placeholder="Contoh: KUA Kecamatan..., Gereja..., dll">
                                        @error('tempat_nikah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nomor_akta_nikah" class="form-label">Nomor Akta Nikah</label>
                                        <input type="text" class="form-control @error('nomor_akta_nikah') is-invalid @enderror" 
                                               id="nomor_akta_nikah" name="nomor_akta_nikah" 
                                               value="{{ old('nomor_akta_nikah', $permohonan->suratNikah->nomor_akta_nikah ?? '') }}" placeholder="Masukkan nomor akta nikah">
                                        @error('nomor_akta_nikah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="detail-cerai" class="conditional-display" data-condition="Cerai Hidup,Cerai Mati" data-field="status_nikah" data-current="{{ old('status_nikah', $permohonan->suratNikah->status_nikah ?? '') }}">
                        <div class="conditional-card">
                            <div class="conditional-card-header">
                                <i class="fas fa-user-times"></i>
                                Detail Perceraian
                            </div>
                            <div class="conditional-card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="tanggal_cerai" class="form-label">Tanggal Cerai</label>
                                        <input type="date" class="form-control @error('tanggal_cerai') is-invalid @enderror" 
                                               id="tanggal_cerai" name="tanggal_cerai" 
                                               value="{{ old('tanggal_cerai', $permohonan->suratNikah->tanggal_cerai ?? '') }}">
                                        @error('tanggal_cerai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nomor_akta_cerai" class="form-label">Nomor Akta Cerai</label>
                                        <input type="text" class="form-control @error('nomor_akta_cerai') is-invalid @enderror" 
                                               id="nomor_akta_cerai" name="nomor_akta_cerai" 
                                               value="{{ old('nomor_akta_cerai', $permohonan->suratNikah->nomor_akta_cerai ?? '') }}" placeholder="Masukkan nomor akta cerai">
                                        @error('nomor_akta_cerai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-clipboard-list"></i>
                            Keperluan Surat
                        </h5>
                        <div class="mb-3">
                            <label for="keperluan" class="form-label required-field">Keperluan</label>
                            <select class="form-select @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" required>
                                <option value="">Pilih keperluan</option>
                                <option value="Melamar Pekerjaan" {{ old('keperluan', $permohonan->suratNikah->keperluan ?? '') == 'Melamar Pekerjaan' ? 'selected' : '' }}>Melamar Pekerjaan</option>
                                <option value="Pendaftaran Sekolah/Kuliah" {{ old('keperluan', $permohonan->suratNikah->keperluan ?? '') == 'Pendaftaran Sekolah/Kuliah' ? 'selected' : '' }}>Pendaftaran Sekolah/Kuliah</option>
                                <option value="Pengajuan Beasiswa" {{ old('keperluan', $permohonan->suratNikah->keperluan ?? '') == 'Pengajuan Beasiswa' ? 'selected' : '' }}>Pengajuan Beasiswa</option>
                                <option value="Persyaratan Administrasi" {{ old('keperluan', $permohonan->suratNikah->keperluan ?? '') == 'Persyaratan Administrasi' ? 'selected' : '' }}>Persyaratan Administrasi</option>
                                <option value="Pengajuan Kredit Bank" {{ old('keperluan', $permohonan->suratNikah->keperluan ?? '') == 'Pengajuan Kredit Bank' ? 'selected' : '' }}>Pengajuan Kredit Bank</option>
                                <option value="Persyaratan Nikah" {{ old('keperluan', $permohonan->suratNikah->keperluan ?? '') == 'Persyaratan Nikah' ? 'selected' : '' }}>Persyaratan Nikah</option>
                                <option value="Pengajuan Visa" {{ old('keperluan', $permohonan->suratNikah->keperluan ?? '') == 'Pengajuan Visa' ? 'selected' : '' }}>Pengajuan Visa</option>
                                <option value="custom">Lainnya (Sebutkan)</option>
                            </select>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="custom-keperluan" class="mb-3 conditional-display" data-condition="custom" data-field="keperluan" data-current="{{ old('keperluan', $permohonan->suratNikah->keperluan ?? '') }}">
                            <label for="keperluan_custom" class="form-label required-field">Keperluan Lainnya</label>
                            <input type="text" class="form-control @error('keperluan_custom') is-invalid @enderror" 
                                   id="keperluan_custom" name="keperluan_custom" 
                                   value="{{ old('keperluan_custom', (old('keperluan', $permohonan->suratNikah->keperluan ?? '') == 'custom' ? ($permohonan->suratNikah->keperluan ?? '') : '')) }}" placeholder="Sebutkan keperluan lainnya...">
                            @error('keperluan_custom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>Pastikan status pernikahan yang dipilih sesuai dengan kondisi sebenarnya</li>
                            <li>Jika sudah menikah, isi detail pernikahan dengan lengkap</li>
                            <li>Jika sudah cerai, isi detail perceraian dengan lengkap</li>
                            <li>Data ini akan digunakan untuk generate PDF surat keterangan nikah/belum menikah</li>
                        </ul>
                    </div>

                    <div class="actions-container">
                        <div class="d-flex justify-content-between flex-wrap gap-2">
                            <a href="{{ route('warga.permohonan.show', $permohonan) }}" class="action-btn btn-back">
                                <i class="fas fa-arrow-left"></i>Kembali
                            </a>
                            <button type="submit" class="action-btn btn-save">
                                <i class="fas fa-save"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusNikahSelect = document.getElementById('status_nikah');
    const detailNikahDiv = document.getElementById('detail-nikah');
    const detailCeraiDiv = document.getElementById('detail-cerai');
    const keperluanSelect = document.getElementById('keperluan');
    const customKeperluanDiv = document.getElementById('custom-keperluan');
    
    // Initialize display based on current values
    function initializeDisplay() {
        const currentStatus = statusNikahSelect.value;
        if (currentStatus === 'Menikah') {
            detailNikahDiv.style.display = 'block';
            detailCeraiDiv.style.display = 'none';
        } else if (currentStatus === 'Cerai Hidup' || currentStatus === 'Cerai Mati') {
            detailNikahDiv.style.display = 'none';
            detailCeraiDiv.style.display = 'block';
        } else {
            detailNikahDiv.style.display = 'none';
            detailCeraiDiv.style.display = 'none';
        }

        const currentKeperluan = keperluanSelect.value;
        if (currentKeperluan === 'custom') {
            customKeperluanDiv.style.display = 'block';
            document.getElementById('keperluan_custom').required = true;
        } else {
            customKeperluanDiv.style.display = 'none';
            document.getElementById('keperluan_custom').required = false;
        }
    }
    
    statusNikahSelect.addEventListener('change', function() {
        const status = this.value;
        
        if (status === 'Menikah') {
            detailNikahDiv.style.display = 'block';
            detailCeraiDiv.style.display = 'none';
        } else if (status === 'Cerai Hidup' || status === 'Cerai Mati') {
            detailNikahDiv.style.display = 'none';
            detailCeraiDiv.style.display = 'block';
        } else {
            detailNikahDiv.style.display = 'none';
            detailCeraiDiv.style.display = 'none';
        }
    });
    
    keperluanSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customKeperluanDiv.style.display = 'block';
            document.getElementById('keperluan_custom').required = true;
        } else {
            customKeperluanDiv.style.display = 'none';
            document.getElementById('keperluan_custom').required = false;
        }
    });

    // Initialize on page load
    initializeDisplay();
});
</script>
@endsection