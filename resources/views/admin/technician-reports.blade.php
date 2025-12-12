@extends('layouts.app')

@section('title', 'Technician Reports')

@section('content')
<h1 style="margin-bottom: 1.5rem;">Reports for {{ $user->name }}</h1>

<div class="card">
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('admin.users.monitoring') }}" class="btn btn-secondary" style="margin-bottom: 1rem;">← Back to User Monitoring</a>
    </div>
    
    @if($reports->count() > 0)
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
                @foreach($reports as $report)
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
                            <a href="{{ route('reports.show', ['report' => $report, 'from' => 'technician-reports', 'user_id' => $user->id]) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem; min-width: 100px; text-align: center;">View</a>
                            @if($report->status === 'on-going')
                                <a href="{{ route('reports.edit', ['report' => $report, 'from' => 'technician-reports', 'user_id' => $user->id]) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem; min-width: 100px; text-align: center;">Edit</a>
                            @else
                                <a href="{{ route('reports.export', $report) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem; min-width: 100px; text-align: center;">Export PDF</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 1rem;">
            {{ $reports->links() }}
        </div>
    @else
        <p>No reports found for this technician.</p>
    @endif
</div>
@endsection

