<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::guard('admin')->check()) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Show the form for creating a new technician.
     */
    public function createTechnician()
    {
        return view('admin.create-technician');
    }

    /**
     * Store a newly created technician.
     */
    public function storeTechnician(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.monitoring')->with('success', 'Technician account created successfully.');
    }

    /**
     * Display user monitoring page.
     */
    public function userMonitoring(Request $request)
    {
        // All users are technicians now (admins are separate)
        $query = User::withCount(['reports as ongoing_reports_count' => function ($query) {
                $query->where('status', 'on-going');
            }])
            ->withCount(['reports as completed_reports_count' => function ($query) {
                $query->where('status', 'complete');
            }]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $technicians = $query->orderBy('name')->get();

        return view('admin.user-monitoring', compact('technicians'));
    }

    /**
     * Display all reports for a specific technician.
     */
    public function technicianReports(User $user)
    {
        $reports = Report::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.technician-reports', compact('user', 'reports'));
    }
}
