<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Troubleshooting Report #<?php echo e($report->id); ?></title>
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
        <p>Report ID: #<?php echo e($report->id); ?></p>
    </div>

    <div class="section">
        <h2>Report Status</h2>
        <span class="status-badge <?php echo e($report->status === 'complete' ? 'status-complete' : 'status-ongoing'); ?>">
            <?php echo e(strtoupper($report->status)); ?>

        </span>
    </div>

    <div class="section">
        <h2>Client Information</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Client Name:</div>
                <div class="info-value"><?php echo e($report->client_name); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?php echo e($report->client_email); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone Number:</div>
                <div class="info-value"><?php echo e($report->phone_number); ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Device Information</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Device Type:</div>
                <div class="info-value"><?php echo e($report->device_type); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Model Name:</div>
                <div class="info-value"><?php echo e($report->model_name); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Serial ID:</div>
                <div class="info-value"><?php echo e($report->device_serial_id); ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Problem Description</h2>
        <div class="content-box"><?php echo e($report->problem_description); ?></div>
    </div>

    <?php if($report->fix_description): ?>
    <div class="section">
        <h2>Fix Description</h2>
        <div class="content-box"><?php echo e($report->fix_description); ?></div>
    </div>
    <?php endif; ?>

    <?php if($report->additional_notes): ?>
    <div class="section">
        <h2>Additional Notes</h2>
        <div class="content-box"><?php echo e($report->additional_notes); ?></div>
    </div>
    <?php endif; ?>

    <?php if($report->pdf_hash): ?>
    <div class="section">
        <h2>PDF Verification Hash</h2>
        <div class="hash-box">
            <?php echo e($report->pdf_hash); ?>

        </div>
        <p style="font-size: 10px; color: #6b7280; margin-top: 5px;">
            This hash can be used to verify the integrity of this PDF document. 
            Compare this hash with the hash stored in the system to ensure the document has not been altered.
        </p>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p><strong>Created At:</strong> <?php echo e($report->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s')); ?></p>
        <p><strong>Last Updated:</strong> <?php echo e($report->updated_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s')); ?></p>
        <?php if($report->completed_at): ?>
            <p><strong>Completed At:</strong> <?php echo e($report->completed_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s')); ?></p>
        <?php endif; ?>
        <p><strong>Created By:</strong> <?php echo e($report->user->name); ?></p>
    </div>
</body>
</html>

<?php /**PATH C:\Users\alson\troubleshooting-report-system\resources\views/reports/pdf.blade.php ENDPATH**/ ?>