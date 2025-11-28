<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome - Troubleshooting Report System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
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
            --dark-bg: #0f172a;
            --dark-bg-2: #1e293b;
        }

        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            background: var(--dark-bg);
            overflow-x: hidden;
        }

        .header {
            background: rgba(128, 128, 128, 0.3);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            padding: 1.25rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0;
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .header-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .header-btn {
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            border: 2px solid var(--primary);
        }

        .header-btn:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .header-btn.primary {
            background: var(--primary);
            color: white;
            border: 2px solid var(--primary);
        }

        .header-btn.primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .hero {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            position: relative;
            padding: 4rem 2rem;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                linear-gradient(45deg, transparent 30%, rgba(99, 102, 241, 0.03) 50%, transparent 70%);
            background-size: 100% 100%, 100% 100%, 200% 200%;
            animation: backgroundShift 20s ease-in-out infinite;
            pointer-events: none;
            opacity: 0.6;
        }

        @keyframes backgroundShift {
            0%, 100% { background-position: 0% 0%, 0% 0%, 0% 0%; }
            50% { background-position: 100% 100%, -100% -100%, 50% 50%; }
        }

        /* Technical Pattern Background */
        .tech-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.15;
            background-image: 
                repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(99, 102, 241, 0.1) 2px, rgba(99, 102, 241, 0.1) 4px),
                repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(99, 102, 241, 0.1) 2px, rgba(99, 102, 241, 0.1) 4px);
            background-size: 50px 50px;
            pointer-events: none;
            animation: patternMove 30s linear infinite;
        }

        @keyframes patternMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .hero-content {
            max-width: 900px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-headline {
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            color: white;
            letter-spacing: -2px;
        }

        .hero-headline .highlight {
            color: var(--primary);
            display: block;
        }

        .hero-description {
            font-size: clamp(1rem, 2vw, 1.25rem);
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 3rem;
            line-height: 1.6;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }


        @media (max-width: 768px) {
            .header {
                padding: 1rem 1.5rem;
            }

            .brand {
                font-size: 1.5rem;
            }

            .header-buttons {
                gap: 0.75rem;
            }

            .header-btn {
                padding: 0.625rem 1.25rem;
                font-size: 0.875rem;
            }

            .hero {
                padding: 3rem 1.5rem;
            }

        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-content > * {
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-headline {
            animation-delay: 0.1s;
        }

        .hero-description {
            animation-delay: 0.3s;
        }

        /* Scroll Animation Classes */
        .scroll-animate {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-animate.animated {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-animate-delay-1 {
            transition-delay: 0.1s;
        }

        .scroll-animate-delay-2 {
            transition-delay: 0.2s;
        }

        .scroll-animate-delay-3 {
            transition-delay: 0.3s;
        }

        .scroll-animate-delay-4 {
            transition-delay: 0.4s;
        }

        .scroll-animate-delay-5 {
            transition-delay: 0.5s;
        }

        .scroll-animate-delay-6 {
            transition-delay: 0.6s;
        }

        /* Fade in from sides */
        .scroll-fade-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-fade-left.animated {
            opacity: 1;
            transform: translateX(0);
        }

        .scroll-fade-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-fade-right.animated {
            opacity: 1;
            transform: translateX(0);
        }

        /* Scale animation */
        .scroll-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-scale.animated {
            opacity: 1;
            transform: scale(1);
        }

        .content-section {
            padding: 5rem 2rem;
            background: var(--dark-bg);
            position: relative;
            overflow: hidden;
        }

        .content-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.06) 0%, transparent 40%);
            pointer-events: none;
            opacity: 0.5;
        }

        .content-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                repeating-linear-gradient(45deg, transparent, transparent 100px, rgba(99, 102, 241, 0.03) 100px, rgba(99, 102, 241, 0.03) 102px);
            pointer-events: none;
            opacity: 0.3;
        }

        .content-section:nth-child(even) {
            background: var(--dark-bg-2);
        }

        .content-section:nth-child(even)::before {
            background-image: 
                radial-gradient(circle at 90% 20%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 10% 80%, rgba(99, 102, 241, 0.06) 0%, transparent 40%);
        }

        .section-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .section-title {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 700;
            color: white;
            text-align: center;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .section-subtitle {
            font-size: clamp(1rem, 2vw, 1.125rem);
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 2rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.2);
            background: rgba(255, 255, 255, 0.08);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: white;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
        }

        .feature-description {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        .benefits-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .benefit-item {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .benefit-icon {
            width: 24px;
            height: 24px;
            color: var(--primary);
            flex-shrink: 0;
            margin-top: 0.25rem;
        }

        .benefit-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .benefit-content p {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        .footer {
            background: var(--dark-bg-2);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem 2rem 2rem;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9375rem;
            margin-bottom: 1rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .content-section {
                padding: 3rem 1.5rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .benefits-list {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="{{ route('welcome') }}" class="brand">Troubleshooting Reports</a>
            <div class="header-buttons">
                <a href="{{ route('login') }}" class="header-btn">Login</a>
                <a href="{{ route('register') }}" class="header-btn primary">Sign Up</a>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="tech-pattern"></div>
        <div class="hero-content">
            <h1 class="hero-headline">
                Troubleshooting Report
                <span class="highlight">Management System</span>
            </h1>
            <p class="hero-description">
                A comprehensive troubleshooting report management system designed for modern workflows. Efficient, Powerful, and User-Friendly.
            </p>
        </div>
    </section>

    <section class="content-section">
        <div class="section-container">
            <h2 class="section-title scroll-animate">Key Features</h2>
            <p class="section-subtitle scroll-animate scroll-animate-delay-1">Everything you need to manage troubleshooting reports efficiently</p>
            
            <div class="features-grid">
                <div class="feature-card scroll-animate scroll-animate-delay-1">
                    <div class="feature-icon">📝</div>
                    <h3 class="feature-title">Report Management</h3>
                    <p class="feature-description">
                        Create, edit, and manage troubleshooting reports with ease. Track device information, client details, and problem descriptions all in one place.
                    </p>
                </div>

                <div class="feature-card scroll-animate scroll-animate-delay-2">
                    <div class="feature-icon">🔍</div>
                    <h3 class="feature-title">Advanced Search</h3>
                    <p class="feature-description">
                        Quickly find reports using powerful search and filtering capabilities. Sort by date, status, or any other criteria to locate what you need instantly.
                    </p>
                </div>

                <div class="feature-card scroll-animate scroll-animate-delay-3">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Status Tracking</h3>
                    <p class="feature-description">
                        Monitor report progress from on-going to completed. Get real-time updates on the status of all your troubleshooting reports.
                    </p>
                </div>

                <div class="feature-card scroll-animate scroll-animate-delay-4">
                    <div class="feature-icon">📄</div>
                    <h3 class="feature-title">PDF Export</h3>
                    <p class="feature-description">
                        Generate professional PDF reports with a single click. Each PDF includes a verification hash to ensure document integrity and authenticity.
                    </p>
                </div>

                <div class="feature-card scroll-animate scroll-animate-delay-5">
                    <div class="feature-icon">📧</div>
                    <h3 class="feature-title">Email Integration</h3>
                    <p class="feature-description">
                        Send completed reports directly to clients via email. Streamline your communication workflow and keep clients informed automatically.
                    </p>
                </div>

                <div class="feature-card scroll-animate scroll-animate-delay-6">
                    <div class="feature-icon">🌙</div>
                    <h3 class="feature-title">Dark Mode</h3>
                    <p class="feature-description">
                        Customize your experience with dark mode support. Your preference is saved and persists across all your devices and sessions.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="section-container">
            <h2 class="section-title scroll-animate">Why Choose Our System?</h2>
            <p class="section-subtitle scroll-animate scroll-animate-delay-1">Designed with efficiency and user experience in mind</p>
            
            <div class="benefits-list">
                <div class="benefit-item scroll-fade-left scroll-animate-delay-1">
                    <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div class="benefit-content">
                        <h3>Streamlined Workflow</h3>
                        <p>Reduce time spent on administrative tasks and focus on what matters most - solving technical issues for your clients.</p>
                    </div>
                </div>

                <div class="benefit-item scroll-fade-right scroll-animate-delay-2">
                    <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <div class="benefit-content">
                        <h3>Secure & Reliable</h3>
                        <p>Your data is protected with industry-standard security measures. PDF verification hashes ensure document integrity and prevent tampering.</p>
                    </div>
                </div>

                <div class="benefit-item scroll-fade-left scroll-animate-delay-3">
                    <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <div class="benefit-content">
                        <h3>Fast & Responsive</h3>
                        <p>Built with modern web technologies for optimal performance. Experience lightning-fast load times and smooth interactions.</p>
                    </div>
                </div>

                <div class="benefit-item scroll-fade-right scroll-animate-delay-4">
                    <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    <div class="benefit-content">
                        <h3>Organized Dashboard</h3>
                        <p>Get a clear overview of all your reports at a glance. Access recent reports, track status, and manage everything from one central location.</p>
                    </div>
                </div>

                <div class="benefit-item scroll-fade-left scroll-animate-delay-5">
                    <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="benefit-content">
                        <h3>Complete History</h3>
                        <p>Maintain a complete record of all troubleshooting activities. Access historical reports, track completion times, and analyze your workflow patterns.</p>
                    </div>
                </div>

                <div class="benefit-item scroll-fade-right scroll-animate-delay-6">
                    <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <div class="benefit-content">
                        <h3>Mobile Friendly</h3>
                        <p>Access your reports from any device, anywhere. The responsive design ensures a seamless experience on desktop, tablet, and mobile devices.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <p class="footer-text scroll-animate">
                &copy; {{ date('Y') }} Troubleshooting Report Management System. All rights reserved.
            </p>
            <div class="footer-links scroll-animate scroll-animate-delay-1">
                <a href="{{ route('welcome') }}" class="footer-link">Home</a>
                <a href="{{ route('login') }}" class="footer-link">Login</a>
                <a href="{{ route('register') }}" class="footer-link">Sign Up</a>
            </div>
        </div>
    </footer>

    <script>
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    // Optionally stop observing after animation
                    // observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all elements with scroll animation classes
        document.addEventListener('DOMContentLoaded', () => {
            const animatedElements = document.querySelectorAll('.scroll-animate, .scroll-fade-left, .scroll-fade-right, .scroll-scale');
            animatedElements.forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>

