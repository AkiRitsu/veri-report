@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<style>
    .dashboard-action-btn {
        width: 95px;
        height: 32px;
        padding: 0;
        font-size: 0.75rem;
        text-align: center;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 2rem;
    }
    .period-selector {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .period-btn {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
        background: var(--bg-secondary);
        color: var(--text-primary);
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .period-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    .period-btn:hover {
        background: var(--primary-light);
        color: white;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<h1 style="margin-bottom: 1.5rem;">Dashboard</h1>

<!-- Completed Reports Chart -->
<div class="card" style="margin-bottom: 2rem;">
    <h2 style="margin-bottom: 1rem;">My Completed Reports</h2>
    <div class="period-selector">
        <a href="{{ route('dashboard', ['period' => 'daily']) }}" class="period-btn {{ $period === 'daily' ? 'active' : '' }}">Daily</a>
        <a href="{{ route('dashboard', ['period' => 'weekly']) }}" class="period-btn {{ $period === 'weekly' ? 'active' : '' }}">Weekly</a>
        <a href="{{ route('dashboard', ['period' => 'monthly']) }}" class="period-btn {{ $period === 'monthly' ? 'active' : '' }}">Monthly</a>
    </div>
    <div class="chart-container">
        <canvas id="completedReportsChart"></canvas>
    </div>
</div>

<!-- Recent Reports -->
<div class="card">
    <h2 style="margin-bottom: 1rem;">Recent Reports</h2>
    
    @if($recentReports->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client Name</th>
                    <th>Device Type</th>
                    <th>Model</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Created At</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentReports as $report)
                <tr>
                    <td>{{ $report->id }}</td>
                    <td>{{ $report->client_name }}</td>
                    <td>{{ $report->device_type }}</td>
                    <td>{{ $report->model_name }}</td>
                    <td style="text-align: center;">
                        <span class="badge {{ $report->status === 'complete' ? 'badge-success' : 'badge-warning' }}">
                            {{ $report->status === 'complete' ? 'Complete' : 'On-going' }}
                        </span>
                    </td>
                    <td style="text-align: center;">{{ $report->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i') }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; align-items: center; justify-content: center;">
                            @if($report->status === 'on-going')
                                <form method="POST" action="{{ route('reports.destroy', $report) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger dashboard-action-btn">Delete</button>
                                </form>
                                <a href="{{ route('reports.show', ['report' => $report, 'from' => 'dashboard']) }}" class="btn btn-secondary dashboard-action-btn">View</a>
                                <a href="{{ route('reports.edit', $report) }}" class="btn btn-primary dashboard-action-btn">Edit</a>
                                <form method="POST" action="{{ route('reports.complete', $report) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success dashboard-action-btn">Complete</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('reports.destroy', $report) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger dashboard-action-btn">Delete</button>
                                </form>
                                <a href="{{ route('reports.show', ['report' => $report, 'from' => 'dashboard']) }}" class="btn btn-secondary dashboard-action-btn">View</a>
                                <a href="{{ route('reports.export', $report) }}" class="btn btn-primary dashboard-action-btn">Export PDF</a>
                                <form method="POST" action="{{ route('reports.send-email', $report) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success dashboard-action-btn">Email</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No reports yet. <a href="{{ route('reports.create') }}">Create your first report</a></p>
    @endif
</div>

<script>
    const ctx = document.getElementById('completedReportsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Completed Reports',
                data: @json($chartData['data']),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection

