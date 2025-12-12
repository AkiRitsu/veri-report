<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // No middleware needed here - route already has check.auth middleware
    }

    /**
     * Show the dashboard.
     */
    public function index(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return $this->adminDashboard($request);
        } else {
            return $this->technicianDashboard($request);
        }
    }

    /**
     * Admin dashboard data.
     */
    private function adminDashboard(Request $request)
    {
        $period = $request->get('period', 'monthly'); // daily, weekly, monthly

        // Get completed reports data for chart
        $chartData = $this->getCompletedReportsChartData($period);

        // Get top 5 users with most ongoing reports (all users are technicians now)
        $topUsers = User::withCount(['reports as ongoing_count' => function ($query) {
                $query->where('status', 'on-going');
            }])
            ->orderBy('ongoing_count', 'desc')
            ->take(5)
            ->get();

        $recentReports = Report::latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact('chartData', 'topUsers', 'recentReports', 'period'));
    }

    /**
     * Technician dashboard data.
     */
    private function technicianDashboard(Request $request)
    {
        $userId = Auth::guard('web')->id();
        $period = $request->get('period', 'monthly'); // daily, weekly, monthly

        // Get completed reports data for chart
        $chartData = $this->getCompletedReportsChartDataForUser($userId, $period);

        $recentReports = Report::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.technician', compact('chartData', 'recentReports', 'period'));
    }

    /**
     * Get completed reports chart data for admin (all reports).
     */
    private function getCompletedReportsChartData($period)
    {
        $now = Carbon::now();
        $labels = [];
        $data = [];

        if ($period === 'daily') {
            // Last 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                $labels[] = $date->format('M d');
                $data[] = Report::where('status', 'complete')
                    ->whereDate('completed_at', $date->format('Y-m-d'))
                    ->count();
            }
        } elseif ($period === 'weekly') {
            // Last 12 weeks
            for ($i = 11; $i >= 0; $i--) {
                $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
                $weekEnd = $weekStart->copy()->endOfWeek();
                $labels[] = $weekStart->format('M d') . ' - ' . $weekEnd->format('M d');
                $data[] = Report::where('status', 'complete')
                    ->whereBetween('completed_at', [$weekStart, $weekEnd])
                    ->count();
            }
        } else { // monthly
            // Last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $month = $now->copy()->subMonths($i);
                $labels[] = $month->format('M Y');
                $data[] = Report::where('status', 'complete')
                    ->whereYear('completed_at', $month->year)
                    ->whereMonth('completed_at', $month->month)
                    ->count();
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get completed reports chart data for technician (their reports only).
     */
    private function getCompletedReportsChartDataForUser($userId, $period = 'monthly')
    {
        $now = Carbon::now();
        $labels = [];
        $data = [];

        if ($period === 'daily') {
            // Last 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                $labels[] = $date->format('M d');
                $data[] = Report::where('user_id', $userId)
                    ->where('status', 'complete')
                    ->whereDate('completed_at', $date->format('Y-m-d'))
                    ->count();
            }
        } elseif ($period === 'weekly') {
            // Last 12 weeks
            for ($i = 11; $i >= 0; $i--) {
                $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
                $weekEnd = $weekStart->copy()->endOfWeek();
                $labels[] = $weekStart->format('M d') . ' - ' . $weekEnd->format('M d');
                $data[] = Report::where('user_id', $userId)
                    ->where('status', 'complete')
                    ->whereBetween('completed_at', [$weekStart, $weekEnd])
                    ->count();
            }
        } else { // monthly
            // Last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $month = $now->copy()->subMonths($i);
                $labels[] = $month->format('M Y');
                $data[] = Report::where('user_id', $userId)
                    ->where('status', 'complete')
                    ->whereYear('completed_at', $month->year)
                    ->whereMonth('completed_at', $month->month)
                    ->count();
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}

