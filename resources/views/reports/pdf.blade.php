<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Troubleshooting Report #{{ $report->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1f2937;
        }
        .header p {
            margin: 5px 0;
            color: #6b7280;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            font-size: 16px;
            color: #1f2937;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            padding: 5px 10px 5px 0;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-complete {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-ongoing {
            background-color: #fef3c7;
            color: #92400e;
        }
        .content-box {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #6b7280;
        }
        .hash-box {
            background-color: #fef3c7;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-family: monospace;
            font-size: 10px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Troubleshooting Report</h1>
        <p>Management System for Troubleshooting Report</p>
        <p>Report ID: #{{ $report->id }}</p>
    </div>

    <div class="section">
        <h2>Report Status</h2>
        <span class="status-badge {{ $report->status === 'complete' ? 'status-complete' : 'status-ongoing' }}">
            {{ strtoupper($report->status) }}
        </span>
    </div>

    <div class="section">
        <h2>Client Information</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Client Name:</div>
                <div class="info-value">{{ $report->client_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $report->client_email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone Number:</div>
                <div class="info-value">{{ $report->phone_number }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Device Information</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Device Type:</div>
                <div class="info-value">{{ $report->device_type }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Model Name:</div>
                <div class="info-value">{{ $report->model_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Serial ID:</div>
                <div class="info-value">{{ $report->device_serial_id }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Problem Description</h2>
        <div class="content-box">{{ $report->problem_description }}</div>
    </div>

    @if($report->fix_description)
    <div class="section">
        <h2>Fix Description</h2>
        <div class="content-box">{{ $report->fix_description }}</div>
    </div>
    @endif

    @if($report->additional_notes)
    <div class="section">
        <h2>Additional Notes</h2>
        <div class="content-box">{{ $report->additional_notes }}</div>
    </div>
    @endif


    <div class="footer">
        <p><strong>Created At:</strong> {{ $report->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}</p>
        @if($report->completed_at)
            <p><strong>Completed At:</strong> {{ $report->completed_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}</p>
        @else
            <p><strong>Last Updated:</strong> {{ $report->updated_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}</p>
        @endif
        <p><strong>Created By:</strong> {{ $report->user->name }}</p>
    </div>
</body>
</html>

