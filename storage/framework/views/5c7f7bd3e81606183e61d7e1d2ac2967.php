

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

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
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        gap: 0.5rem;
        width: fit-content;
        margin: 0 auto;
    }
    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 2rem;
    }
    .period-selector {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .period-btn {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
        background: var(--bg-secondary);
        color: var(--text-primary);
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .period-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    .period-btn:hover {
        background: var(--primary-light);
        color: white;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1 style="margin-bottom: 1.5rem;">Admin Dashboard</h1>

<!-- Completed Reports Chart -->
<div class="card" style="margin-bottom: 2rem;">
    <h2 style="margin-bottom: 1rem;">Completed Reports</h2>
    <div class="period-selector">
        <a href="<?php echo e(route('dashboard', ['period' => 'daily'])); ?>" class="period-btn <?php echo e($period === 'daily' ? 'active' : ''); ?>">Daily</a>
        <a href="<?php echo e(route('dashboard', ['period' => 'weekly'])); ?>" class="period-btn <?php echo e($period === 'weekly' ? 'active' : ''); ?>">Weekly</a>
        <a href="<?php echo e(route('dashboard', ['period' => 'monthly'])); ?>" class="period-btn <?php echo e($period === 'monthly' ? 'active' : ''); ?>">Monthly</a>
    </div>
    <div class="chart-container">
        <canvas id="completedReportsChart"></canvas>
    </div>
</div>

<!-- Top Users with Most Ongoing Reports -->
<div class="card" style="margin-bottom: 2rem;">
    <h2 style="margin-bottom: 1rem;">Top Users with Ongoing Reports</h2>
    <?php if($topUsers->count() > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th style="text-align: center;">Ongoing Reports</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($user->name); ?></td>
                    <td><?php echo e($user->email); ?></td>
                    <td style="text-align: center;">
                        <span class="badge badge-warning"><?php echo e($user->ongoing_count); ?></span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No technicians with ongoing reports.</p>
    <?php endif; ?>
</div>

<!-- Recent Reports -->
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
                    <th>Technician</th>
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
                    <td><?php echo e($report->user->name); ?></td>
                    <td style="text-align: center;">
                        <span class="badge <?php echo e($report->status === 'complete' ? 'badge-success' : 'badge-warning'); ?>">
                            <?php echo e($report->status === 'complete' ? 'Complete' : 'On-going'); ?>

                        </span>
                    </td>
                    <td style="text-align: center;"><?php echo e($report->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i')); ?></td>
                    <td style="text-align: center;">
                        <div class="actions-grid">
                            <?php if($report->status === 'on-going'): ?>
                                <form method="POST" action="<?php echo e(route('reports.destroy', $report)); ?>" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger dashboard-action-btn">Delete</button>
                                </form>
                                <a href="<?php echo e(route('reports.show', ['report' => $report, 'from' => 'dashboard'])); ?>" class="btn btn-secondary dashboard-action-btn">View</a>
                                <a href="<?php echo e(route('reports.edit', $report)); ?>" class="btn btn-primary dashboard-action-btn">Edit</a>
                                <form method="POST" action="<?php echo e(route('reports.complete', $report)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success dashboard-action-btn">Complete</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(route('reports.destroy', $report)); ?>" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger dashboard-action-btn">Delete</button>
                                </form>
                                <a href="<?php echo e(route('reports.show', ['report' => $report, 'from' => 'dashboard'])); ?>" class="btn btn-secondary dashboard-action-btn">View</a>
                                <a href="<?php echo e(route('reports.export', $report)); ?>" class="btn btn-primary dashboard-action-btn">Export PDF</a>
                                <form method="POST" action="<?php echo e(route('reports.send-email', $report)); ?>">
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
        <p>No reports yet.</p>
    <?php endif; ?>
</div>

<script>
    const ctx = document.getElementById('completedReportsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartData['labels'], 15, 512) ?>,
            datasets: [{
                label: 'Completed Reports',
                data: <?php echo json_encode($chartData['data'], 15, 512) ?>,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alson\troubleshooting-report-system\resources\views/dashboard/admin.blade.php ENDPATH**/ ?>