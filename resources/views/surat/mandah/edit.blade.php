@extends('layouts.app')

@section('title', 'Edit Detail Surat Pindah/Mandah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Detail Surat Pindah/Mandah</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-mandah.update', $permohonan) }}">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="alamat_mandah" class="form-label">Alamat Tujuan Pindah <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat_mandah') is-invalid @enderror" 
                                    id="alamat_mandah" name="alamat_mandah" rows="3" required 
                                    placeholder="Masukkan alamat lengkap tujuan pindah...">{{ old('alamat_mandah', $permohonan->suratMandah->alamat_mandah ?? '') }}</textarea>
                            @error('alamat_mandah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kelurahan_mandah" class="form-label">Kelurahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kelurahan_mandah') is-invalid @enderror" 
                                   id="kelurahan_mandah" name="kelurahan_mandah" required 
                                   value="{{ old('kelurahan_mandah', $permohonan->suratMandah->kelurahan_mandah ?? '') }}" placeholder="Nama kelurahan">
                            @error('kelurahan_mandah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="kecamatan_mandah" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kecamatan_mandah') is-invalid @enderror" 
                                   id="kecamatan_mandah" name="kecamatan_mandah" required 
                                   value="{{ old('kecamatan_mandah', $permohonan->suratMandah->kecamatan_mandah ?? '') }}" placeholder="Nama kecamatan">
                            @error('kecamatan_mandah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kabupaten_mandah" class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kabupaten_mandah') is-invalid @enderror" 
                                   id="kabupaten_mandah" name="kabupaten_mandah" required 
                                   value="{{ old('kabupaten_mandah', $permohonan->suratMandah->kabupaten_mandah ?? '') }}" placeholder="Nama kabupaten/kota">
                            @error('kabupaten_mandah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="provinsi_mandah" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('provinsi_mandah') is-invalid @enderror" 
                                   id="provinsi_mandah" name="provinsi_mandah" required 
                                   value="{{ old('provinsi_mandah', $permohonan->suratMandah->provinsi_mandah ?? '') }}" placeholder="Nama provinsi">
                            @error('provinsi_mandah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alasan_pindah" class="form-label">Alasan Pindah <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alasan_pindah') is-invalid @enderror" 
                                id="alasan_pindah" name="alasan_pindah" rows="3" required 
                                placeholder="Masukkan alasan pindah...">{{ old('alasan_pindah', $permohonan->suratMandah->alasan_pindah ?? '') }}</textarea>
                        @error('alasan_pindah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">
                    <h5 class="text-primary mb-3">Data Keluarga yang Ikut Pindah (Opsional)</h5>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Anggota Keluarga 1</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nama_pengikut1" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('nama_pengikut1') is-invalid @enderror" 
                                           id="nama_pengikut1" name="nama_pengikut1" 
                                           value="{{ old('nama_pengikut1', $permohonan->suratMandah->nama_pengikut1 ?? '') }}" placeholder="Nama lengkap pengikut 1">
                                    @error('nama_pengikut1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="jenis_kelamin_pengikut1" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin_pengikut1') is-invalid @enderror" 
                                            id="jenis_kelamin_pengikut1" name="jenis_kelamin_pengikut1">
                                        <option value="">Pilih</option>
                                        <option value="L" {{ old('jenis_kelamin_pengikut1', $permohonan->suratMandah->jenis_kelamin_pengikut1 ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin_pengikut1', $permohonan->suratMandah->jenis_kelamin_pengikut1 ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin_pengikut1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="umur_pengikut1" class="form-label">Umur</label>
                                    <input type="number" class="form-control @error('umur_pengikut1') is-invalid @enderror" 
                                           id="umur_pengikut1" name="umur_pengikut1" min="0" max="150"
                                           value="{{ old('umur_pengikut1', $permohonan->suratMandah->umur_pengikut1 ?? '') }}" placeholder="Umur">
                                    @error('umur_pengikut1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="hubungan_pengikut1" class="form-label">Hubungan Keluarga</label>
                                <input type="text" class="form-control @error('hubungan_pengikut1') is-invalid @enderror" 
                                       id="hubungan_pengikut1" name="hubungan_pengikut1" 
                                       value="{{ old('hubungan_pengikut1', $permohonan->suratMandah->hubungan_pengikut1 ?? '') }}" placeholder="Istri, Anak, dll">
                                @error('hubungan_pengikut1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Anggota Keluarga 2</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nama_pengikut2" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('nama_pengikut2') is-invalid @enderror" 
                                           id="nama_pengikut2" name="nama_pengikut2" 
                                           value="{{ old('nama_pengikut2', $permohonan->suratMandah->nama_pengikut2 ?? '') }}" placeholder="Nama lengkap pengikut 2">
                                    @error('nama_pengikut2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="jenis_kelamin_pengikut2" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin_pengikut2') is-invalid @enderror" 
                                            id="jenis_kelamin_pengikut2" name="jenis_kelamin_pengikut2">
                                        <option value="">Pilih</option>
                                        <option value="L" {{ old('jenis_kelamin_pengikut2', $permohonan->suratMandah->jenis_kelamin_pengikut2 ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin_pengikut2', $permohonan->suratMandah->jenis_kelamin_pengikut2 ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin_pengikut2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="umur_pengikut2" class="form-label">Umur</label>
                                    <input type="number" class="form-control @error('umur_pengikut2') is-invalid @enderror" 
                                           id="umur_pengikut2" name="umur_pengikut2" min="0" max="150"
                                           value="{{ old('umur_pengikut2', $permohonan->suratMandah->umur_pengikut2 ?? '') }}" placeholder="Umur">
                                    @error('umur_pengikut2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="hubungan_pengikut2" class="form-label">Hubungan Keluarga</label>
                                <input type="text" class="form-control @error('hubungan_pengikut2') is-invalid @enderror" 
                                       id="hubungan_pengikut2" name="hubungan_pengikut2" 
                                       value="{{ old('hubungan_pengikut2', $permohonan->suratMandah->hubungan_pengikut2 ?? '') }}" placeholder="Istri, Anak, dll">
                                @error('hubungan_pengikut2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>Pastikan alamat tujuan pindah sudah benar dan lengkap</li>
                            <li>Data keluarga yang ikut pindah bersifat opsional</li>
                            <li>Data ini akan digunakan untuk generate PDF surat keterangan pindah</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('warga.permohonan.show', $permohonan) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection