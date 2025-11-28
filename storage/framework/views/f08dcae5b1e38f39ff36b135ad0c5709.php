

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1 style="margin-bottom: 1.5rem;">Dashboard</h1>

<div class="card">
    <h2 style="margin-bottom: 1rem;">Recent Reports</h2>
    
    <?php if($recentReports->count() > 0): ?>
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
                <?php $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($report->id); ?></td>
                    <td><?php echo e($report->client_name); ?></td>
                    <td><?php echo e($report->device_type); ?></td>
                    <td><?php echo e($report->model_name); ?></td>
                    <td style="text-align: center;">
                        <span class="badge <?php echo e($report->status === 'complete' ? 'badge-success' : 'badge-warning'); ?>">
                            <?php echo e($report->status === 'complete' ? 'Complete' : 'On-going'); ?>

                        </span>
                    </td>
                    <td style="text-align: center;"><?php echo e($report->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i')); ?></td>
                    <td style="text-align: center;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; align-items: center; justify-content: center;">
                            <?php if($report->status === 'on-going'): ?>
                                <form method="POST" action="<?php echo e(route('reports.destroy', $report)); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger dashboard-action-btn">Delete</button>
                                </form>
                                <a href="<?php echo e(route('reports.show', ['report' => $report, 'from' => 'dashboard'])); ?>" class="btn btn-secondary dashboard-action-btn">View</a>
                                <a href="<?php echo e(route('reports.edit', $report)); ?>" class="btn btn-primary dashboard-action-btn">Edit</a>
                                <form method="POST" action="<?php echo e(route('reports.complete', $report)); ?>" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success dashboard-action-btn">Complete</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(route('reports.destroy', $report)); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger dashboard-action-btn">Delete</button>
                                </form>
                                <a href="<?php echo e(route('reports.show', ['report' => $report, 'from' => 'dashboard'])); ?>" class="btn btn-secondary dashboard-action-btn">View</a>
                                <a href="<?php echo e(route('reports.export', $report)); ?>" class="btn btn-primary dashboard-action-btn">Export PDF</a>
                                <form method="POST" action="<?php echo e(route('reports.send-email', $report)); ?>" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success dashboard-action-btn">Email</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No reports yet. <a href="<?php echo e(route('reports.create')); ?>">Create your first report</a></p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alson\troubleshooting-report-system\resources\views/dashboard/index.blade.php ENDPATH**/ ?>