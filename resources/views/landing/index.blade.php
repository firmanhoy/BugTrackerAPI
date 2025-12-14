<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bug Tracker API - Track & Manage Bugs Efficiently</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/img/logo.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-black: #000000;
            --color-gray: #4a4a4a;
            --color-red: #dc143c;
            --color-light: #f5f5f5;
            --color-white: #ffffff;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: var(--color-gray);
            overflow-x: hidden;
        }

        /* Navigation */
        nav {
            background: var(--color-black);
            padding: 1.5rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--color-white);
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .logo-img {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--color-white);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links a:hover {
            color: var(--color-red);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--color-black) 0%, var(--color-gray) 100%);
            color: var(--color-white);
            padding: 180px 2rem 120px;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(220, 20, 60, 0.2);
            color: var(--color-red);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2rem;
            border: 1px solid var(--color-red);
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 3rem;
            opacity: 0.9;
        }

        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 2px solid transparent;
        }

        .btn-primary {
            background: var(--color-red);
            color: var(--color-white);
            box-shadow: 0 10px 30px rgba(220, 20, 60, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(220, 20, 60, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--color-white);
            border: 2px solid var(--color-white);
        }

        .btn-secondary:hover {
            background: var(--color-white);
            color: var(--color-black);
        }

        /* Features Section - COMMENTED OUT */
        /*
        .features {
            padding: 100px 2rem;
            background: var(--color-light);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--color-black);
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.2rem;
            color: var(--color-gray);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--color-white);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 50px rgba(220, 20, 60, 0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--color-red), #ff4757);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: var(--color-white);
        }

        .feature-card h3 {
            font-size: 1.5rem;
            color: var(--color-black);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--color-gray);
            line-height: 1.8;
        }
        */

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--color-red), #ff4757);
            color: var(--color-white);
            padding: 100px 2rem;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
        }

        /* Footer */
        footer {
            background: var(--color-black);
            color: var(--color-white);
            padding: 3rem 2rem 2rem;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: var(--color-white);
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .footer-links a:hover {
            opacity: 1;
            color: var(--color-red);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .nav-links {
                gap: 1rem;
                font-size: 0.9rem;
            }

            .logo {
                font-size: 1.2rem;
            }

            .logo-img {
                width: 35px;
                height: 35px;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="/" class="logo">
                <div class="logo-img">
                    <img src="{{ asset('asset/img/logo.png') }}" alt="Bug Tracker Logo">
                </div>
                <span>Bug Tracker API</span>
            </a>
            <ul class="nav-links">
                <li><a href="/api/documentation">📚 Documentation</a></li>
                <li><a href="https://github.com/firmanhoy/BugTrackerAPI" target="_blank">⭐ GitHub</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <span class="hero-badge">POWERFUL REST API</span>
            <h1>Track & Manage Bugs Efficiently</h1>
            <p>Modern RESTful API for bug tracking and project management. Built with Laravel for developers who demand
                reliability and performance.</p>
            <div class="cta-buttons">
                <a href="/api/documentation" class="btn btn-primary">
                    Get Started →
                </a>
                <a href="https://github.com/firmanhoy/BugTrackerAPI" class="btn btn-secondary" target="_blank">
                    View on GitHub
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section - COMMENTED OUT -->
    <!--
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Powerful Features</h2>
                <p>Everything you need to build amazing bug tracking applications</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Fast & Reliable</h3>
                    <p>Built with Laravel for optimal performance and reliability. Handle thousands of requests with ease.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Secure Authentication</h3>
                    <p>Industry-standard authentication with JWT tokens and OAuth2 support for enterprise security.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Comprehensive API</h3>
                    <p>Full CRUD operations for bugs, projects, users, and more. Everything documented and easy to use.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>RESTful Design</h3>
                    <p>Clean, intuitive endpoints following REST principles. Easy integration with any frontend framework.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h3>Rich Documentation</h3>
                    <p>Detailed API documentation with examples in multiple languages. Get started in minutes.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚀</div>
                    <h3>Developer Friendly</h3>
                    <p>Intuitive API design, comprehensive error messages, and excellent developer experience.</p>
                </div>
            </div>
        </div>
    </section>
    -->

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Start tracking bugs efficiently with our powerful API today</p>
            <div class="cta-buttons">
                <a href="/api/documentation" class="btn btn-secondary">Read Documentation</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-links">
            <a href="/api/documentation">Documentation</a>
            <a href="https://github.com/firmanhoy/BugTrackerAPI" target="_blank">GitHub</a>
            <a href="#">Terms of Service</a>
            <a href="#">Privacy Policy</a>
        </div>
        <p>&copy; 2024 Bug Tracker API. By <a href="" style="font-weight: bold">Andrean Firmaansyah</a></p>
    </footer>
</body>

</html>
