<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Penghasilan</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 16pt;
            margin: 5px 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }
        .content {
            margin: 30px 0;
        }
        .nomor-surat {
            text-align: center;
            margin: 20px 0;
            text-decoration: underline;
            font-weight: bold;
        }
        .isi-surat {
            text-align: justify;
            margin: 20px 0;
        }
        .data-table {
            margin: 20px 0;
            width: 100%;
        }
        .data-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .ttd {
            margin-top: 50px;
            float: right;
            width: 300px;
            text-align: center;
        }
        .ttd-space {
            height: 80px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pemerintah Desa {{ $desa['nama_desa'] }}</h1>
        <h2>Kecamatan {{ $desa['nama_kecamatan'] }}</h2>
        <h2>Kabupaten {{ $desa['nama_kabupaten'] }}</h2>
        <p>Alamat: {{ $desa['alamat_lengkap'] }}</p>
        <p>Telepon: {{ $desa['nomor_telepon'] }} | Email: {{ $desa['email_desa'] }}</p>
    </div>

    <div class="content">
        <div class="nomor-surat">
            <p>SURAT KETERANGAN PENGHASILAN</p>
            <p>Nomor: {{ $permohonan->nomor_surat ?? 'Belum ada nomor' }}</p>
        </div>

        <div class="isi-surat">
            <p>Yang bertanda tangan di bawah ini, Kepala Desa {{ $desa['nama_desa'] }}, Kecamatan {{ $desa['nama_kecamatan'] }}, Kabupaten {{ $desa['nama_kabupaten'] }}, dengan ini menerangkan bahwa:</p>

            <table class="data-table">
                <tr>
                    <td width="150">Nama</td>
                    <td width="20">:</td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $user->nik }}</td>
                </tr>
                <tr>
                    <td>Tempat/Tgl Lahir</td>
                    <td>:</td>
                    <td>{{ $user->tempat_lahir }}, {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{ $user->agama }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ $user->pekerjaan }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $user->alamat }}</td>
                </tr>
                @if($suratPenghasilan)
                <tr>
                    <td>Penghasilan per Bulan</td>
                    <td>:</td>
                    <td>Rp {{ number_format($suratPenghasilan->penghasilan_per_bulan, 0, ',', '.') }}</td>
                </tr>
                @endif
            </table>

            <p>Adalah benar warga kami yang berdomisili di wilayah Desa {{ $desa['nama_desa'] }} dan memiliki penghasilan sebesar <strong>Rp {{ number_format($suratPenghasilan->jumlah_penghasilan, 0, ',', '.') }}</strong> per bulan dari pekerjaan sebagai {{ $user->pekerjaan }}.</p>

            <p>Surat keterangan ini dibuat untuk keperluan: <strong>{{ $permohonan->keperluan }}</strong></p>

            <p>Demikian surat keterangan ini dibuat dengan sebenarnya dan dapat dipergunakan sebagaimana mestinya.</p>
        </div>

        <div class="ttd">
            <p>{{ $desa['nama_desa'] }}, {{ $tanggal_surat }}</p>
            <p>Kepala Desa {{ $desa['nama_desa'] }}</p>
            
            {{-- Digital Signature or QR Code --}}
            @if($permohonan->signed_at)
                <div class="signature-section" style="margin: 20px 0;">
                    @if($permohonan->signature_type === 'digital' && $permohonan->digital_signature)
                        <div class="digital-signature" style="text-align: center;">
                            <img src="data:image/png;base64,{{ $permohonan->digital_signature }}" 
                                 alt="Digital Signature" 
                                 style="max-width: 200px; max-height: 100px; border: 1px solid #ccc;">
                            <p style="font-size: 10pt; margin-top: 5px;">
                                <em>Ditandatangani secara digital pada {{ $permohonan->signed_at->format('d F Y H:i:s') }}</em>
                            </p>
                        </div>
                    @elseif($permohonan->signature_type === 'qr_code' && $permohonan->qr_code_image)
                        <div class="qr-signature" style="text-align: center;">
                            <img src="{{ $permohonan->qr_code_image }}" 
                                 alt="QR Code Verification" 
                                 style="width: 200px; height: 200px;">
                            <p style="font-size: 10pt; margin-top: 5px;">
                                <em>Scan QR Code untuk verifikasi</em><br>
                                <em>Ditandatangani pada {{ $permohonan->signed_at->format('d F Y H:i:s') }}</em>
                            </p>
                        </div>
                    @endif
                </div>
            @else
                <div class="ttd-space"></div>
            @endif
            
            <p><strong>{{ $desa['nama_kepala_desa'] }}</strong></p>
            <p>NIP: {{ $desa['nip_kepala_desa'] }}</p>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>