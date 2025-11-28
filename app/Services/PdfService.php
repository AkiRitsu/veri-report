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
     */
    public function generatePdf(Report $report): string
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = View::make('reports.pdf', ['report' => $report])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Generate hash for PDF content.
     */
    public function generateHash(string $pdfContent): string
    {
        return hash('sha256', $pdfContent);
    }

    /**
     * Verify PDF hash.
     */
    public function verifyHash(string $pdfContent, string $storedHash): bool
    {
        $calculatedHash = $this->generateHash($pdfContent);
        return hash_equals($storedHash, $calculatedHash);
    }
}

