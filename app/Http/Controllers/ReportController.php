<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\PdfService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $pdfService;
    protected $emailService;

    /**
     * Create a new controller instance.
     */
    public function __construct(PdfService $pdfService, EmailService $emailService)
    {
        $this->middleware('auth');
        $this->pdfService = $pdfService;
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of all reports.
     */
    public function index(Request $request)
    {
        $query = Report::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('device_type', 'like', "%{$search}%")
                  ->orWhere('model_name', 'like', "%{$search}%")
                  ->orWhere('device_serial_id', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'id') {
            $query->orderBy('id', $sortOrder);
        } elseif ($sortBy === 'created_at') {
            $query->orderBy('created_at', $sortOrder);
        } elseif ($sortBy === 'updated_at') {
            $query->orderBy('updated_at', $sortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }

        $reports = $query->paginate(15)->withQueryString();
        return view('reports.index', compact('reports'));
    }

    /**
     * Display a listing of completed reports.
     */
    public function completed(Request $request)
    {
        $query = Report::where('status', 'complete');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('device_type', 'like', "%{$search}%")
                  ->orWhere('model_name', 'like', "%{$search}%")
                  ->orWhere('device_serial_id', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'completed_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'id') {
            $query->orderBy('id', $sortOrder);
        } elseif ($sortBy === 'created_at') {
            $query->orderBy('created_at', $sortOrder);
        } elseif ($sortBy === 'completed_at') {
            $query->orderBy('completed_at', $sortOrder);
        } else {
            $query->orderBy('completed_at', 'desc'); // Default to completed_at descending
        }

        $reports = $query->paginate(15)->withQueryString();
        return view('reports.completed', compact('reports'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created report.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'model_name' => 'required|string|max:255',
            'device_serial_id' => 'required|string|max:255',
            'device_type' => 'required|in:PC,Laptop,Mobile Phone',
            'problem_description' => 'required|string',
            'fix_description' => 'nullable|string',
            'phone_number' => 'required|string|max:20',
            'client_email' => 'required|email|max:255',
            'additional_notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'on-going';

        Report::create($validated);

        return redirect()->route('reports.index')->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified report.
     */
    public function edit(Report $report)
    {
        return view('reports.edit', compact('report'));
    }

    /**
     * Update the specified report.
     */
    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'model_name' => 'required|string|max:255',
            'device_serial_id' => 'required|string|max:255',
            'device_type' => 'required|in:PC,Laptop,Mobile Phone',
            'problem_description' => 'required|string',
            'fix_description' => 'nullable|string',
            'phone_number' => 'required|string|max:20',
            'client_email' => 'required|email|max:255',
            'additional_notes' => 'nullable|string',
        ]);

        $report->update($validated);

        // Note: Hash will be recalculated when PDF is exported or emailed
        // We don't calculate it here to ensure it matches the actual exported PDF

        return redirect()->route('reports.index')->with('success', 'Report updated successfully.');
    }

    /**
     * Mark the report as complete.
     * Note: Hash is NOT calculated here - it will be calculated when PDF is exported or emailed.
     */
    public function complete(Report $report)
    {
        $report->markAsComplete();

        return redirect()->back()->with('success', 'Report marked as complete.');
    }

    /**
     * Export the report as PDF.
     * This is where the hash is calculated and stored - ensuring it matches the exported PDF.
     */
    public function exportPdf(Report $report)
    {
        try {
            // Generate PDF and calculate hash from the ACTUAL exported PDF
            // This ensures the hash matches exactly what the user downloads
            $result = $this->pdfService->generatePdfWithHash($report);
            $pdf = $result['pdf'];
            $hash = $result['hash'];
            
            // Update the stored hash when exporting (ensures it matches the exported PDF)
            // Only update if hash doesn't exist or if it's different (to avoid unnecessary updates)
            // Use DB::table() to update without touching updated_at timestamp
            if (!$report->pdf_hash || $report->pdf_hash !== $hash) {
                DB::table('reports')
                    ->where('id', $report->id)
                    ->update(['pdf_hash' => $hash]);
                $report->refresh(); // Refresh to get updated hash
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate PDF on export: ' . $e->getMessage());
            // Fallback: generate PDF
            $pdf = $this->pdfService->generatePdf($report);
            $hash = $this->pdfService->generateHash($pdf);
            // Only update if hash doesn't exist (don't overwrite with potentially incorrect hash)
            // Use DB::table() to update without touching updated_at timestamp
            if (!$report->pdf_hash) {
                DB::table('reports')
                    ->where('id', $report->id)
                    ->update(['pdf_hash' => $hash]);
                $report->refresh(); // Refresh to get updated hash
            }
        }

        $filename = 'report_' . $report->id . '_' . now()->format('Y-m-d') . '.pdf';

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Send the report via email.
     * Note: Hash is NOT updated here - it is only updated when exporting PDF.
     * We use the existing stored hash (from export) if available, otherwise calculate one for email display only.
     */
    public function sendEmail(Report $report)
    {
        try {
            // Generate PDF for email
            $pdf = $this->pdfService->generatePdf($report);
            
            // Use existing stored hash if available (from PDF export)
            // Otherwise, calculate hash for email display only (but don't store it)
            if ($report->pdf_hash) {
                $hash = $report->pdf_hash; // Use stored hash from export
            } else {
                // No hash exists yet, calculate one for email display only
                $hash = $this->pdfService->generateHash($pdf);
            }

            $this->emailService->sendReportEmail($report, $pdf, $hash);

            return redirect()->back()->with('success', 'Report sent to client email successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified report.
     */
    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->back()->with('success', 'Report deleted successfully.');
    }
}

