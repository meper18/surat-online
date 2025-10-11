<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

/**
 * Improved PDF Service with High-Quality QR Codes
 * 
 * This service improves upon the current DomPDF implementation
 * with higher quality QR codes and better rendering options
 */
class ImprovedPDFService 
{
    /**
     * Generate PDF with high-quality QR code
     */
    public function generatePDF($html, $qrData = null, $options = []) 
    {
        try {
            // Replace QR code placeholder with high-quality version
            if ($qrData) {
                $html = $this->replaceQRCodeInTemplate($html, $qrData);
            }
            
            // Configure PDF options for better quality
            $pdf = Pdf::loadHTML($html);
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
            
            // Set DomPDF options for better rendering
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 150,
                'defaultPaperSize' => 'A4',
                'chroot' => public_path(),
            ]);

            return $pdf->output();
            
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage());
            throw new \Exception('Gagal generate PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate high-quality QR code using SimpleSoftwareIO QrCode
     */
    public function generateHighQualityQRCode($data, $size = 200) 
    {
        try {
            // Generate QR code as PNG with high quality
            $qrCode = QrCode::format('png')
                ->size($size)
                ->margin(1)
                ->errorCorrection('M')
                ->generate($data);

            // Convert to base64
            $base64 = 'data:image/png;base64,' . base64_encode($qrCode);
            
            return '<img src="' . $base64 . '" style="width: 100px; height: 100px; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;" alt="QR Code" class="qr-code">';
            
        } catch (\Exception $e) {
            Log::error('QR Code Generation Error: ' . $e->getMessage());
            return '<div class="qr-code-error">QR Code tidak dapat dibuat</div>';
        }
    }
    
    /**
     * Replace existing QR code in HTML template
     */
    public function replaceQRCodeInTemplate($html, $qrData) 
    {
        try {
            // Generate high-quality QR code
            $highQualityQR = $this->generateHighQualityQRCode($qrData);
            
            // Replace various QR code patterns that might exist in templates
            $patterns = [
                '/\{\{\s*\$qrCodeImage\s*\}\}/',
                '/\{\{\s*qrCodeImage\s*\}\}/',
                '/\{\{QR_CODE\}\}/',
                '/<img[^>]*src="data:image\/png;base64,[^"]*"[^>]*style="[^"]*width:\s*100px[^"]*"[^>]*>/i',
                '/<img[^>]*src="data:image\/png;base64,[^"]*"[^>]*style="[^"]*width:\s*200px[^"]*"[^>]*>/i',
                '/<img[^>]*class="qr-code"[^>]*>/',
                '/<div[^>]*class="qr-code"[^>]*>.*?<\/div>/s'
            ];
            
            foreach ($patterns as $pattern) {
                $html = preg_replace($pattern, $highQualityQR, $html);
            }
            
            return $html;
            
        } catch (\Exception $e) {
            Log::error('QR Code Template Replacement Error: ' . $e->getMessage());
            return $html; // Return original HTML if replacement fails
        }
    }

    /**
     * Generate QR code for signature verification
     * Compatible with existing PermohonanController methods
     */
    public function generateQRCodeForSignature($permohonanId, $signatureData) 
    {
        $verificationUrl = url("/verify/{$permohonanId}");
        return $this->generateHighQualityQRCode($verificationUrl);
    }
}