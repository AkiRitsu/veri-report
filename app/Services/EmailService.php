<?php

namespace App\Services;

use App\Models\Report;
use Mailjet\Client;
use Mailjet\Resources;

class EmailService
{
    protected $mailjet;

    public function __construct()
    {
        $this->mailjet = new Client(
            config('services.mailjet.api_key'),
            config('services.mailjet.api_secret'),
            true,
            ['version' => 'v3.1']
        );
    }

    /**
     * Send report email to client.
     */
    public function sendReportEmail(Report $report, string $pdfContent, string $hash)
    {
        $pdfBase64 = base64_encode($pdfContent);
        $filename = 'report_' . $report->id . '_' . now()->format('Y-m-d') . '.pdf';

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => config('services.mailjet.from_email'),
                        'Name' => config('services.mailjet.from_name'),
                    ],
                    'To' => [
                        [
                            'Email' => $report->client_email,
                            'Name' => $report->client_name,
                        ],
                    ],
                    'Subject' => 'Troubleshooting Report - ' . $report->device_type . ' (' . $report->model_name . ')',
                    'TextPart' => "Dear {$report->client_name},\n\nPlease find attached your troubleshooting report.\n\nVerification Hash: {$hash}\n\nYou can use this hash to verify the integrity of the PDF document.",
                    'HTMLPart' => "<h3>Dear {$report->client_name},</h3><p>Please find attached your troubleshooting report.</p><p><strong>Verification Hash:</strong> {$hash}</p><p>You can use this hash to verify the integrity of the PDF document.</p>",
                    'Attachments' => [
                        [
                            'ContentType' => 'application/pdf',
                            'Filename' => $filename,
                            'Base64Content' => $pdfBase64,
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->mailjet->post(Resources::$Email, ['body' => $body]);

        if (!$response->success()) {
            throw new \Exception('Failed to send email via Mailjet: ' . json_encode($response->getData()));
        }

        return $response;
    }
}

