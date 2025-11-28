

<?php $__env->startSection('title', 'Create Report'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .form-action-btn {
        width: 140px;
        height: 40px;
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
<h1 style="margin-bottom: 1.5rem;">Create New Report</h1>

<div class="card">
    <form method="POST" action="<?php echo e(route('reports.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="client_name" class="form-label">Client Name *</label>
            <input type="text" id="client_name" name="client_name" class="form-control" value="<?php echo e(old('client_name')); ?>" required>
        </div>

        <div class="form-group">
            <label for="device_type" class="form-label">Device Type *</label>
            <select id="device_type" name="device_type" class="form-control" required>
                <option value="">Select Device Type</option>
                <option value="PC" <?php echo e(old('device_type') === 'PC' ? 'selected' : ''); ?>>PC</option>
                <option value="Laptop" <?php echo e(old('device_type') === 'Laptop' ? 'selected' : ''); ?>>Laptop</option>
                <option value="Mobile Phone" <?php echo e(old('device_type') === 'Mobile Phone' ? 'selected' : ''); ?>>Mobile Phone</option>
            </select>
        </div>

        <div class="form-group">
            <label for="model_name" class="form-label">Model Name *</label>
            <input type="text" id="model_name" name="model_name" class="form-control" value="<?php echo e(old('model_name')); ?>" required>
        </div>

        <div class="form-group">
            <label for="device_serial_id" class="form-label">Device Serial ID *</label>
            <input type="text" id="device_serial_id" name="device_serial_id" class="form-control" value="<?php echo e(old('device_serial_id')); ?>" required>
        </div>

        <div class="form-group">
            <label for="problem_description" class="form-label">What's Wrong with the Device? *</label>
            <textarea id="problem_description" name="problem_description" class="form-control" rows="4" required><?php echo e(old('problem_description')); ?></textarea>
        </div>

        <div class="form-group">
            <label for="fix_description" class="form-label">How Did I Fix It?</label>
            <textarea id="fix_description" name="fix_description" class="form-control" rows="4"><?php echo e(old('fix_description')); ?></textarea>
        </div>

        <div class="form-group">
            <label for="phone_number" class="form-label">Phone Number *</label>
            <input type="text" id="phone_number" name="phone_number" class="form-control" value="<?php echo e(old('phone_number')); ?>" required>
        </div>

        <div class="form-group">
            <label for="client_email" class="form-label">Client Email *</label>
            <input type="email" id="client_email" name="client_email" class="form-control" value="<?php echo e(old('client_email')); ?>" required>
        </div>

        <div class="form-group">
            <label for="additional_notes" class="form-label">Additional Notes</label>
            <textarea id="additional_notes" name="additional_notes" class="form-control" rows="3"><?php echo e(old('additional_notes')); ?></textarea>
        </div>

        <div class="form-group" style="display: flex; gap: 0.5rem; align-items: center;">
            <button type="submit" class="btn btn-primary form-action-btn">Create Report</button>
            <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-secondary form-action-btn">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alson\troubleshooting-report-system\resources\views/reports/create.blade.php ENDPATH**/ ?>