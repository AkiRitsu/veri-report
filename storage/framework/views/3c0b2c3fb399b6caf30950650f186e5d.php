<?php $__env->startSection('title', 'Completed Reports'); ?>

<?php $__env->startSection('styles'); ?>
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
    
    .completed-actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        width: fit-content;
        margin: 0 auto;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1 style="margin-bottom: 1.5rem;">Completed Reports</h1>

<div class="card">
    <!-- Search Form -->
    <div style="margin-bottom: 1rem;">
        <form method="GET" action="<?php echo e(route('reports.completed')); ?>" style="display: flex; gap: 0.5rem; align-items: center;">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by client, device, model, serial..." style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 0.25rem; min-width: 250px;">
            <?php if(request('sort_by')): ?>
                <input type="hidden" name="sort_by" value="<?php echo e(request('sort_by')); ?>">
                <input type="hidden" name="sort_order" value="<?php echo e(request('sort_order')); ?>">
            <?php endif; ?>
            <button type="submit" class="btn btn-secondary">Search</button>
            <?php if(request('search')): ?>
                <a href="<?php echo e(route('reports.completed')); ?>" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if($reports->count() > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <a href="<?php echo e(route('reports.completed', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'id', 'sort_order' => (request('sort_by') == 'id' && request('sort_order') == 'desc') ? 'asc' : ((request('sort_by') == 'id' && request('sort_order') == 'asc') ? 'desc' : 'asc')]))); ?>" style="text-decoration: none; color: inherit;">
                            ID
                            <?php if(!request('sort_by') || request('sort_by') == 'id'): ?>
                                <?php echo e((request('sort_by') == 'id' && request('sort_order') == 'asc') ? '↑' : '↓'); ?>

                            <?php endif; ?>
                        </a>
                    </th>
                    <th>Client Name</th>
                    <th>Device Type</th>
                    <th>Model</th>
                    <th>Serial ID</th>
                    <th style="text-align: center;">
                        <a href="<?php echo e(route('reports.completed', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'completed_at', 'sort_order' => request('sort_by') == 'completed_at' && request('sort_order') == 'asc' ? 'desc' : 'asc']))); ?>" style="text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            Completed At
                            <?php
                                $isSortingByCompleted = request('sort_by') == 'completed_at' || (request('sort_by') == null);
                                $isAscending = request('sort_order') == 'asc';
                            ?>
                            <?php if($isSortingByCompleted): ?>
                                <?php echo e($isAscending ? '↑' : '↓'); ?>

                            <?php endif; ?>
                        </a>
                    </th>
                    <th style="text-align: center;">
                        <a href="<?php echo e(route('reports.completed', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'created_at', 'sort_order' => request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'desc' : 'asc']))); ?>" style="text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            Created At
                            <?php if(request('sort_by') == 'created_at'): ?>
                                <?php echo e(request('sort_order') == 'asc' ? '↑' : '↓'); ?>

                            <?php endif; ?>
                        </a>
                    </th>
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
                    <td><?php echo e($report->device_serial_id); ?></td>
                    <td style="text-align: center;"><?php echo e($report->completed_at ? $report->completed_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i') : 'N/A'); ?></td>
                    <td style="text-align: center;"><?php echo e($report->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i')); ?></td>
                    <td style="text-align: center;">
                        <div class="completed-actions-grid">
                            <form method="POST" action="<?php echo e(route('reports.destroy', $report)); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger reports-action-btn">Delete</button>
                            </form>
                            <a href="<?php echo e(route('reports.show', $report)); ?>" class="btn btn-secondary reports-action-btn">View</a>
                            <a href="<?php echo e(route('reports.export', $report)); ?>" class="btn btn-primary reports-action-btn">Export PDF</a>
                        <form method="POST" action="<?php echo e(route('reports.send-email', $report)); ?>" style="display: inline;">
                            <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success reports-action-btn">Email</button>
                        </form>
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
        <p>No completed reports found.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alson\troubleshooting-report-system\resources\views/reports/completed.blade.php ENDPATH**/ ?>