<?php

namespace App\Services;

use App\Models\Report;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

class PdfService
{
    /**
     * Generate PDF for a report.
     * Note: Hash is NOT included in the PDF to ensure consistent hash calculation.
     * 
     * @param Report $report
     * @return string
     */
    public function generatePdf(Report $report): string
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        // Make PDF generation deterministic by disabling metadata
        $options->set('isPhpEnabled', false);
        $options->set('isJavascriptEnabled', false);

        $dompdf = new Dompdf($options);

        $html = View::make('reports.pdf', [
            'report' => $report
        ])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Set fixed metadata to ensure deterministic output
        // Use the report's completed_at or created_at as the document date
        $docDate = $report->completed_at ?? $report->created_at;
        $canvas = $dompdf->getCanvas();
        if ($canvas && method_exists($canvas, 'get_cpdf')) {
            $cpdf = $canvas->get_cpdf();
            if ($cpdf && method_exists($cpdf, 'setInfo')) {
                $dateString = "D:{$docDate->format('YmdHis')}+08'00'";
                $cpdf->setInfo('CreationDate', $dateString);
                $cpdf->setInfo('ModDate', $dateString);
            }
        }

        return $dompdf->output();
    }

    /**
     * Generate PDF and calculate hash.
     * Since hash is not included in PDF, this is straightforward and consistent.
     * 
     * @param Report $report
     * @return array ['pdf' => string, 'hash' => string]
     */
    public function generatePdfWithHash(Report $report): array
    {
        // Generate PDF without hash (ensures consistent content)
        $pdf = $this->generatePdf($report);
        
        // Calculate hash from the PDF
        $hash = $this->generateHash($pdf);
        
        return ['pdf' => $pdf, 'hash' => $hash];
    }

    /**
     * Generate hash from report content data (canonical representation).
     * This creates a deterministic hash that represents the report data.
     * 
     * @param Report $report
     * @return string
     */
    public function generateContentHash(Report $report): string
    {
        // Create a canonical representation of the report data
        // Include all fields that should be in the PDF
        $data = [
            'id' => $report->id,
            'client_name' => $report->client_name,
            'client_email' => $report->client_email,
            'phone_number' => $report->phone_number,
            'device_type' => $report->device_type,
            'model_name' => $report->model_name,
            'device_serial_id' => $report->device_serial_id,
            'problem_description' => $report->problem_description,
            'fix_description' => $report->fix_description ?? '',
            'additional_notes' => $report->additional_notes ?? '',
            'status' => $report->status,
            'created_at' => $report->created_at->toIso8601String(),
            'completed_at' => $report->completed_at ? $report->completed_at->toIso8601String() : null,
            'created_by' => $report->user->name,
        ];

        // Create JSON with sorted keys for consistency
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_SORT_KEYS);
        
        return hash('sha256', $json);
    }

    /**
     * Generate hash for PDF binary content (legacy method, kept for compatibility).
     */
    public function generateHash(string $pdfContent): string
    {
        return hash('sha256', $pdfContent);
    }

    /**
     * Verify PDF hash using content-based verification.
     * 
     * @param Report $report The report to verify
     * @param string $storedHash The stored hash to compare against
     * @return bool
     */
    public function verifyContentHash(Report $report, string $storedHash): bool
    {
        $calculatedHash = $this->generateContentHash($report);
        return hash_equals($storedHash, $calculatedHash);
    }

    /**
     * Verify PDF hash from binary (legacy method).
     */
    public function verifyHash(string $pdfContent, string $storedHash): bool
    {
        $calculatedHash = $this->generateHash($pdfContent);
        return hash_equals($storedHash, $calculatedHash);
    }
}

