<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Kematian</title>
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
        <h1>Pemerintah Desa [Nama Desa]</h1>
        <h2>Kecamatan [Nama Kecamatan]</h2>
        <h2>Kabupaten [Nama Kabupaten]</h2>
        <p>Alamat: [Alamat Lengkap Kantor Desa]</p>
        <p>Telepon: [Nomor Telepon] | Email: [Email Desa]</p>
    </div>

    <div class="content">
        <div class="nomor-surat">
            <p>SURAT KETERANGAN KEMATIAN</p>
            <p>Nomor: {{ $permohonan->nomor_surat ?? 'Belum ada nomor' }}</p>
        </div>

        <div class="isi-surat">
            <p>Yang bertanda tangan di bawah ini, Kepala Desa [Nama Desa], Kecamatan [Nama Kecamatan], Kabupaten [Nama Kabupaten], dengan ini menerangkan bahwa:</p>

            @if($suratKematian)
            <table class="data-table">
                <tr>
                    <td width="150">Nama Almarhum/ah</td>
                    <td width="20">:</td>
                    <td>{{ $suratKematian->nama_almarhum }}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $suratKematian->nik_almarhum }}</td>
                </tr>
                <tr>
                    <td>Tempat/Tgl Lahir</td>
                    <td>:</td>
                    <td>{{ $suratKematian->tempat_lahir }}, {{ \Carbon\Carbon::parse($suratKematian->tanggal_lahir)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $suratKematian->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{ $suratKematian->agama }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $suratKematian->alamat }}</td>
                </tr>
                <tr>
                    <td>Tanggal Meninggal</td>
                    <td>:</td>
                    <td>{{ \Carbon\Carbon::parse($suratKematian->tanggal_meninggal)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Tempat Meninggal</td>
                    <td>:</td>
                    <td>{{ $suratKematian->tempat_meninggal }}</td>
                </tr>
                <tr>
                    <td>Sebab Kematian</td>
                    <td>:</td>
                    <td>{{ $suratKematian->sebab_kematian }}</td>
                </tr>
            </table>
            @endif

            <p>Adalah benar telah meninggal dunia pada tanggal, tempat, dan sebab sebagaimana tersebut di atas.</p>

            <p>Surat keterangan ini dibuat untuk keperluan: <strong>{{ $permohonan->keperluan }}</strong></p>

            <p>Demikian surat keterangan ini dibuat dengan sebenarnya dan dapat dipergunakan sebagaimana mestinya.</p>
        </div>

        <div class="ttd">
            <p>[Nama Desa], {{ $tanggal_surat }}</p>
            <p>Kepala Desa [Nama Desa]</p>
            
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
            
            <p><strong>[Nama Kepala Desa]</strong></p>
            <p>NIP: [NIP Kepala Desa]</p>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>