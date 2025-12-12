

<?php $__env->startSection('title', 'User Monitoring'); ?>

<?php $__env->startSection('content'); ?>
<h1 style="margin-bottom: 1.5rem;">User Monitoring</h1>

<div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0;">All Technicians</h2>
        <a href="<?php echo e(route('admin.technicians.create')); ?>" class="btn btn-primary">Create New Technician</a>
    </div>
    
    <!-- Search Form -->
    <form method="GET" action="<?php echo e(route('admin.users.monitoring')); ?>" style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 1rem;">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by name or email..." style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 0.25rem; min-width: 250px;">
        <button type="submit" class="btn btn-secondary" style="display: inline-flex; align-items: center; justify-content: center; text-align: center;">Search</button>
        <?php if(request('search')): ?>
            <a href="<?php echo e(route('admin.users.monitoring')); ?>" class="btn btn-secondary" style="display: inline-flex; align-items: center; justify-content: center; text-align: center;">Clear</a>
        <?php endif; ?>
    </form>
    
    <?php if($technicians->count() > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th style="text-align: center;">Ongoing Reports</th>
                    <th style="text-align: center;">Completed Reports</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $technicians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $technician): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($technician->name); ?></td>
                    <td><?php echo e($technician->email); ?></td>
                    <td style="text-align: center;">
                        <span class="badge badge-warning"><?php echo e($technician->ongoing_reports_count); ?></span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-success"><?php echo e($technician->completed_reports_count); ?></span>
                    </td>
                    <td style="text-align: center;">
                        <a href="<?php echo e(route('admin.technicians.reports', $technician)); ?>" class="btn btn-primary" style="padding: 0.5rem 1rem;">View Reports</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>
            <?php if(request('search')): ?>
                No technicians found matching "<?php echo e(request('search')); ?>". 
                <a href="<?php echo e(route('admin.users.monitoring')); ?>">Clear search</a>
            <?php else: ?>
                No technicians found. <a href="<?php echo e(route('admin.technicians.create')); ?>">Create the first technician account</a>
            <?php endif; ?>
        </p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alson\troubleshooting-report-system\resources\views/admin/user-monitoring.blade.php ENDPATH**/ ?>