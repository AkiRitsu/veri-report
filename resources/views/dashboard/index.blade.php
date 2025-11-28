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
</style>
@endsection

@section('content')
<h1 style="margin-bottom: 1.5rem;">Dashboard</h1>

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
@endsection

