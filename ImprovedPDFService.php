<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Improved PDF Service with High-Quality QR Codes
 * 
 * This service improves upon the current DomPDF implementation
 * with higher quality QR codes and better rendering options
 */
class ImprovedPDFService {
    
    private $options;
    
    public function __construct() {
        $this->options = new Options();
        $this->options->set('defaultFont', 'Arial');
        $this->options->set('isRemoteEnabled', true);
        $this->options->set('isHtml5ParserEnabled', true);
        $this->options->set('dpi', 300); // Higher DPI for better quality
    }
    
    /**
     * Generate PDF with high-quality QR code
     */
    public function generatePDF($html, $qrData = null, $options = []) {
        try {
            // Replace QR code placeholder with high-quality version
            if ($qrData) {
                $highQualityQR = $this->generateHighQualityQRCode($qrData);
                $html = str_replace('{{QR_CODE}}', $highQualityQR, $html);
            }
            
            $dompdf = new Dompdf($this->options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            return $dompdf->output();
            
        } catch (\Exception $e) {
            throw new \Exception("PDF generation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Generate high-quality QR code (600px PNG)
     */
    public function generateHighQualityQRCode($data, $size = 600) {
        // Generate SVG first for maximum quality
        $renderer = new ImageRenderer(
            new RendererStyle($size, 1), // Smaller border for more QR content
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svgString = $writer->writeString($data);
        
        // Convert to high-resolution PNG
        $pngBase64 = $this->svgToPngBase64($svgString, $size);
        
        return '<img src="' . $pngBase64 . '" style="width: 200px; height: 200px; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">';
    }
    
    /**
     * Convert SVG to high-quality PNG
     */
    private function svgToPngBase64($svgString, $size) {
        // Parse SVG and create high-quality PNG
        $dom = new \DOMDocument();
        $dom->loadXML($svgString);
        
        $image = imagecreatetruecolor($size, $size);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        imagefill($image, 0, 0, $white);
        
        // Render SVG rectangles to PNG with high precision
        $rectangles = $dom->getElementsByTagName('rect');
        foreach ($rectangles as $rect) {
            $x = (int)$rect->getAttribute('x');
            $y = (int)$rect->getAttribute('y');
            $w = (int)$rect->getAttribute('width');
            $h = (int)$rect->getAttribute('height');
            $fill = $rect->getAttribute('fill');
            
            if ($fill === '#000000' || $fill === 'black') {
                imagefilledrectangle($image, $x, $y, $x + $w - 1, $y + $h - 1, $black);
            }
        }
        
        // Enable anti-aliasing for smoother edges
        imageantialias($image, true);
        
        ob_start();
        imagepng($image, null, 0); // Maximum quality PNG
        $pngData = ob_get_clean();
        imagedestroy($image);
        
        return 'data:image/png;base64,' . base64_encode($pngData);
    }
    
    /**
     * Generate test PDF with improved QR code
     */
    public function generateTestPDF($qrData) {
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Test Surat dengan QR Code Berkualitas Tinggi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin: 20px 0; }
        .signature { margin-top: 50px; text-align: center; }
        .qr-container { margin: 20px 0; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SURAT KETERANGAN</h2>
        <p>Nomor: 001/SK/2025</p>
    </div>
    
    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala Desa menerangkan bahwa:</p>
        <p>Nama: John Doe<br>
        NIK: 1234567890123456<br>
        Alamat: Jl. Contoh No. 123</p>
        
        <p>Adalah benar penduduk desa kami dan surat keterangan ini dibuat untuk keperluan administrasi.</p>
    </div>
    
    <div class="signature">
        <p>Kepala Desa</p>
        <div class="qr-container">
            {{QR_CODE}}
            <p><small>Scan QR Code untuk verifikasi<br>Ditandatangani pada ' . date('d F Y H:i:s') . '</small></p>
        </div>
        <p><strong>Nama Kepala Desa</strong></p>
    </div>
</body>
</html>';
        
        return $this->generatePDF($html, $qrData);
    }
}
