<?php

namespace App\Exports;

use App\Models\Permohonan;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportPdfExport
{
    protected $query;
    protected $filters;

    public function __construct($query, $filters = [])
    {
        $this->query = $query;
        $this->filters = $filters;
    }

    /**
     * Generate PDF content for report export
     */
    public function toPdf()
    {
        $data = $this->query->with(['user', 'jenisSurat'])->get();
        $statistics = $this->getStatistics();
        $filterInfo = $this->getFilterInfo();
        
        $html = $this->generateHtml($data, $statistics, $filterInfo);
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf;
    }

    /**
     * Generate HTML content for PDF
     */
    private function generateHtml($data, $statistics, $filterInfo): string
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Laporan Surat</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 10px;
                    margin: 20px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 10px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 18px;
                    color: #333;
                }
                .header h2 {
                    margin: 5px 0 0 0;
                    font-size: 14px;
                    color: #666;
                }
                .filter-info {
                    background-color: #f8f9fa;
                    padding: 10px;
                    margin-bottom: 15px;
                    border-left: 4px solid #007bff;
                }
                .filter-info h3 {
                    margin: 0 0 8px 0;
                    font-size: 12px;
                    color: #333;
                }
                .filter-info p {
                    margin: 2px 0;
                    font-size: 10px;
                }
                .statistics {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }
                .stat-card {
                    background-color: #f8f9fa;
                    padding: 8px;
                    border-radius: 4px;
                    text-align: center;
                    width: 15%;
                    border: 1px solid #dee2e6;
                }
                .stat-card h4 {
                    margin: 0;
                    font-size: 10px;
                    color: #666;
                }
                .stat-card .number {
                    font-size: 16px;
                    font-weight: bold;
                    color: #333;
                    margin: 5px 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 6px;
                    text-align: left;
                    font-size: 9px;
                }
                th {
                    background-color: #f8f9fa;
                    font-weight: bold;
                    color: #333;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                .status-badge {
                    padding: 2px 6px;
                    border-radius: 3px;
                    font-size: 8px;
                    font-weight: bold;
                    color: white;
                }
                .status-diajukan { background-color: #6c757d; }
                .status-diverifikasi { background-color: #ffc107; color: #000; }
                .status-diproses { background-color: #17a2b8; }
                .status-ditandatangani { background-color: #fd7e14; }
                .status-selesai { background-color: #28a745; }
                .status-ditolak { background-color: #dc3545; }
                .footer {
                    margin-top: 20px;
                    text-align: right;
                    font-size: 9px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN SURAT ONLINE</h1>
                <h2>Sistem Informasi Pelayanan Surat Desa</h2>
            </div>';

        // Filter Information
        if (!empty($filterInfo)) {
            $html .= '<div class="filter-info">
                <h3>Filter yang Diterapkan:</h3>';
            foreach ($filterInfo as $info) {
                $html .= '<p>• ' . $info . '</p>';
            }
            $html .= '</div>';
        }

        // Statistics
        $html .= '<div class="statistics">
            <div class="stat-card">
                <h4>Total</h4>
                <div class="number">' . $statistics['total'] . '</div>
            </div>
            <div class="stat-card">
                <h4>Diajukan</h4>
                <div class="number">' . $statistics['diajukan'] . '</div>
            </div>
            <div class="stat-card">
                <h4>Diproses</h4>
                <div class="number">' . $statistics['diproses'] . '</div>
            </div>
            <div class="stat-card">
                <h4>Selesai</h4>
                <div class="number">' . $statistics['selesai'] . '</div>
            </div>
            <div class="stat-card">
                <h4>Ditolak</h4>
                <div class="number">' . $statistics['ditolak'] . '</div>
            </div>
        </div>';

        // Data Table
        $html .= '<table>
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 8%;">Tanggal</th>
                    <th style="width: 10%;">Kode</th>
                    <th style="width: 15%;">Pemohon</th>
                    <th style="width: 15%;">Jenis Surat</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 20%;">Keperluan</th>
                    <th style="width: 10%;">No. Surat</th>
                    <th style="width: 11%;">Catatan</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($data as $index => $permohonan) {
            $statusClass = 'status-' . $permohonan->status;
            $statusLabel = $this->getStatusLabel($permohonan->status);
            
            $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . $permohonan->tanggal_permohonan->format('d/m/Y') . '</td>
                <td>' . $permohonan->kode_permohonan . '</td>
                <td>' . $permohonan->user->name . '</td>
                <td>' . $permohonan->jenisSurat->nama . '</td>
                <td><span class="status-badge ' . $statusClass . '">' . $statusLabel . '</span></td>
                <td>' . ($permohonan->keperluan ?? '-') . '</td>
                <td>' . ($permohonan->nomor_surat ?? '-') . '</td>
                <td>' . ($permohonan->catatan ?? '-') . '</td>
            </tr>';
        }

        $html .= '</tbody>
        </table>
        
        <div class="footer">
            <p>Dicetak pada: ' . date('d/m/Y H:i:s') . '</p>
            <p>Total data: ' . $data->count() . ' permohonan</p>
        </div>
        
        </body>
        </html>';

        return $html;
    }

    /**
     * Get statistics for the report
     */
    private function getStatistics(): array
    {
        $baseQuery = clone $this->query;
        
        return [
            'total' => $baseQuery->count(),
            'diajukan' => (clone $baseQuery)->where('status', 'diajukan')->count(),
            'diverifikasi' => (clone $baseQuery)->where('status', 'diverifikasi')->count(),
            'diproses' => (clone $baseQuery)->where('status', 'diproses')->count(),
            'ditandatangani' => (clone $baseQuery)->where('status', 'ditandatangani')->count(),
            'selesai' => (clone $baseQuery)->where('status', 'selesai')->count(),
            'ditolak' => (clone $baseQuery)->where('status', 'ditolak')->count(),
        ];
    }

    /**
     * Get status label in Indonesian
     */
    private function getStatusLabel(string $status): string
    {
        $statusLabels = [
            'diajukan' => 'Diajukan',
            'diverifikasi' => 'Diverifikasi',
            'diproses' => 'Diproses',
            'ditandatangani' => 'Ditandatangani',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak'
        ];

        return $statusLabels[$status] ?? ucfirst($status);
    }

    /**
     * Get filter information for the report
     */
    public function getFilterInfo(): array
    {
        $info = [];
        
        if (!empty($this->filters['jenis_surat_id'])) {
            $jenisSurat = \App\Models\JenisSurat::find($this->filters['jenis_surat_id']);
            $info[] = 'Jenis Surat: ' . ($jenisSurat ? $jenisSurat->nama : 'Tidak ditemukan');
        }
        
        if (!empty($this->filters['status'])) {
            $info[] = 'Status: ' . $this->getStatusLabel($this->filters['status']);
        }
        
        if (!empty($this->filters['tanggal_mulai'])) {
            $info[] = 'Tanggal Mulai: ' . date('d/m/Y', strtotime($this->filters['tanggal_mulai']));
        }
        
        if (!empty($this->filters['tanggal_selesai'])) {
            $info[] = 'Tanggal Selesai: ' . date('d/m/Y', strtotime($this->filters['tanggal_selesai']));
        }
        
        if (!empty($this->filters['search'])) {
            $info[] = 'Pencarian: ' . $this->filters['search'];
        }

        return $info;
    }

    /**
     * Generate filename for export
     */
    public function getFilename(): string
    {
        $date = date('Y-m-d_H-i-s');
        return "laporan_surat_{$date}.pdf";
    }
}