<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perubahan Status Permohonan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            margin: 5px;
        }
        .status-diajukan { background-color: #ffc107; color: #212529; }
        .status-diverifikasi { background-color: #17a2b8; color: white; }
        .status-ditandatangani { background-color: #007bff; color: white; }
        .status-selesai { background-color: #28a745; color: white; }
        .status-ditolak { background-color: #dc3545; color: white; }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        .status-change {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .arrow {
            font-size: 24px;
            color: #007bff;
            margin: 0 10px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sistem Layanan Surat Online</h1>
            <p>Pemberitahuan Perubahan Status Permohonan</p>
        </div>

        <p>Yth. {{ $permohonan->user->name }},</p>
        
        <p>Kami informasikan bahwa status permohonan surat Anda telah mengalami perubahan:</p>

        <table class="info-table">
            <tr>
                <td>Nomor Permohonan</td>
                <td>#{{ str_pad($permohonan->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Jenis Surat</td>
                <td>{{ $permohonan->jenisSurat->nama }}</td>
            </tr>
            <tr>
                <td>Tanggal Permohonan</td>
                <td>{{ $permohonan->created_at->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Keperluan</td>
                <td>{{ $permohonan->keperluan }}</td>
            </tr>
        </table>

        <div class="status-change">
            <h3>Perubahan Status</h3>
            <div style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap;">
                <span class="status-badge status-{{ $oldStatus }}">
                    @switch($oldStatus)
                        @case('diajukan') Diajukan @break
                        @case('diverifikasi') Diverifikasi @break
                        @case('ditandatangani') Ditandatangani @break
                        @case('selesai') Selesai @break
                        @case('ditolak') Ditolak @break
                    @endswitch
                </span>
                <span class="arrow">→</span>
                <span class="status-badge status-{{ $newStatus }}">
                    @switch($newStatus)
                        @case('diajukan') Diajukan @break
                        @case('diverifikasi') Diverifikasi @break
                        @case('ditandatangani') Ditandatangani @break
                        @case('selesai') Selesai @break
                        @case('ditolak') Ditolak @break
                    @endswitch
                </span>
            </div>
        </div>

        @if($permohonan->keterangan_status)
            <div style="background-color: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <strong>Keterangan:</strong><br>
                {{ $permohonan->keterangan_status }}
            </div>
        @endif

        @if($newStatus == 'selesai')
            <div style="background-color: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0; text-align: center;">
                <h4 style="color: #155724; margin-top: 0;">🎉 Permohonan Anda Telah Selesai!</h4>
                <p style="color: #155724;">Surat Anda telah siap dan dapat diunduh melalui sistem.</p>
                <a href="{{ config('app.url') }}" class="btn">Akses Sistem</a>
            </div>
        @elseif($newStatus == 'ditolak')
            <div style="background-color: #f8d7da; padding: 20px; border-radius: 5px; margin: 20px 0; text-align: center;">
                <h4 style="color: #721c24; margin-top: 0;">❌ Permohonan Ditolak</h4>
                <p style="color: #721c24;">Mohon periksa keterangan di atas dan ajukan permohonan baru jika diperlukan.</p>
            </div>
        @else
            <div style="text-align: center; margin: 20px 0;">
                <p>Untuk melihat detail lengkap permohonan Anda, silakan akses sistem:</p>
                <a href="{{ config('app.url') }}" class="btn">Akses Sistem</a>
            </div>
        @endif

        <div class="footer">
            <p><strong>Sistem Layanan Surat Online Kelurahan</strong></p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p>Jika Anda memiliki pertanyaan, silakan hubungi kantor kelurahan.</p>
        </div>
    </div>
</body>
</html>