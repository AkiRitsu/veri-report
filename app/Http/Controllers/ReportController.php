<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\PdfService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        return redirect()->route('reports.index')->with('success', 'Report updated successfully.');
    }

    /**
     * Mark the report as complete.
     */
    public function complete(Report $report)
    {
        $report->markAsComplete();

        // Generate PDF hash immediately when report is completed
        if (!$report->pdf_hash) {
            try {
                $pdf = $this->pdfService->generatePdf($report);
                $hash = $this->pdfService->generateHash($pdf);
                $report->update(['pdf_hash' => $hash]);
                $report->refresh(); // Refresh to get updated hash
            } catch (\Exception $e) {
                // Log error but don't fail the completion
                \Log::error('Failed to generate PDF hash on completion: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Report marked as complete.');
    }

    /**
     * Export the report as PDF.
     */
    public function exportPdf(Report $report)
    {
        $pdf = $this->pdfService->generatePdf($report);
        $hash = $this->pdfService->generateHash($pdf);

        // Update report with hash if not already set
        if (!$report->pdf_hash) {
            $report->update(['pdf_hash' => $hash]);
        }

        $filename = 'report_' . $report->id . '_' . now()->format('Y-m-d') . '.pdf';

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Send the report via email.
     */
    public function sendEmail(Report $report)
    {
        try {
            $pdf = $this->pdfService->generatePdf($report);
            $hash = $this->pdfService->generateHash($pdf);

            // Update report with hash if not already set
            if (!$report->pdf_hash) {
                $report->update(['pdf_hash' => $hash]);
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

