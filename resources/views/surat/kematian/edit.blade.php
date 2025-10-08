@extends('layouts.app')

@section('title', 'Edit Detail Surat Kematian')

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
                        <i class="fas fa-edit me-3"></i>Edit Detail Surat Kematian
                    </h2>
                    <p class="mb-0 opacity-90">{{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <i class="fas fa-file-alt fa-3x opacity-50"></i>
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

                <form method="POST" action="{{ route('warga.surat-kematian.update', $permohonan) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-user"></i>
                            Data Pemohon
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="hubungan_keluarga" class="form-label required-field">Hubungan Keluarga dengan Almarhum/Almarhumah</label>
                                <select class="form-select @error('hubungan_keluarga') is-invalid @enderror" 
                                        id="hubungan_keluarga" name="hubungan_keluarga" required>
                                    <option value="">Pilih hubungan keluarga</option>
                                    <option value="Ayah" {{ old('hubungan_keluarga', $permohonan->suratKematian->hubungan_keluarga ?? '') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="Ibu" {{ old('hubungan_keluarga', $permohonan->suratKematian->hubungan_keluarga ?? '') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="Suami" {{ old('hubungan_keluarga', $permohonan->suratKematian->hubungan_keluarga ?? '') == 'Suami' ? 'selected' : '' }}>Suami</option>
                                    <option value="Istri" {{ old('hubungan_keluarga', $permohonan->suratKematian->hubungan_keluarga ?? '') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                    <option value="Anak" {{ old('hubungan_keluarga', $permohonan->suratKematian->hubungan_keluarga ?? '') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="Saudara" {{ old('hubungan_keluarga', $permohonan->suratKematian->hubungan_keluarga ?? '') == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                                    <option value="Keponakan" {{ old('hubungan_keluarga', $permohonan->suratKematian->hubungan_keluarga ?? '') == 'Keponakan' ? 'selected' : '' }}>Keponakan</option>
                                    <option value="Cucu" {{ old('hubungan_keluarga', $permohonan->suratKematian->hubungan_keluarga ?? '') == 'Cucu' ? 'selected' : '' }}>Cucu</option>
                                    <option value="Lainnya" {{ old('hubungan_keluarga', $permohonan->suratKematian->hubungan_keluarga ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('hubungan_keluarga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">
                    
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-user-times"></i>
                            Data Almarhum/Almarhumah
                        </h5>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="nama_meninggal" class="form-label">Nama Lengkap Almarhum/Almarhumah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_meninggal') is-invalid @enderror" 
                                   id="nama_meninggal" name="nama_meninggal" required 
                                   value="{{ old('nama_meninggal', $permohonan->suratKematian->nama_meninggal ?? '') }}" placeholder="Masukkan nama lengkap almarhum/almarhumah">
                            @error('nama_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nik_meninggal" class="form-label">NIK Almarhum/Almarhumah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik_meninggal') is-invalid @enderror" 
                                   id="nik_meninggal" name="nik_meninggal" required maxlength="16"
                                   value="{{ old('nik_meninggal', $permohonan->suratKematian->nik_meninggal ?? '') }}" placeholder="Masukkan NIK almarhum/almarhumah">
                            @error('nik_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nomor_kk_meninggal" class="form-label">Nomor KK Almarhum/Almarhumah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nomor_kk_meninggal') is-invalid @enderror" 
                                   id="nomor_kk_meninggal" name="nomor_kk_meninggal" required maxlength="16"
                                   value="{{ old('nomor_kk_meninggal', $permohonan->suratKematian->nomor_kk_meninggal ?? '') }}" placeholder="Masukkan nomor KK almarhum/almarhumah">
                            @error('nomor_kk_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tempat_lahir_meninggal" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_lahir_meninggal') is-invalid @enderror" 
                                   id="tempat_lahir_meninggal" name="tempat_lahir_meninggal" required 
                                   value="{{ old('tempat_lahir_meninggal', $permohonan->suratKematian->tempat_lahir_meninggal ?? '') }}" placeholder="Masukkan tempat lahir">
                            @error('tempat_lahir_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_lahir_meninggal" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir_meninggal') is-invalid @enderror" 
                                   id="tanggal_lahir_meninggal" name="tanggal_lahir_meninggal" required 
                                   value="{{ old('tanggal_lahir_meninggal', $permohonan->suratKematian->tanggal_lahir_meninggal ?? '') }}">
                            @error('tanggal_lahir_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="agama_meninggal" class="form-label required-field">Agama</label>
                            <select class="form-select @error('agama_meninggal') is-invalid @enderror" 
                                    id="agama_meninggal" name="agama_meninggal" required>
                                <option value="">Pilih agama</option>
                                <option value="Islam" {{ old('agama_meninggal', $permohonan->suratKematian->agama_meninggal ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama_meninggal', $permohonan->suratKematian->agama_meninggal ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama_meninggal', $permohonan->suratKematian->agama_meninggal ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama_meninggal', $permohonan->suratKematian->agama_meninggal ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama_meninggal', $permohonan->suratKematian->agama_meninggal ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama_meninggal', $permohonan->suratKematian->agama_meninggal ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="alamat_meninggal" class="form-label required-field">Alamat Terakhir</label>
                            <textarea class="form-control @error('alamat_meninggal') is-invalid @enderror" 
                                      id="alamat_meninggal" name="alamat_meninggal" required rows="3" 
                                      placeholder="Masukkan alamat terakhir almarhum/almarhumah">{{ old('alamat_meninggal', $permohonan->suratKematian->alamat_meninggal ?? '') }}</textarea>
                            @error('alamat_meninggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    </div>

                    <hr class="section-divider">

                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-calendar-times"></i>
                            Data Kematian
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="hari_meninggal" class="form-label required-field">Hari Kematian</label>
                                <select class="form-select @error('hari_meninggal') is-invalid @enderror" 
                                        id="hari_meninggal" name="hari_meninggal" required>
                                    <option value="">Pilih hari</option>
                                    <option value="Senin" {{ old('hari_meninggal', $permohonan->suratKematian->hari_meninggal ?? '') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="Selasa" {{ old('hari_meninggal', $permohonan->suratKematian->hari_meninggal ?? '') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="Rabu" {{ old('hari_meninggal', $permohonan->suratKematian->hari_meninggal ?? '') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="Kamis" {{ old('hari_meninggal', $permohonan->suratKematian->hari_meninggal ?? '') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="Jumat" {{ old('hari_meninggal', $permohonan->suratKematian->hari_meninggal ?? '') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="Sabtu" {{ old('hari_meninggal', $permohonan->suratKematian->hari_meninggal ?? '') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                    <option value="Minggu" {{ old('hari_meninggal', $permohonan->suratKematian->hari_meninggal ?? '') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                                </select>
                                @error('hari_meninggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="tanggal_meninggal" class="form-label required-field">Tanggal Kematian</label>
                                <input type="date" class="form-control @error('tanggal_meninggal') is-invalid @enderror" 
                                       id="tanggal_meninggal" name="tanggal_meninggal" required 
                                       value="{{ old('tanggal_meninggal', $permohonan->suratKematian->tanggal_meninggal ?? '') }}">
                                @error('tanggal_meninggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="waktu_meninggal" class="form-label required-field">Waktu Kematian</label>
                                <input type="time" class="form-control @error('waktu_meninggal') is-invalid @enderror" 
                                       id="waktu_meninggal" name="waktu_meninggal" required 
                                       value="{{ old('waktu_meninggal', $permohonan->suratKematian->waktu_meninggal ? date('H:i', strtotime($permohonan->suratKematian->waktu_meninggal)) : '') }}">
                                @error('waktu_meninggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tempat_meninggal" class="form-label required-field">Tempat Kematian</label>
                                <input type="text" class="form-control @error('tempat_meninggal') is-invalid @enderror" 
                                       id="tempat_meninggal" name="tempat_meninggal" required 
                                       value="{{ old('tempat_meninggal', $permohonan->suratKematian->tempat_meninggal ?? '') }}" placeholder="Masukkan tempat kematian">
                                @error('tempat_meninggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="penentu_kematian" class="form-label required-field">Penentu Kematian</label>
                                <select class="form-select @error('penentu_kematian') is-invalid @enderror" 
                                        id="penentu_kematian" name="penentu_kematian" required>
                                    <option value="">Pilih penentu kematian</option>
                                    <option value="Dokter" {{ old('penentu_kematian', $permohonan->suratKematian->penentu_kematian ?? '') == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                                    <option value="Tenaga Kesehatan" {{ old('penentu_kematian', $permohonan->suratKematian->penentu_kematian ?? '') == 'Tenaga Kesehatan' ? 'selected' : '' }}>Tenaga Kesehatan</option>
                                    <option value="Kepolisian" {{ old('penentu_kematian', $permohonan->suratKematian->penentu_kematian ?? '') == 'Kepolisian' ? 'selected' : '' }}>Kepolisian</option>
                                    <option value="Lainnya" {{ old('penentu_kematian', $permohonan->suratKematian->penentu_kematian ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('penentu_kematian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
@endsection