@extends('layouts.app')

@section('title', 'Detail Permohonan Surat')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Permohonan Surat</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Nomor Permohonan:</strong></div>
                    <div class="col-sm-9">#{{ str_pad($permohonan->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Tanggal Permohonan:</strong></div>
                    <div class="col-sm-9">{{ $permohonan->created_at->format('d F Y, H:i') }} WIB</div>
                </div>

                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('operator'))
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Pemohon:</strong></div>
                        <div class="col-sm-9">
                            {{ $permohonan->user->name }}<br>
                            <small class="text-muted">{{ $permohonan->user->email }}</small><br>
                            <small class="text-muted">NIK: {{ $permohonan->user->nik }}</small>
                        </div>
                    </div>
                @endif

                {{-- Navigation to Detail Form for Incomplete Data --}}
                @if(Auth::user()->hasRole('warga') && !$permohonan->hasCompleteDetailData() && $permohonan->getDetailFormRoute())
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Data Detail Belum Lengkap</h6>
                                <p class="mb-2">Permohonan {{ $permohonan->jenisSurat->nama }} Anda memerlukan data detail tambahan untuk dapat diproses.</p>
                                <a href="{{ route($permohonan->getDetailFormRoute(), $permohonan) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>Lengkapi Data Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Jenis Surat:</strong></div>
                    <div class="col-sm-9">
                        {{ $permohonan->jenisSurat->nama }} ({{ $permohonan->jenisSurat->kode }})
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Keperluan:</strong></div>
                    <div class="col-sm-9">{{ $permohonan->keperluan }}</div>
                </div>

                @if($permohonan->catatan)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Catatan:</strong></div>
                        <div class="col-sm-9">{{ $permohonan->catatan }}</div>
                    </div>
                @endif

                {{-- Display Required Documents --}}
                @if($requiredDocuments && $requiredDocuments->count() > 0)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Dokumen Wajib:</strong></div>
                        <div class="col-sm-9">
                            <div class="row">
                                @foreach($requiredDocuments as $dokumen)
                                    <div class="col-md-6 mb-2">
                                        <div class="card border-light">
                                            <div class="card-body p-2">
                                                <h6 class="card-title mb-1 text-primary">
                                                    <i class="fas fa-file-alt me-1"></i>
                                                    {{ $dokumen->jenis_dokumen_name }}
                                                </h6>
                                                <p class="card-text small text-muted mb-2">
                                                    {{ $dokumen->nama_file }}<br>
                                                    <small>{{ number_format($dokumen->file_size / 1024, 1) }} KB</small>
                                                </p>
                                                <div class="d-flex gap-1">
                                                    @if(Auth::user()->hasRole('admin'))
                                                        <a href="{{ route('admin.permohonan.downloadDocument', [$permohonan, $dokumen->id]) }}" 
                                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                    @elseif(Auth::user()->hasRole('operator'))
                                                        <a href="{{ route('operator.permohonan.downloadDocument', [$permohonan, $dokumen->id]) }}" 
                                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                    @else
                                                        <a href="{{ route('warga.permohonan.downloadDocument', [$permohonan, $dokumen->id]) }}" 
                                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                    @endif
                                                    @if(in_array($dokumen->mime_type, ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf']))
                                                        @php
                                                            $downloadUrl = Auth::user()->hasRole('admin') 
                                                                ? route('admin.permohonan.downloadDocument', [$permohonan, $dokumen->id])
                                                                : (Auth::user()->hasRole('operator') 
                                                                    ? route('operator.permohonan.downloadDocument', [$permohonan, $dokumen->id])
                                                                    : route('warga.permohonan.downloadDocument', [$permohonan, $dokumen->id]));
                                                        @endphp
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-success" 
                                                                onclick="showDocumentModal('{{ Storage::url($dokumen->file_path) }}', '{{ addslashes($dokumen->jenis_dokumen_name) }}', '{{ $downloadUrl }}', '{{ $dokumen->mime_type }}')">
                                                            <i class="fas fa-eye me-1"></i>Lihat
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Display Additional Documents --}}
                @if($additionalDocuments && $additionalDocuments->count() > 0)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Dokumen Tambahan:</strong></div>
                        <div class="col-sm-9">
                            <div class="row">
                                @foreach($additionalDocuments as $dokumen)
                                    <div class="col-md-6 mb-2">
                                        <div class="card border-info">
                                            <div class="card-body p-2">
                                                <h6 class="card-title mb-1 text-info">
                                                    <i class="fas fa-file-plus me-1"></i>
                                                    {{ $dokumen->jenis_dokumen_name }}
                                                </h6>
                                                <p class="card-text small text-muted mb-2">
                                                    {{ $dokumen->nama_file }}<br>
                                                    <small>{{ number_format($dokumen->file_size / 1024, 1) }} KB</small>
                                                </p>
                                                <div class="d-flex gap-1">
                                                    @if(Auth::user()->hasRole('admin'))
                                                        <a href="{{ route('admin.permohonan.downloadDocument', [$permohonan, $dokumen->id]) }}" 
                                                           class="btn btn-sm btn-outline-info" target="_blank">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                    @elseif(Auth::user()->hasRole('operator'))
                                                        <a href="{{ route('operator.permohonan.downloadDocument', [$permohonan, $dokumen->id]) }}" 
                                                           class="btn btn-sm btn-outline-info" target="_blank">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                    @else
                                                        <a href="{{ route('warga.permohonan.downloadDocument', [$permohonan, $dokumen->id]) }}" 
                                                           class="btn btn-sm btn-outline-info" target="_blank">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                    @endif
                                                    @if(in_array($dokumen->mime_type, ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf']))
                                                        @php
                                                            $downloadUrl = Auth::user()->hasRole('admin') 
                                                                ? route('admin.permohonan.downloadDocument', [$permohonan, $dokumen->id])
                                                                : (Auth::user()->hasRole('operator') 
                                                                    ? route('operator.permohonan.downloadDocument', [$permohonan, $dokumen->id])
                                                                    : route('warga.permohonan.downloadDocument', [$permohonan, $dokumen->id]));
                                                        @endphp
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-success" 
                                                                onclick="showDocumentModal('{{ Storage::url($dokumen->file_path) }}', '{{ addslashes($dokumen->jenis_dokumen_name) }}', '{{ $downloadUrl }}', '{{ $dokumen->mime_type }}')">
                                                            <i class="fas fa-eye me-1"></i>Lihat
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Legacy single document support --}}
                @if($permohonan->dokumen_pendukung)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Dokumen Pendukung (Lama):</strong></div>
                        <div class="col-sm-9">
                            <a href="{{ Storage::url($permohonan->dokumen_pendukung) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file me-1"></i>Lihat Dokumen
                            </a>
                        </div>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Status:</strong></div>
                    <div class="col-sm-9">
                        @if($permohonan->status == 'diajukan')
                            <span class="badge bg-warning fs-6">Menunggu Verifikasi</span>
                        @elseif($permohonan->status == 'diverifikasi')
                            <span class="badge bg-info fs-6">Sedang Diverifikasi</span>
                        @elseif($permohonan->status == 'ditandatangani')
                            <span class="badge bg-primary fs-6">Sedang Ditandatangani</span>
                        @elseif($permohonan->status == 'selesai')
                            <span class="badge bg-success fs-6">Selesai</span>
                        @elseif($permohonan->status == 'ditolak')
                            <span class="badge bg-danger fs-6">Ditolak</span>
                        @endif
                    </div>
                </div>

                @if($permohonan->keterangan_status)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Keterangan:</strong></div>
                        <div class="col-sm-9">
                            <div class="alert alert-info mb-0">
                                {{ $permohonan->keterangan_status }}
                            </div>
                        </div>
                    </div>
                @endif

                @if($permohonan->status == 'selesai' && $permohonan->file_surat)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>File Surat:</strong></div>
                        <div class="col-sm-9">
                            @if(Auth::user()->hasRole('admin'))
                                <a href="{{ route('admin.permohonan.download', $permohonan) }}" class="btn btn-success">
                                    <i class="fas fa-download me-1"></i>Download Surat
                                </a>
                            @elseif(Auth::user()->hasRole('operator'))
                                <a href="{{ route('operator.permohonan.download', $permohonan) }}" class="btn btn-success">
                                    <i class="fas fa-download me-1"></i>Download Surat
                                </a>
                            @else
                                <a href="{{ route('warga.permohonan.download', $permohonan) }}" class="btn btn-success">
                                    <i class="fas fa-download me-1"></i>Download Surat
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- PDF Generation Buttons for Admin/Operator --}}
                @if((Auth::user()->hasRole('admin') || Auth::user()->hasRole('operator')) && $permohonan->status == 'ditandatangani')
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Generate PDF:</strong></div>
                        <div class="col-sm-9">
                            @switch($permohonan->jenisSurat->nama)
                                @case('Surat Keterangan Penghasilan')
                                    <a href="{{ route('admin.surat-penghasilan.pdf', $permohonan) }}" class="btn btn-primary">
                                        <i class="fas fa-file-pdf me-1"></i>Generate PDF Surat Penghasilan
                                    </a>
                                    @break
                                @case('Surat Keterangan Pindah/Mandah')
                                    <a href="{{ route('admin.surat-mandah.pdf', $permohonan) }}" class="btn btn-primary">
                                        <i class="fas fa-file-pdf me-1"></i>Generate PDF Surat Pindah
                                    </a>
                                    @break
                                @case('Surat Keterangan Kematian')
                                    <a href="{{ route('admin.surat-kematian.pdf', $permohonan) }}" class="btn btn-primary">
                                        <i class="fas fa-file-pdf me-1"></i>Generate PDF Surat Kematian
                                    </a>
                                    @break
                                @case('Surat Keterangan Nikah')
                                @case('Surat Keterangan Belum Menikah')
                                    <a href="{{ route('admin.surat-nikah.pdf', $permohonan) }}" class="btn btn-primary">
                                        <i class="fas fa-file-pdf me-1"></i>Generate PDF Surat Nikah
                                    </a>
                                    @break
                                @case('Surat Keterangan Domisili Tinggal')
                                    <a href="{{ route('admin.surat-domisili-tinggal.pdf', $permohonan) }}" class="btn btn-primary">
                                        <i class="fas fa-file-pdf me-1"></i>Generate PDF Domisili Tinggal
                                    </a>
                                    @break
                                @case('Surat Keterangan Domisili Usaha')
                                    <a href="{{ route('admin.surat-domisili-usaha.pdf', $permohonan) }}" class="btn btn-primary">
                                        <i class="fas fa-file-pdf me-1"></i>Generate PDF Domisili Usaha
                                    </a>
                                    @break
                                @default
                                    <span class="text-muted">Template PDF belum tersedia untuk jenis surat ini</span>
                            @endswitch
                        </div>
                    </div>
                @endif

                {{-- Digital Signature Options for Admin/Operator --}}
                @if((Auth::user()->hasRole('admin') || Auth::user()->hasRole('operator')) && $permohonan->status == 'ditandatangani' && !$permohonan->signed_at)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Tandatangan Digital:</strong></div>
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Pilih Jenis Tandatangan</h6>
                                    <form id="signatureForm" method="POST" action="{{ route(auth()->user()->hasRole('admin') ? 'admin.permohonan.addSignature' : 'operator.permohonan.addSignature', $permohonan) }}">
                                        @csrf
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="signature_type" id="digital_signature" value="digital" checked onchange="toggleSignatureSection()">
                                                <label class="form-check-label" for="digital_signature">
                                                    <i class="fas fa-signature me-2"></i>Tandatangan Digital
                                                </label>
                                                <small class="form-text text-muted d-block">Menggunakan tanda tangan digital dengan canvas</small>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="signature_type" id="qr_code" value="qr_code" onchange="toggleSignatureSection()">
                                                <label class="form-check-label" for="qr_code">
                                                    <i class="fas fa-qrcode me-2"></i>QR Code
                                                </label>
                                                <small class="form-text text-muted d-block">Menggunakan QR Code untuk verifikasi</small>
                                            </div>
                                        </div>
                                        
                                        {{-- Digital Signature Canvas --}}
                                        <div id="digitalSignatureSection" class="signature-section">
                                            <div class="mb-3">
                                                <div class="form-label">Buat Tandatangan Digital:</div>
                                                <div class="border rounded p-3 bg-light">
                                                    <canvas id="signatureCanvas" width="400" height="200" style="border: 1px solid #ccc; cursor: crosshair;" tabindex="0" role="img" aria-label="Area untuk membuat tandatangan digital"></canvas>
                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature()">
                                                            <i class="fas fa-eraser me-1"></i>Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="digital_signature" id="digitalSignatureData">
                                            </div>
                                        </div>

                                        {{-- QR Code Section --}}
                                        <div id="qrCodeSection" class="signature-section" style="display: none;">
                                            <div class="mb-3">
                                                <div class="form-label">QR Code akan berisi informasi:</div>
                                                <ul class="list-unstyled" id="qrCodeInfo">
                                                    <li><i class="fas fa-check text-success me-2"></i>Nomor Permohonan: {{ $permohonan->kode_permohonan }}</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>Jenis Surat: {{ $permohonan->jenisSurat->nama }}</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>Tanggal Ditandatangani</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>Nama Penandatangan: {{ Auth::user()->name }}</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-1"></i>Tandatangani Surat
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="toggleSignatureSection()">
                                                <i class="fas fa-eye me-1"></i>Preview
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Display Signature Information if Already Signed --}}
                @if($permohonan->signed_at)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Informasi Tandatangan:</strong></div>
                        <div class="col-sm-9">
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Surat telah ditandatangani</strong>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">Jenis Tandatangan:</small><br>
                                            @if($permohonan->signature_type == 'digital')
                                                <span class="badge bg-primary"><i class="fas fa-signature me-1"></i>Tandatangan Digital</span>
                                            @else
                                                <span class="badge bg-info"><i class="fas fa-qrcode me-1"></i>QR Code</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Ditandatangani pada:</small><br>
                                            {{ $permohonan->signed_at->format('d/m/Y H:i:s') }}
                                        </div>
                                    </div>
                                    @if($permohonan->signature_type == 'qr_code' && $permohonan->qr_code_image)
                                        <div class="mt-3">
                                            <small class="text-muted">QR Code:</small><br>
                                            <img src="{{ $permohonan->qr_code_image }}" alt="QR Code" style="width: 200px; height: 200px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="d-flex justify-content-between">
                    @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.permohonan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    @elseif(Auth::user()->hasRole('operator'))
                        <a href="{{ route('operator.permohonan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    @else
                        <a href="{{ route('warga.permohonan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('operator'))
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Kelola Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ Auth::user()->hasRole('admin') ? route('admin.permohonan.updateStatus', $permohonan) : route('operator.permohonan.updateStatus', $permohonan) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="status" class="form-label">Status Permohonan</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="diajukan" {{ $permohonan->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="diverifikasi" {{ $permohonan->status == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                <option value="ditandatangani" {{ $permohonan->status == 'ditandatangani' ? 'selected' : '' }}>Ditandatangani</option>
                                <option value="selesai" {{ $permohonan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ $permohonan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan_status" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan_status" name="keterangan_status" rows="3" placeholder="Berikan keterangan jika diperlukan...">{{ $permohonan->keterangan_status }}</textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="updateStatusBtn">
                                <span class="btn-text">
                                    <i class="fas fa-save me-1"></i>Update Status
                                </span>
                            </button>
                        </div>
                    </form>

                    @if(Auth::user()->hasRole('admin') && $permohonan->status != 'selesai')
                        <hr>
                        <div class="d-grid">
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash me-1"></i>Hapus Permohonan
                            </button>
                        </div>

                        <form id="delete-form" action="{{ route('admin.permohonan.destroy', $permohonan) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="documentContent">
                    <!-- Document content will be loaded here -->
                </div>
                <div id="documentLoading" class="d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat dokumen...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="downloadDocumentBtn" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download me-1"></i>Download
                </a>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus permohonan ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('delete-form').submit();
        }
    }

    function showDocumentModal(documentUrl, documentName, downloadUrl, mimeType) {
        const modal = new bootstrap.Modal(document.getElementById('documentModal'));
        const modalTitle = document.getElementById('documentModalLabel');
        const documentContent = document.getElementById('documentContent');
        const documentLoading = document.getElementById('documentLoading');
        const downloadBtn = document.getElementById('downloadDocumentBtn');
        
        // Set modal title
        modalTitle.textContent = documentName;
        
        // Set download button
        downloadBtn.href = downloadUrl;
        
        // Show loading
        documentLoading.classList.remove('d-none');
        documentContent.innerHTML = '';
        
        // Show modal
        modal.show();
        
        // Debug logging
        console.log('Document URL:', documentUrl);
        console.log('MIME Type:', mimeType);
        
        // Load document content based on mime type
        if (mimeType && (mimeType.includes('image/') || mimeType === 'image/jpeg' || mimeType === 'image/jpg' || mimeType === 'image/png')) {
            // For images
            const img = new Image();
            img.onload = function() {
                documentLoading.classList.add('d-none');
                documentContent.innerHTML = `<img src="${documentUrl}" class="img-fluid" alt="${documentName}" style="max-height: 70vh;">`;
            };
            img.onerror = function() {
                console.error('Image load error for:', documentUrl);
                documentLoading.classList.add('d-none');
                documentContent.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Tidak dapat memuat gambar. Silakan download untuk melihat dokumen.
                        <br><small>URL: ${documentUrl}</small>
                    </div>
                `;
            };
            img.src = documentUrl;
        } else if (mimeType && mimeType === 'application/pdf') {
            // For PDFs - Add error handling for iframe
            documentLoading.classList.add('d-none');
            
            // Create iframe with better error handling
            const iframe = document.createElement('iframe');
            iframe.src = documentUrl;
            iframe.style.width = '100%';
            iframe.style.height = '70vh';
            iframe.style.border = 'none';
            iframe.title = documentName;
            
            // Handle iframe load errors
            iframe.onload = function() {
                console.log('PDF loaded successfully');
            };
            
            iframe.onerror = function() {
                console.error('PDF load error for:', documentUrl);
                documentContent.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Tidak dapat memuat PDF. Silakan download untuk melihat dokumen.
                        <br><small>URL: ${documentUrl}</small>
                        <br><a href="${downloadUrl}" class="btn btn-sm btn-primary mt-2" target="_blank">
                            <i class="fas fa-download me-1"></i>Download Dokumen
                        </a>
                    </div>
                `;
            };
            
            // Add fallback content
            iframe.innerHTML = `
                <p>Browser Anda tidak mendukung tampilan PDF. 
                   <a href="${downloadUrl}" target="_blank">Klik di sini untuk download</a>
                </p>
            `;
            
            documentContent.appendChild(iframe);
            
            // Additional check for PDF accessibility
            setTimeout(function() {
                try {
                    // Test if iframe content is accessible
                    if (iframe.contentDocument === null) {
                        console.warn('PDF may not be loading properly');
                    }
                } catch (e) {
                    console.log('Cross-origin iframe - this is normal for PDF files');
                }
            }, 1000);
            
        } else {
            // For other file types
            documentLoading.classList.add('d-none');
            documentContent.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-file me-2"></i>
                    <h6>Dokumen: ${documentName}</h6>
                    <p class="mb-0">Tipe file ini tidak dapat ditampilkan di browser. Silakan download untuk melihat dokumen.</p>
                    <br><a href="${downloadUrl}" class="btn btn-sm btn-primary mt-2" target="_blank">
                        <i class="fas fa-download me-1"></i>Download Dokumen
                    </a>
                </div>
            `;
        }
    }

    // ===== CANVAS SIGNATURE FUNCTIONALITY =====
    
    // Global variables for canvas
    let globalCanvas = null;
    let globalCtx = null;
    let isDrawing = false;

    // Clear signature function - available globally
    function clearSignature() {
        console.log('Clearing signature...');
        if (globalCtx && globalCanvas) {
            globalCtx.fillStyle = 'white';
            globalCtx.fillRect(0, 0, globalCanvas.width, globalCanvas.height);
            globalCtx.strokeStyle = '#000';
            console.log('Signature cleared successfully');
        } else {
            console.error('Canvas or context not available for clearing');
        }
    }

    // Toggle signature section function - available globally
    function toggleSignatureSection() {
        const digitalSection = document.getElementById('digitalSignatureSection');
        const qrSection = document.getElementById('qrCodeSection');
        const checkedRadio = document.querySelector('input[name="signature_type"]:checked');
        
        if (!digitalSection || !qrSection) {
            console.warn('Signature sections not found - elements may not be present on this page');
            return;
        }
        
        // Hide both sections first
        digitalSection.style.display = 'none';
        qrSection.style.display = 'none';
        
        // Show the appropriate section
        if (checkedRadio && checkedRadio.value === 'digital') {
            digitalSection.style.display = 'block';
            // Initialize canvas after showing the section
            setTimeout(initializeCanvas, 100);
        } else if (checkedRadio && checkedRadio.value === 'qr_code') {
            qrSection.style.display = 'block';
        }
    }

    // Simple canvas initialization
     function initializeCanvas() {
         console.log('Initializing canvas...');
         
         // Cari canvas yang asli
         const originalCanvas = document.getElementById('signatureCanvas');
         if (!originalCanvas) {
             console.error('Original canvas not found!');
             return;
         }
         
         // Clone canvas untuk memastikan fresh start
         const newCanvas = originalCanvas.cloneNode(true);
         originalCanvas.parentNode.replaceChild(newCanvas, originalCanvas);
         
         // Set canvas size
         newCanvas.width = 600;
         newCanvas.height = 200;
         
         // Update referensi global
         globalCanvas = newCanvas;
         globalCtx = globalCanvas.getContext('2d');
         
         // Validasi context
         if (!globalCtx) {
             console.error('Failed to get canvas context!');
             return;
         }
         
         // Set canvas background to white
         globalCtx.fillStyle = 'white';
         globalCtx.fillRect(0, 0, globalCanvas.width, globalCanvas.height);
         
         // Set canvas properties
         globalCtx.strokeStyle = '#000';
         globalCtx.lineWidth = 2;
         globalCtx.lineCap = 'round';
         globalCtx.lineJoin = 'round';
         
         // Test canvas dengan menggambar titik kecil
         globalCtx.fillStyle = 'red';
         globalCtx.fillRect(0, 0, 2, 2);
         console.log('Canvas test dot drawn');
         
         // Clear test dot setelah 1 detik
         setTimeout(() => {
             globalCtx.fillStyle = 'white';
             globalCtx.fillRect(0, 0, 2, 2);
         }, 1000);
         
         // Add fresh event listeners
         globalCanvas.addEventListener('mousedown', startDrawing);
         globalCanvas.addEventListener('mousemove', draw);
         globalCanvas.addEventListener('mouseup', stopDrawing);
         globalCanvas.addEventListener('mouseout', stopDrawing);
         
         // Touch events for mobile
         globalCanvas.addEventListener('touchstart', handleTouch);
         globalCanvas.addEventListener('touchmove', handleTouch);
         globalCanvas.addEventListener('touchend', stopDrawing);
         
         console.log('Canvas initialized successfully');
         console.log('Canvas dimensions:', globalCanvas.width, 'x', globalCanvas.height);
         console.log('Canvas context:', !!globalCtx);
     }

    function startDrawing(e) {
         isDrawing = true;
         console.log('Starting drawing - Context available:', !!globalCtx);
         
         if (!globalCtx || !globalCanvas) {
             console.error('Canvas or context not available for drawing!');
             return;
         }
         
         const rect = globalCanvas.getBoundingClientRect();
         const x = e.clientX - rect.left;
         const y = e.clientY - rect.top;
         
         globalCtx.beginPath();
         globalCtx.moveTo(x, y);
         console.log('Started drawing at:', x, y);
     }

     function draw(e) {
         if (!isDrawing) return;
         
         if (!globalCtx || !globalCanvas) {
             console.error('Canvas or context not available for drawing!');
             return;
         }
         
         const rect = globalCanvas.getBoundingClientRect();
         const x = e.clientX - rect.left;
         const y = e.clientY - rect.top;
         
         globalCtx.lineTo(x, y);
         globalCtx.stroke();
         console.log('Drawing to:', x, y);
     }

    function stopDrawing() {
        isDrawing = false;
        console.log('Stopped drawing');
    }

    function handleTouch(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                        e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        globalCanvas.dispatchEvent(mouseEvent);
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, setting up canvas...');
        
        // Check if signature elements exist on this page
        const digitalSection = document.getElementById('digitalSignatureSection');
        const qrSection = document.getElementById('qrCodeSection');
        
        if (!digitalSection || !qrSection) {
            console.log('Signature sections not found - this page may not have signature functionality');
            return;
        }
        
        // Setup radio button event listeners
        const signatureRadios = document.querySelectorAll('input[name="signature_type"]');
        signatureRadios.forEach(radio => {
            radio.addEventListener('change', toggleSignatureSection);
        });
        
        // Initial toggle
        toggleSignatureSection();

        // Form submission handling
        const signatureForm = document.getElementById('signatureForm');
        if (signatureForm) {
            signatureForm.addEventListener('submit', function(e) {
                console.log('Form submission started...');
                const checkedRadio = document.querySelector('input[name="signature_type"]:checked');
                console.log('Checked radio value:', checkedRadio ? checkedRadio.value : 'none');
                
                if (checkedRadio && checkedRadio.value === 'digital') {
                    if (!globalCanvas || !globalCtx) {
                        e.preventDefault();
                        alert('Canvas tidak tersedia. Silakan refresh halaman!');
                        return;
                    }
                    
                    const signatureData = globalCanvas.toDataURL();
                    console.log('Signature data length:', signatureData.length);
                    console.log('Signature data preview:', signatureData.substring(0, 100) + '...');
                    
                    // Create blank canvas for comparison
                    const blankCanvas = document.createElement('canvas');
                    blankCanvas.width = globalCanvas.width;
                    blankCanvas.height = globalCanvas.height;
                    const blankCtx = blankCanvas.getContext('2d');
                    blankCtx.fillStyle = 'white';
                    blankCtx.fillRect(0, 0, blankCanvas.width, blankCanvas.height);
                    
                    if (signatureData === blankCanvas.toDataURL()) {
                        e.preventDefault();
                        alert('Silakan buat tanda tangan terlebih dahulu!');
                        return;
                    }
                    
                    // Set signature data
                    const hiddenInput = document.getElementById('digitalSignatureData');
                    if (hiddenInput) {
                        hiddenInput.value = signatureData;
                        console.log('Hidden input value set successfully');
                        console.log('Hidden input name:', hiddenInput.name);
                        console.log('Hidden input value length:', hiddenInput.value.length);
                        console.log('Hidden input element:', hiddenInput);
                        
                        // Force update the input value
                        hiddenInput.setAttribute('value', signatureData);
                        
                        // Additional verification
                        console.log('Form data before submission:');
                        const formData = new FormData(signatureForm);
                        let foundDigitalSignature = false;
                        for (let [key, value] of formData.entries()) {
                            if (key === 'digital_signature') {
                                foundDigitalSignature = true;
                                console.log(`${key}: ${value.substring(0, 100)}... (length: ${value.length})`);
                            } else {
                                console.log(`${key}: ${value}`);
                            }
                        }
                        
                        if (!foundDigitalSignature) {
                            console.error('CRITICAL: digital_signature field not found in FormData!');
                            console.log('All form fields:', Array.from(formData.keys()));
                            
                            // Try manual append
                            formData.append('digital_signature', signatureData);
                            console.log('Manually appended digital_signature to FormData');
                        }
                    } else {
                        console.error('Hidden input digitalSignatureData not found!');
                        e.preventDefault();
                        alert('Error: Hidden input tidak ditemukan!');
                        return;
                    }
                }
                
                console.log('Form submission proceeding...');
            });
        }
    });
</script>
@endsection