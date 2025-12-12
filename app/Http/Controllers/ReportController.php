<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\PdfService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $pdfService;
    protected $emailService;

    /**
     * Create a new controller instance.
     */
    public function __construct(PdfService $pdfService, EmailService $emailService)
    {
        // No middleware needed here - route already has check.auth middleware
        $this->pdfService = $pdfService;
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of all reports.
     */
    public function index(Request $request)
    {
        $query = Report::query();

        // Filter by role: technicians only see their own reports, admin sees all
        if (Auth::guard('web')->check()) {
            $query->where('user_id', Auth::guard('web')->id());
        }

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

        // Filter by role: technicians only see their own reports, admin sees all
        if (Auth::guard('web')->check()) {
            $query->where('user_id', Auth::guard('web')->id());
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
        $technicians = null;
        if (Auth::guard('admin')->check()) {
            $technicians = \App\Models\User::orderBy('name')->get();
        }
        return view('reports.create', compact('technicians'));
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
            'user_id' => Auth::guard('admin')->check() ? 'required|exists:users,id' : 'nullable',
        ]);

        // If admin, use assigned user_id; if technician, use their own id
        $validated['user_id'] = Auth::guard('admin')->check() 
            ? $validated['user_id'] 
            : Auth::guard('web')->id();
        $validated['status'] = 'on-going';

        Report::create($validated);

        return redirect()->route('reports.index')->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        // Technicians can only view their own reports
        if (Auth::guard('web')->check() && $report->user_id !== Auth::guard('web')->id()) {
            abort(403, 'Unauthorized access.');
        }
        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified report.
     */
    public function edit(Report $report)
    {
        // Technicians can only edit their own reports
        if (Auth::guard('web')->check() && $report->user_id !== Auth::guard('web')->id()) {
            abort(403, 'Unauthorized access.');
        }
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

        // Redirect back to technician reports page if coming from there
        if ($request->get('from') === 'technician-reports' && $request->get('user_id')) {
            return redirect()->route('admin.technicians.reports', $request->get('user_id'))->with('success', 'Report updated successfully.');
        }

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
     * Note: Hash is calculated from the actual PDF being sent, but NOT stored in database.
     * Database hash is only updated when exporting PDF locally.
     */
    public function sendEmail(Report $report)
    {
        try {
            // Generate PDF for email
            $pdf = $this->pdfService->generatePdf($report);
            
            // Calculate hash from the actual PDF being sent (don't store in database)
            $hash = $this->pdfService->generateHash($pdf);

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
        // Technicians can only delete their own reports
        if (Auth::guard('web')->check() && $report->user_id !== Auth::guard('web')->id()) {
            abort(403, 'Unauthorized access.');
        }
        $report->delete();
        return redirect()->back()->with('success', 'Report deleted successfully.');
    }
}

