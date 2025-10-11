@extends('layouts.app')

@section('title', 'Input Detail Surat Pindah/Mandah')

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

.pengikut-section {
    background: #f8fafc;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    border-left: 4px solid #3b82f6;
}

.pengikut-section h6 {
    color: #1f2937;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1rem;
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

.pengikut-row {
    display: flex;
    flex-direction: column;
    gap: 1rem;
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
    
    .pengikut-row {
        flex-direction: row;
    }
    
    .pengikut-row .form-group:first-child {
        flex: 2;
    }
    
    .pengikut-row .form-group:not(:first-child) {
        flex: 1;
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
    .pengikut-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .pengikut-row .form-group:first-child {
        grid-column: 1 / -1;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid px-3">
    <div class="form-card">
        <div class="page-header">
            <h4><i class="fas fa-file-alt me-2"></i>Input Detail Surat Pindah/Mandah</h4>
        </div>
        <div class="p-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Permohonan:</strong> {{ $permohonan->jenisSurat->nama }} - {{ $permohonan->kode_permohonan }}
                </div>

                <form action="{{ route('surat.mandah.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="alamat_mandah" class="form-label">Alamat Tujuan Pindah <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat_mandah') is-invalid @enderror" 
                                id="alamat_mandah" name="alamat_mandah" rows="3" required 
                                placeholder="Masukkan alamat lengkap tujuan pindah...">{{ old('alamat_mandah') }}</textarea>
                        @error('alamat_mandah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="kelurahan_tujuan" class="form-label">Kelurahan Tujuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kelurahan_tujuan') is-invalid @enderror" 
                                   id="kelurahan_tujuan" name="kelurahan_tujuan" value="{{ old('kelurahan_tujuan') }}" required>
                            @error('kelurahan_tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kecamatan_tujuan" class="form-label">Kecamatan Tujuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kecamatan_tujuan') is-invalid @enderror" 
                                   id="kecamatan_tujuan" name="kecamatan_tujuan" value="{{ old('kecamatan_tujuan') }}" required>
                            @error('kecamatan_tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="kabupaten_tujuan" class="form-label">Kabupaten/Kota Tujuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kabupaten_tujuan') is-invalid @enderror" 
                                   id="kabupaten_tujuan" name="kabupaten_tujuan" value="{{ old('kabupaten_tujuan') }}" required>
                            @error('kabupaten_tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="provinsi_tujuan" class="form-label">Provinsi Tujuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('provinsi_tujuan') is-invalid @enderror" 
                                   id="provinsi_tujuan" name="provinsi_tujuan" value="{{ old('provinsi_tujuan') }}" required>
                            @error('provinsi_tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_keluarga" class="form-label">Jumlah Anggota Keluarga yang Ikut Pindah <span class="text-danger">*</span></label>
                        <select class="form-select @error('jumlah_keluarga') is-invalid @enderror" 
                                id="jumlah_keluarga" name="jumlah_keluarga" required onchange="togglePengikutSections()">
                            <option value="">Pilih jumlah...</option>
                            <option value="1" {{ old('jumlah_keluarga') == '1' ? 'selected' : '' }}>1 orang (hanya pemohon)</option>
                            <option value="2" {{ old('jumlah_keluarga') == '2' ? 'selected' : '' }}>2 orang</option>
                            <option value="3" {{ old('jumlah_keluarga') == '3' ? 'selected' : '' }}>3 orang</option>
                            <option value="4" {{ old('jumlah_keluarga') == '4' ? 'selected' : '' }}>4 orang</option>
                        </select>
                        @error('jumlah_keluarga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pengikut 1 -->
                    <div id="pengikut1" class="pengikut-section" style="display: none;">
                        <h6><i class="fas fa-user me-2"></i>Data Pengikut 1</h6>
                        <div class="pengikut-row">
                            <div class="form-group">
                                <label for="nama_pengikut1" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_pengikut1" name="nama_pengikut1" 
                                       value="{{ old('nama_pengikut1') }}">
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin_pengikut1" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin_pengikut1" name="jenis_kelamin_pengikut1">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('jenis_kelamin_pengikut1') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin_pengikut1') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="hubungan_pengikut1" class="form-label">Hubungan</label>
                                <select class="form-select" id="hubungan_pengikut1" name="hubungan_pengikut1">
                                    <option value="">Pilih...</option>
                                    <option value="Istri" {{ old('hubungan_pengikut1') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                    <option value="Suami" {{ old('hubungan_pengikut1') == 'Suami' ? 'selected' : '' }}>Suami</option>
                                    <option value="Anak" {{ old('hubungan_pengikut1') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="Orang Tua" {{ old('hubungan_pengikut1') == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                    <option value="Saudara" {{ old('hubungan_pengikut1') == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pengikut 2 -->
                    <div id="pengikut2" class="pengikut-section" style="display: none;">
                        <h6><i class="fas fa-user me-2"></i>Data Pengikut 2</h6>
                        <div class="pengikut-row">
                            <div class="form-group">
                                <label for="nama_pengikut2" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_pengikut2" name="nama_pengikut2" 
                                       value="{{ old('nama_pengikut2') }}">
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin_pengikut2" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin_pengikut2" name="jenis_kelamin_pengikut2">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('jenis_kelamin_pengikut2') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin_pengikut2') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="hubungan_pengikut2" class="form-label">Hubungan</label>
                                <select class="form-select" id="hubungan_pengikut2" name="hubungan_pengikut2">
                                    <option value="">Pilih...</option>
                                    <option value="Istri" {{ old('hubungan_pengikut2') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                    <option value="Suami" {{ old('hubungan_pengikut2') == 'Suami' ? 'selected' : '' }}>Suami</option>
                                    <option value="Anak" {{ old('hubungan_pengikut2') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="Orang Tua" {{ old('hubungan_pengikut2') == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                    <option value="Saudara" {{ old('hubungan_pengikut2') == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pengikut 3 -->
                    <div id="pengikut3" class="pengikut-section" style="display: none;">
                        <h6><i class="fas fa-user me-2"></i>Data Pengikut 3</h6>
                        <div class="pengikut-row">
                            <div class="form-group">
                                <label for="nama_pengikut3" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_pengikut3" name="nama_pengikut3" 
                                       value="{{ old('nama_pengikut3') }}">
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin_pengikut3" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin_pengikut3" name="jenis_kelamin_pengikut3">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('jenis_kelamin_pengikut3') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin_pengikut3') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="hubungan_pengikut3" class="form-label">Hubungan</label>
                                <select class="form-select" id="hubungan_pengikut3" name="hubungan_pengikut3">
                                    <option value="">Pilih...</option>
                                    <option value="Istri" {{ old('hubungan_pengikut3') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                    <option value="Suami" {{ old('hubungan_pengikut3') == 'Suami' ? 'selected' : '' }}>Suami</option>
                                    <option value="Anak" {{ old('hubungan_pengikut3') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="Orang Tua" {{ old('hubungan_pengikut3') == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                    <option value="Saudara" {{ old('hubungan_pengikut3') == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Penting:</strong> Pastikan semua data yang dimasukkan sudah benar sebelum mengirim permohonan.
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('permohonan.index') }}" class="action-btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="action-btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Permohonan
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
function togglePengikutSections() {
    const jumlah = document.getElementById('jumlah_keluarga').value;
    
    // Hide all sections first
    document.getElementById('pengikut1').style.display = 'none';
    document.getElementById('pengikut2').style.display = 'none';
    document.getElementById('pengikut3').style.display = 'none';
    
    // Show sections based on selection
    if (jumlah >= 2) {
        document.getElementById('pengikut1').style.display = 'block';
    }
    if (jumlah >= 3) {
        document.getElementById('pengikut2').style.display = 'block';
    }
    if (jumlah >= 4) {
        document.getElementById('pengikut3').style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePengikutSections();
});
</script>
@endsection