@extends('layouts.app')

@section('title', 'Input Detail Surat Pindah/Mandah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Input Detail Surat Pindah/Mandah</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form method="POST" action="{{ route('warga.surat-mandah.store', $permohonan) }}">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="alamat_mandah" class="form-label">Alamat Tujuan Pindah <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat_mandah') is-invalid @enderror" 
                                    id="alamat_mandah" name="alamat_mandah" rows="3" required 
                                    placeholder="Masukkan alamat lengkap tujuan pindah...">{{ old('alamat_mandah') }}</textarea>
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
                                   value="{{ old('kelurahan_mandah') }}" placeholder="Nama kelurahan">
                            @error('kelurahan_mandah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="kecamatan_mandah" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kecamatan_mandah') is-invalid @enderror" 
                                   id="kecamatan_mandah" name="kecamatan_mandah" required 
                                   value="{{ old('kecamatan_mandah') }}" placeholder="Nama kecamatan">
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
                                   value="{{ old('kabupaten_mandah') }}" placeholder="Nama kabupaten/kota">
                            @error('kabupaten_mandah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="provinsi_mandah" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('provinsi_mandah') is-invalid @enderror" 
                                   id="provinsi_mandah" name="provinsi_mandah" required 
                                   value="{{ old('provinsi_mandah') }}" placeholder="Nama provinsi">
                            @error('provinsi_mandah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_keluarga_ikut" class="form-label">Jumlah Keluarga yang Ikut Pindah <span class="text-danger">*</span></label>
                        <select class="form-select @error('jumlah_keluarga_ikut') is-invalid @enderror" 
                                id="jumlah_keluarga_ikut" name="jumlah_keluarga_ikut" required onchange="togglePengikutFields()">
                            <option value="">Pilih jumlah keluarga</option>
                            <option value="0" {{ old('jumlah_keluarga_ikut') == '0' ? 'selected' : '' }}>0 (Hanya saya)</option>
                            <option value="1" {{ old('jumlah_keluarga_ikut') == '1' ? 'selected' : '' }}>1 orang</option>
                            <option value="2" {{ old('jumlah_keluarga_ikut') == '2' ? 'selected' : '' }}>2 orang</option>
                            <option value="3" {{ old('jumlah_keluarga_ikut') == '3' ? 'selected' : '' }}>3 orang</option>
                        </select>
                        @error('jumlah_keluarga_ikut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pengikut 1 -->
                    <div id="pengikut1" class="pengikut-section" style="display: none;">
                        <h6 class="text-primary">Data Pengikut 1</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama_pengikut1" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('nama_pengikut1') is-invalid @enderror" 
                                       id="nama_pengikut1" name="nama_pengikut1" 
                                       value="{{ old('nama_pengikut1') }}" placeholder="Nama lengkap pengikut 1">
                                @error('nama_pengikut1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="jenis_kelamin_pengikut1" class="form-label">Jenis Kelamin</label>
                                <select class="form-select @error('jenis_kelamin_pengikut1') is-invalid @enderror" 
                                        id="jenis_kelamin_pengikut1" name="jenis_kelamin_pengikut1">
                                    <option value="">Pilih</option>
                                    <option value="L" {{ old('jenis_kelamin_pengikut1') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin_pengikut1') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin_pengikut1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="hubungan_keluarga_pengikut1" class="form-label">Hubungan Keluarga</label>
                                <input type="text" class="form-control @error('hubungan_keluarga_pengikut1') is-invalid @enderror" 
                                       id="hubungan_keluarga_pengikut1" name="hubungan_keluarga_pengikut1" 
                                       value="{{ old('hubungan_keluarga_pengikut1') }}" placeholder="Istri/Anak/dll">
                                @error('hubungan_keluarga_pengikut1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pengikut 2 -->
                    <div id="pengikut2" class="pengikut-section" style="display: none;">
                        <h6 class="text-primary">Data Pengikut 2</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama_pengikut2" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('nama_pengikut2') is-invalid @enderror" 
                                       id="nama_pengikut2" name="nama_pengikut2" 
                                       value="{{ old('nama_pengikut2') }}" placeholder="Nama lengkap pengikut 2">
                                @error('nama_pengikut2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="jenis_kelamin_pengikut2" class="form-label">Jenis Kelamin</label>
                                <select class="form-select @error('jenis_kelamin_pengikut2') is-invalid @enderror" 
                                        id="jenis_kelamin_pengikut2" name="jenis_kelamin_pengikut2">
                                    <option value="">Pilih</option>
                                    <option value="L" {{ old('jenis_kelamin_pengikut2') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin_pengikut2') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin_pengikut2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="hubungan_keluarga_pengikut2" class="form-label">Hubungan Keluarga</label>
                                <input type="text" class="form-control @error('hubungan_keluarga_pengikut2') is-invalid @enderror" 
                                       id="hubungan_keluarga_pengikut2" name="hubungan_keluarga_pengikut2" 
                                       value="{{ old('hubungan_keluarga_pengikut2') }}" placeholder="Istri/Anak/dll">
                                @error('hubungan_keluarga_pengikut2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pengikut 3 -->
                    <div id="pengikut3" class="pengikut-section" style="display: none;">
                        <h6 class="text-primary">Data Pengikut 3</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama_pengikut3" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('nama_pengikut3') is-invalid @enderror" 
                                       id="nama_pengikut3" name="nama_pengikut3" 
                                       value="{{ old('nama_pengikut3') }}" placeholder="Nama lengkap pengikut 3">
                                @error('nama_pengikut3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="jenis_kelamin_pengikut3" class="form-label">Jenis Kelamin</label>
                                <select class="form-select @error('jenis_kelamin_pengikut3') is-invalid @enderror" 
                                        id="jenis_kelamin_pengikut3" name="jenis_kelamin_pengikut3">
                                    <option value="">Pilih</option>
                                    <option value="L" {{ old('jenis_kelamin_pengikut3') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin_pengikut3') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin_pengikut3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="hubungan_keluarga_pengikut3" class="form-label">Hubungan Keluarga</label>
                                <input type="text" class="form-control @error('hubungan_keluarga_pengikut3') is-invalid @enderror" 
                                       id="hubungan_keluarga_pengikut3" name="hubungan_keluarga_pengikut3" 
                                       value="{{ old('hubungan_keluarga_pengikut3') }}" placeholder="Istri/Anak/dll">
                                @error('hubungan_keluarga_pengikut3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Pastikan semua data yang dimasukkan sudah benar. Data ini akan digunakan untuk generate PDF surat.
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
function togglePengikutFields() {
    const jumlah = document.getElementById('jumlah_keluarga_ikut').value;
    const pengikutSections = document.querySelectorAll('.pengikut-section');
    
    // Hide all sections first
    pengikutSections.forEach(section => {
        section.style.display = 'none';
    });
    
    // Show sections based on selection
    for (let i = 1; i <= jumlah; i++) {
        const section = document.getElementById('pengikut' + i);
        if (section) {
            section.style.display = 'block';
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePengikutFields();
});
</script>
@endsection