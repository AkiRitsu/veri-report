@extends('layouts.app')

@section('title', 'All Reports')

@section('styles')
<style>
    .reports-action-btn {
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
    
    .ongoing-actions-grid,
    .complete-actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        width: fit-content;
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<h1 style="margin-bottom: 1.5rem;">All Reports</h1>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
        <a href="{{ route('reports.create') }}" class="btn btn-primary">Create New Report</a>
        
        <!-- Filter Buttons -->
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <a href="{{ route('reports.index', request()->except(['status', 'page'])) }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">All</a>
            <a href="{{ route('reports.index', array_merge(request()->except(['status', 'page']), ['status' => 'on-going'])) }}" class="btn {{ request('status') == 'on-going' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">On-going</a>
        </div>
        
        <!-- Search Form -->
        <form method="GET" action="{{ route('reports.index') }}" style="display: flex; gap: 0.5rem; align-items: center;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by client, device, model, serial..." style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 0.25rem; min-width: 250px;">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('sort_by'))
                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
            @endif
            <button type="submit" class="btn btn-secondary" style="display: inline-flex; align-items: center; justify-content: center; text-align: center;">Search</button>
            @if(request('search'))
                <a href="{{ route('reports.index', request()->except(['search', 'page'])) }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; justify-content: center; text-align: center;">Clear</a>
            @endif
        </form>
    </div>

    @if($reports->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <a href="{{ route('reports.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'id', 'sort_order' => (request('sort_by') == 'id' && request('sort_order') == 'desc') ? 'asc' : ((request('sort_by') == 'id' && request('sort_order') == 'asc') ? 'desc' : 'asc')])) }}" style="text-decoration: none; color: inherit;">
                            ID
                            @if(!request('sort_by') || request('sort_by') == 'id')
                                {{ (request('sort_by') == 'id' && request('sort_order') == 'asc') ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th>Client Name</th>
                    <th>Device Type</th>
                    <th>Model</th>
                    <th>Serial ID</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">
                        <a href="{{ route('reports.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'created_at', 'sort_order' => request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            Created At
                            @if(request('sort_by') == 'created_at')
                                {{ request('sort_order') == 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th style="text-align: center;">
                        <a href="{{ route('reports.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'updated_at', 'sort_order' => request('sort_by') == 'updated_at' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            Last Updated
                            @if(request('sort_by') == 'updated_at')
                                {{ request('sort_order') == 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
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
                    <td>{{ $report->device_serial_id }}</td>
                    <td style="text-align: center;">
                        <span class="badge {{ $report->status === 'complete' ? 'badge-success' : 'badge-warning' }}">
                            {{ $report->status === 'complete' ? 'Complete' : 'On-going' }}
                        </span>
                    </td>
                    <td style="text-align: center;">{{ $report->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i') }}</td>
                    <td style="text-align: center;">{{ $report->updated_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i') }}</td>
                    <td style="text-align: center;">
                        @if($report->status === 'on-going')
                            <div class="ongoing-actions-grid">
                                <form method="POST" action="{{ route('reports.destroy', $report) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger reports-action-btn">Delete</button>
                                </form>
                                <a href="{{ route('reports.show', ['report' => $report, 'from' => 'all-reports']) }}" class="btn btn-secondary reports-action-btn">View</a>
                                <a href="{{ route('reports.edit', $report) }}" class="btn btn-primary reports-action-btn">Edit</a>
                            <form method="POST" action="{{ route('reports.complete', $report) }}" style="display: inline;">
                                @csrf
                                    <button type="submit" class="btn btn-success reports-action-btn">Complete</button>
                                </form>
                            </div>
                        @else
                            <div class="complete-actions-grid">
                                <form method="POST" action="{{ route('reports.destroy', $report) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger reports-action-btn">Delete</button>
                            </form>
                                <a href="{{ route('reports.show', ['report' => $report, 'from' => 'all-reports']) }}" class="btn btn-secondary reports-action-btn">View</a>
                                <a href="{{ route('reports.export', $report) }}" class="btn btn-primary reports-action-btn">Export PDF</a>
                        <form method="POST" action="{{ route('reports.send-email', $report) }}" style="display: inline;">
                            @csrf
                                    <button type="submit" class="btn btn-success reports-action-btn">Email</button>
                        </form>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 1rem;">
            {{ $reports->links() }}
        </div>
    @else
        <p>No reports found. <a href="{{ route('reports.create') }}">Create your first report</a></p>
    @endif
</div>
@endsection

