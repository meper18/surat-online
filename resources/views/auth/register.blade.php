@extends('layouts.app')

@section('title', 'Register - Sistem Layanan Surat Online Kelurahan')

@push('styles')
<link href="{{ asset('css/mobile-first.css') }}" rel="stylesheet">
<style>
    /* Mobile-first responsive design for registration page */
    .register-container {
        padding: 1rem;
        max-width: 100%;
    }
    
    .register-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden;
    }
    
    .register-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 1.5rem 1rem;
        text-align: center;
        border: none;
    }
    
    .register-header h4 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .register-body {
        padding: 1.5rem 1rem;
    }
    
    .form-section {
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        color: #007bff;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .form-row {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        padding: 0.75rem;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #fff;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
    }
    
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
    }
    
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .register-btn {
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .register-btn:hover {
        background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    
    .register-footer {
        background-color: #f8f9fa;
        padding: 1rem;
        text-align: center;
        border-top: 1px solid #e9ecef;
    }
    
    .register-footer p {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .register-footer a {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }
    
    .register-footer a:hover {
        text-decoration: underline;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    /* Tablet styles */
    @media (min-width: 768px) {
        .register-container {
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .register-header {
            padding: 2rem;
        }
        
        .register-header h4 {
            font-size: 1.5rem;
        }
        
        .register-body {
            padding: 2rem;
        }
        
        .form-row {
            flex-direction: row;
            gap: 1.5rem;
        }
        
        .form-group {
            flex: 1;
        }
        
        .section-title {
            font-size: 1.1rem;
        }
    }
    
    /* Desktop styles */
    @media (min-width: 992px) {
        .register-container {
            max-width: 700px;
            padding: 3rem 2rem;
        }
        
        .register-header h4 {
            font-size: 1.75rem;
        }
        
        .register-body {
            padding: 2.5rem;
        }
        
        .form-row {
            gap: 2rem;
        }
        
        .section-title {
            font-size: 1.2rem;
        }
        
        .register-btn {
            max-width: 300px;
            margin: 0 auto;
            display: block;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h4><i class="fas fa-user-plus me-2"></i>Daftar Akun Baru</h4>
            </div>
            <div class="register-body">
                <form method="POST" action="{{ route('register.process') }}">
                    @csrf

                    <div class="form-section">
                        <h5 class="section-title">Informasi Akun</h5>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                       placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
                                       placeholder="contoh@email.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password"
                                       placeholder="Minimal 8 karakter">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password-confirm" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input id="password-confirm" type="password" class="form-control" 
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="Ulangi password">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5 class="section-title">Data Pribadi</h5>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                                <input id="nik" type="text" class="form-control @error('nik') is-invalid @enderror" 
                                       name="nik" value="{{ old('nik') }}" required maxlength="16"
                                       placeholder="16 digit NIK">
                                @error('nik')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="no_hp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                <input id="no_hp" type="text" class="form-control @error('no_hp') is-invalid @enderror" 
                                       name="no_hp" value="{{ old('no_hp') }}" required
                                       placeholder="08xxxxxxxxxx">
                                @error('no_hp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input id="tempat_lahir" type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                       name="tempat_lahir" value="{{ old('tempat_lahir') }}" required
                                       placeholder="Kota tempat lahir">
                                @error('tempat_lahir')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input id="tanggal_lahir" type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                       name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                @error('tanggal_lahir')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                                <select id="agama" class="form-select @error('agama') is-invalid @enderror" name="agama" required>
                                    <option value="" selected disabled>Pilih Agama</option>
                                    <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                </select>
                                @error('agama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="pekerjaan" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                <input id="pekerjaan" type="text" class="form-control @error('pekerjaan') is-invalid @enderror" 
                                       name="pekerjaan" value="{{ old('pekerjaan') }}" required
                                       placeholder="Jenis pekerjaan">
                                @error('pekerjaan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="lingkungan" class="form-label">Lingkungan <span class="text-danger">*</span></label>
                                <select id="lingkungan" class="form-select @error('lingkungan') is-invalid @enderror" name="lingkungan" required>
                                    <option value="" selected disabled>Pilih Lingkungan</option>
                                    <option value="1" {{ old('lingkungan') == '1' ? 'selected' : '' }}>Lingkungan I</option>
                                    <option value="2" {{ old('lingkungan') == '2' ? 'selected' : '' }}>Lingkungan II</option>
                                    <option value="3" {{ old('lingkungan') == '3' ? 'selected' : '' }}>Lingkungan III</option>
                                </select>
                                @error('lingkungan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea id="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                    name="alamat" rows="3" required 
                                    placeholder="Masukkan alamat lengkap...">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="register-btn" id="registerBtn">
                        <span class="btn-text">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </span>
                    </button>
                </form>
            </div>
            <div class="register-footer">
                <p>Sudah memiliki akun? <a href="{{ route('login') }}">Login disini</a></p>
            </div>
        </div>
    </div>
</div>
@endsection