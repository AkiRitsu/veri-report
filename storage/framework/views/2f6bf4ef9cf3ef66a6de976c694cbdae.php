

<?php $__env->startSection('title', 'Technician Reports'); ?>

<?php $__env->startSection('content'); ?>
<h1 style="margin-bottom: 1.5rem;">Reports for <?php echo e($user->name); ?></h1>

<div class="card">
    <div style="margin-bottom: 1rem;">
        <a href="<?php echo e(route('admin.users.monitoring')); ?>" class="btn btn-secondary" style="margin-bottom: 1rem;">← Back to User Monitoring</a>
    </div>
    
    <?php if($reports->count() > 0): ?>
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
                <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            <a href="<?php echo e(route('reports.show', ['report' => $report, 'from' => 'technician-reports', 'user_id' => $user->id])); ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem; min-width: 100px; text-align: center;">View</a>
                            <?php if($report->status === 'on-going'): ?>
                                <a href="<?php echo e(route('reports.edit', ['report' => $report, 'from' => 'technician-reports', 'user_id' => $user->id])); ?>" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem; min-width: 100px; text-align: center;">Edit</a>
                            <?php else: ?>
                                <a href="<?php echo e(route('reports.export', $report)); ?>" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem; min-width: 100px; text-align: center;">Export PDF</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        
        <div style="margin-top: 1rem;">
            <?php echo e($reports->links()); ?>

        </div>
    <?php else: ?>
        <p>No reports found for this technician.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alson\troubleshooting-report-system\resources\views/admin/technician-reports.blade.php ENDPATH**/ ?>