

<?php $__env->startSection('title', 'View Report'); ?>

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1 style="margin-bottom: 1.5rem;">Report #<?php echo e($report->id); ?></h1>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <span class="badge <?php echo e($report->status === 'complete' ? 'badge-success' : 'badge-warning'); ?>">
                Status: <?php echo e(ucfirst($report->status)); ?>

            </span>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <?php if($report->status === 'on-going'): ?>
                <form method="POST" action="<?php echo e(route('reports.destroy', $report)); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger report-action-btn">Delete</button>
                </form>
                <a href="<?php echo e(route('reports.edit', $report)); ?>" class="btn btn-primary report-action-btn">Edit</a>
                <form method="POST" action="<?php echo e(route('reports.complete', $report)); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success report-action-btn">Mark as Complete</button>
                </form>
            <?php else: ?>
                <form method="POST" action="<?php echo e(route('reports.destroy', $report)); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger report-action-btn">Delete</button>
                </form>
                <a href="<?php echo e(route('reports.export', $report)); ?>" class="btn btn-primary report-action-btn">Export PDF</a>
                <form method="POST" action="<?php echo e(route('reports.send-email', $report)); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success report-action-btn">Send Email</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div>
            <h3 style="margin-bottom: 1rem;">Client Information</h3>
            <p><strong>Client Name:</strong> <?php echo e($report->client_name); ?></p>
            <p><strong>Email:</strong> <?php echo e($report->client_email); ?></p>
            <p><strong>Phone:</strong> <?php echo e($report->phone_number); ?></p>
        </div>

        <div>
            <h3 style="margin-bottom: 1rem;">Device Information</h3>
            <p><strong>Device Type:</strong> <?php echo e($report->device_type); ?></p>
            <p><strong>Model:</strong> <?php echo e($report->model_name); ?></p>
            <p><strong>Serial ID:</strong> <?php echo e($report->device_serial_id); ?></p>
        </div>
    </div>

    <div style="margin-top: 1.5rem;">
        <h3 style="margin-bottom: 1rem;">Problem Description</h3>
        <p style="white-space: pre-wrap;"><?php echo e($report->problem_description); ?></p>
    </div>

    <?php if($report->fix_description): ?>
    <div style="margin-top: 1.5rem;">
        <h3 style="margin-bottom: 1rem;">Fix Description</h3>
        <p style="white-space: pre-wrap;"><?php echo e($report->fix_description); ?></p>
    </div>
    <?php endif; ?>

    <?php if($report->additional_notes): ?>
    <div style="margin-top: 1.5rem;">
        <h3 style="margin-bottom: 1rem;">Additional Notes</h3>
        <p style="white-space: pre-wrap;"><?php echo e($report->additional_notes); ?></p>
    </div>
    <?php endif; ?>

    <?php if($report->status === 'complete' && $report->pdf_hash): ?>
    <div style="margin-top: 1.5rem; padding: 1rem; background-color: var(--bg-primary); border-radius: 0.25rem; border: 1px solid var(--border);">
        <h3 style="margin-bottom: 0.5rem; color: var(--text-primary);">PDF Verification Hash</h3>
        <p style="font-family: monospace; word-break: break-all; color: var(--text-primary); background-color: var(--bg-secondary); padding: 0.5rem; border-radius: 0.25rem;"><?php echo e($report->pdf_hash); ?></p>
        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem;">
            <strong>SHA-256 Hash:</strong> This hash is calculated from the PDF file content. 
            To verify the integrity of the exported PDF document, calculate the SHA-256 hash of the PDF file 
            and compare it with the hash shown above. They should match exactly.
        </p>
    </div>
    <?php endif; ?>

    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
        <p style="color: var(--text-primary);"><strong>Created At:</strong> <?php echo e($report->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s')); ?></p>
        <p style="color: var(--text-primary);"><strong>Last Updated:</strong> <?php echo e($report->updated_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s')); ?></p>
        <?php if($report->completed_at): ?>
            <p style="color: var(--text-primary);"><strong>Completed At:</strong> <?php echo e($report->completed_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s')); ?></p>
        <?php endif; ?>
        <p style="color: var(--text-primary);"><strong>Created By:</strong> <?php echo e($report->user->name); ?></p>
    </div>
</div>

<div style="margin-top: 1rem;">
    <?php if(request('from') === 'dashboard'): ?>
        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">Back to Dashboard</a>
    <?php elseif(request('from') === 'all-reports'): ?>
        <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-secondary">Back to All Reports</a>
    <?php elseif($report->status === 'complete'): ?>
        <a href="<?php echo e(route('reports.completed')); ?>" class="btn btn-secondary">Back to Completed Reports</a>
    <?php else: ?>
        <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-secondary">Back to Reports</a>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alson\troubleshooting-report-system\resources\views/reports/show.blade.php ENDPATH**/ ?>