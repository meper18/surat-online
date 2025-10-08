<?php

namespace App\Exports;

use App\Models\Permohonan;
use Illuminate\Support\Collection;

class ReportExport
{
    protected $query;
    protected $filters;

    public function __construct($query, $filters = [])
    {
        $this->query = $query;
        $this->filters = $filters;
    }

    /**
     * Generate CSV content for Excel export
     */
    public function toCsv(): string
    {
        $data = $this->query->with(['user', 'jenisSurat'])->get();
        
        // CSV Headers
        $headers = [
            'No',
            'Tanggal Permohonan',
            'Kode Permohonan',
            'Nama Pemohon',
            'Email Pemohon',
            'Jenis Surat',
            'Status',
            'Keperluan',
            'Nomor Surat',
            'Tanggal Surat',
            'Catatan'
        ];

        // Start CSV content
        $csvContent = $this->arrayToCsvLine($headers) . "\n";

        // Add data rows
        foreach ($data as $index => $permohonan) {
            $row = [
                $index + 1,
                $permohonan->tanggal_permohonan->format('d/m/Y'),
                $permohonan->kode_permohonan,
                $permohonan->user->name,
                $permohonan->user->email,
                $permohonan->jenisSurat->nama,
                $this->getStatusLabel($permohonan->status),
                $permohonan->keperluan,
                $permohonan->nomor_surat ?? '-',
                $permohonan->tanggal_surat ? $permohonan->tanggal_surat->format('d/m/Y') : '-',
                $permohonan->catatan ?? '-'
            ];

            $csvContent .= $this->arrayToCsvLine($row) . "\n";
        }

        return $csvContent;
    }

    /**
     * Convert array to CSV line
     */
    private function arrayToCsvLine(array $fields): string
    {
        $escapedFields = array_map(function ($field) {
            // Escape double quotes and wrap in quotes if necessary
            $field = str_replace('"', '""', $field);
            if (strpos($field, ',') !== false || strpos($field, '"') !== false || strpos($field, "\n") !== false) {
                return '"' . $field . '"';
            }
            return $field;
        }, $fields);

        return implode(',', $escapedFields);
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
        return "laporan_surat_{$date}.csv";
    }
}