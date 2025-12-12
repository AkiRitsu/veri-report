<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-theme="<?php echo e((auth()->guard('admin')->check() && auth()->guard('admin')->user()->dark_mode) || (auth()->guard('web')->check() && auth()->guard('web')->user()->dark_mode) ? 'dark' : 'light'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?> - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --success: #10b981;
            --success-dark: #059669;
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --warning: #f59e0b;
            --secondary: #6b7280;
            --secondary-dark: #4b5563;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        [data-theme="dark"] {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --border: #334155;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.3);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--border) 100%);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            transition: background 0.3s ease, color 0.3s ease;
        }

        [data-theme="dark"] body {
            background: linear-gradient(135deg, var(--bg-primary) 0%, #1e293b 100%);
        }

        .navbar {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 1.25rem 2rem;
            box-shadow: var(--shadow-lg);
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            color: white;
            background: linear-gradient(135deg, #818cf8 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .navbar-menu {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            align-items: center;
        }

        .navbar-menu a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9375rem;
        }

        .navbar-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateY(-1px);
        }

        .navbar-menu button {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-menu button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .container {
            max-width: 1400px;
            margin: 2.5rem auto;
            padding: 0 2rem;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            letter-spacing: -0.5px;
        }

        h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }

        .card {
            background: var(--bg-secondary);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        [data-theme="dark"] .card {
            background: var(--bg-secondary);
            border-color: var(--border);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .btn {
            display: inline-block;
            padding: 0.625rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, var(--success-dark) 100%);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, var(--success-dark) 0%, var(--success) 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, var(--danger-dark) 100%);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, var(--danger-dark) 0%, var(--danger) 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--secondary) 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--bg-secondary);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table thead {
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--border) 100%);
        }

        [data-theme="dark"] .table thead {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }

        .table th {
            padding: 1rem 1.25rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border);
        }

        .table th a {
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s;
        }

        .table th a:hover {
            color: var(--primary);
        }

        .table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: var(--bg-primary);
            transform: scale(1.01);
        }

        [data-theme="dark"] .table tbody tr:hover {
            background: #334155;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            transform: translateY(-1px);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            transform: translateY(-1px);
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            box-shadow: var(--shadow-sm);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-left-color: var(--success);
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-left-color: var(--danger);
        }

        .badge {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-sm);
        }

        .badge-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .badge-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            color: var(--text-primary);
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        .pagination a:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .pagination .active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Action buttons in tables */
        .table .btn {
            min-width: 70px;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            text-align: center;
            white-space: nowrap;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .container {
                padding: 0 1rem;
            }

            .card {
                padding: 1.5rem;
            }

            .table {
                font-size: 0.875rem;
            }

            .table th,
            .table td {
                padding: 0.75rem 0.5rem;
            }

            .table .btn {
                min-width: 60px;
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
            }
        }
    </style>

    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <?php if(auth()->guard('admin')->check() || auth()->guard('web')->check()): ?>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="<?php echo e(route('dashboard')); ?>" class="navbar-brand">Troubleshooting Report System</a>
            <ul class="navbar-menu">
                <li><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                <li><a href="<?php echo e(route('reports.index')); ?>">All Reports</a></li>
                <li><a href="<?php echo e(route('reports.completed')); ?>">Completed Reports</a></li>
                <li><a href="<?php echo e(route('reports.create')); ?>">New Report</a></li>
                <?php if(auth()->guard('admin')->check()): ?>
                <li><a href="<?php echo e(route('admin.users.monitoring')); ?>">User Monitoring</a></li>
                <li><a href="<?php echo e(route('admin.technicians.create')); ?>">Create Technician</a></li>
                <?php endif; ?>
                <li>
                    <form method="POST" action="<?php echo e(route('toggle-dark-mode')); ?>" style="display: inline;" id="dark-mode-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn" style="background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; transition: all 0.3s ease;" title="<?php echo e(((auth()->guard('admin')->check() && auth()->guard('admin')->user()->dark_mode) || (auth()->guard('web')->check() && auth()->guard('web')->user()->dark_mode)) ? 'Switch to Light Mode' : 'Switch to Dark Mode'); ?>">
                            <?php echo e(((auth()->guard('admin')->check() && auth()->guard('admin')->user()->dark_mode) || (auth()->guard('web')->check() && auth()->guard('web')->user()->dark_mode)) ? '☀️' : '🌙'); ?>

                        </button>
                    </form>
                </li>
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn" style="background: transparent; color: white; border: none; padding: 0.5rem 1rem;">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <?php endif; ?>

    <div class="container">
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-error">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>

<?php /**PATH C:\Users\alson\troubleshooting-report-system\resources\views/layouts/app.blade.php ENDPATH**/ ?>