@extends('layouts.app')

@section('title', 'View Report')

@section('styles')
<style>
    .report-action-btn {
        width: 130px;
        height: 36px;
        padding: 0;
        font-size: 0.875rem;
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
<h1 style="margin-bottom: 1.5rem;">Report #{{ $report->id }}</h1>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <span class="badge {{ $report->status === 'complete' ? 'badge-success' : 'badge-warning' }}">
                Status: {{ ucfirst($report->status) }}
            </span>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            @if($report->status === 'on-going')
                <form method="POST" action="{{ route('reports.destroy', $report) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger report-action-btn">Delete</button>
                </form>
                <a href="{{ route('reports.edit', $report) }}" class="btn btn-primary report-action-btn">Edit</a>
                <form method="POST" action="{{ route('reports.complete', $report) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success report-action-btn">Mark as Complete</button>
                </form>
            @else
                <form method="POST" action="{{ route('reports.destroy', $report) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger report-action-btn">Delete</button>
                </form>
                <a href="{{ route('reports.export', $report) }}" class="btn btn-primary report-action-btn">Export PDF</a>
                <form method="POST" action="{{ route('reports.send-email', $report) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success report-action-btn">Send Email</button>
                </form>
            @endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div>
            <h3 style="margin-bottom: 1rem;">Client Information</h3>
            <p><strong>Client Name:</strong> {{ $report->client_name }}</p>
            <p><strong>Email:</strong> {{ $report->client_email }}</p>
            <p><strong>Phone:</strong> {{ $report->phone_number }}</p>
        </div>

        <div>
            <h3 style="margin-bottom: 1rem;">Device Information</h3>
            <p><strong>Device Type:</strong> {{ $report->device_type }}</p>
            <p><strong>Model:</strong> {{ $report->model_name }}</p>
            <p><strong>Serial ID:</strong> {{ $report->device_serial_id }}</p>
        </div>
    </div>

    <div style="margin-top: 1.5rem;">
        <h3 style="margin-bottom: 1rem;">Problem Description</h3>
        <p style="white-space: pre-wrap;">{{ $report->problem_description }}</p>
    </div>

    @if($report->fix_description)
    <div style="margin-top: 1.5rem;">
        <h3 style="margin-bottom: 1rem;">Fix Description</h3>
        <p style="white-space: pre-wrap;">{{ $report->fix_description }}</p>
    </div>
    @endif

    @if($report->additional_notes)
    <div style="margin-top: 1.5rem;">
        <h3 style="margin-bottom: 1rem;">Additional Notes</h3>
        <p style="white-space: pre-wrap;">{{ $report->additional_notes }}</p>
    </div>
    @endif

    @if($report->pdf_hash)
    <div style="margin-top: 1.5rem; padding: 1rem; background-color: var(--bg-primary); border-radius: 0.25rem; border: 1px solid var(--border);">
        <h3 style="margin-bottom: 0.5rem; color: var(--text-primary);">PDF Verification Hash</h3>
        <p style="font-family: monospace; word-break: break-all; color: var(--text-primary); background-color: var(--bg-secondary); padding: 0.5rem; border-radius: 0.25rem;">{{ $report->pdf_hash }}</p>
        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem;">Use this hash to verify the integrity of the PDF document.</p>
    </div>
    @endif

    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
        <p style="color: var(--text-primary);"><strong>Created At:</strong> {{ $report->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}</p>
        <p style="color: var(--text-primary);"><strong>Last Updated:</strong> {{ $report->updated_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}</p>
        @if($report->completed_at)
            <p style="color: var(--text-primary);"><strong>Completed At:</strong> {{ $report->completed_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}</p>
        @endif
        <p style="color: var(--text-primary);"><strong>Created By:</strong> {{ $report->user->name }}</p>
    </div>
</div>

<div style="margin-top: 1rem;">
    @if(request('from') === 'dashboard')
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    @elseif(request('from') === 'all-reports')
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back to All Reports</a>
    @elseif($report->status === 'complete')
        <a href="{{ route('reports.completed') }}" class="btn btn-secondary">Back to Completed Reports</a>
    @else
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back to Reports</a>
    @endif
</div>
@endsection

