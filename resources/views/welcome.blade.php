<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Water Tank</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            background: #f8fafc; 
            color: #1e293b;
            width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
        }
        .navbar {
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.7rem 1.5rem;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.08);
            border-radius: 0 0 16px 16px;
            position: sticky;
            top: 0;
            z-index: 100;
            width: 100%;
            box-sizing: border-box;
        }
        .navbar .logo { display: flex; align-items: center; gap: 0.7rem; font-size: 1.3rem; font-weight: 600; color: #0284c7; text-decoration: none; }
        .navbar .logo img { width: 44px; height: 44px; border-radius: 50%; background: #fff; border: 2px solid #e0e7ef; }
        .nav-links { display: flex; gap: 1.2rem; align-items: center; }
        .nav-links a {
            color: #1e293b;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            padding: 0.2rem 0.7rem;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .nav-links a:hover {
            background: #e0e7ef;
            color: #0284c7;
        }
        .nav-links a.button {
            background: #0284c7;
            color: #fff;
            font-weight: 600;
        }
        .nav-links a.button:hover {
            background: #0369a1;
            color: #fff;
        }
        .hero {
            background: linear-gradient(180deg, #1da1f2 0%, #1da1f2 60%, #f8fafc 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            text-align: left;
            padding: 0.7rem 1.5rem;
            width: 100%;
            box-sizing: border-box;
        }
        .hero-content { flex: 1 1 350px; min-width: 260px; max-width: 520px; }
        .hero-content h1 { margin-top: 0; font-size: 2.8rem; font-weight: 700; }
        .hero-content p { color: #f1f5f9; font-size: 1.2rem; margin-bottom: 1.5rem; }
        .hero-content .get-started {
            color: #1e40af;
            background: #fff;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 4px rgba(2,132,199,0.08);
        }
        .hero-content .get-started:hover {
            background: #e0e7ef;
            color: #0284c7;
            box-shadow: 0 2px 8px rgba(2,132,199,0.13);
        }
        .hero-image { flex: 1 1 320px; min-width: 220px; display: flex; align-items: center; justify-content: center; }
        .hero-image img { max-width: 320px; width: 100%; border-radius: 18px; box-shadow: 0 2px 16px rgba(2,132,199,0.10); background: #fff; }
        .summary-section {
            width: 100%;
            margin: 0.5rem 0 0 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(2, 132, 199, 0.05);
            padding: 0.3rem 1.5rem;
            text-align: center;
            box-sizing: border-box;
        }
        .summary-section h2 { color: #0284c7; font-size: 1.7rem; margin-bottom: 1rem; }
        .summary-section p { color: #334155; font-size: 1.1rem; }
        .features-section {
            background: #fff;
            padding: 0.5rem 1.5rem 0.3rem 1.5rem;
            border-radius: 12px;
            margin: 0.5rem 0 0 0;
            width: 100%;
            box-shadow: 0 2px 10px rgba(2, 132, 199, 0.05);
            box-sizing: border-box;
        }
        .features-section h2 { text-align: center; font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; color: #1e293b; }
        .features-section p { text-align: center; color: #64748b; margin-bottom: 2.5rem; }
        .features-grid {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: stretch;
            gap: 0.7rem;
            flex-wrap: wrap;
        }
        .feature-card {
            background: #f8fafc;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.04);
            padding: 0.3rem 0.2rem;
            text-align: center;
            flex: 1 1 0;
            min-width: 160px;
            max-width: 260px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .feature-card i { font-size: 2.5rem; color: #0284c7; margin-bottom: 1rem; }
        .feature-card h3 { font-size: 1.2rem; font-weight: 600; margin-bottom: 0.7rem; color: #1e293b; }
        .feature-card p { color: #64748b; font-size: 1rem; }
        .benefits-section {
            width: 100%;
            margin: 0.5rem 0 0 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(2, 132, 199, 0.05);
            padding: 0.3rem 1.5rem;
            box-sizing: border-box;
        }
        .benefits-section h2 { color: #0284c7; font-size: 1.5rem; margin-bottom: 1rem; text-align:center; }
        .benefits-list {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: stretch;
            gap: 0.7rem;
            flex-wrap: wrap;
        }
        .benefit-item {
            flex: 1 1 120px;
            min-width: 100px;
            background: #f8fafc;
            border-radius: 8px;
            padding: 0.2rem;
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.03);
            text-align: center;
        }
        .benefit-item i { color: #0284c7; font-size: 2rem; margin-bottom: 0.5rem; }
        .benefit-item p { color: #334155; font-size: 1rem; }
        footer {
            background: #e5e7eb;
            padding: 0.3rem 1.5rem 0.2rem 1.5rem;
            color: #1e293b;
            font-size: 0.98rem;
            margin-top: 1.2rem;
            border-top: 1px solid #e0e7ef;
            width: 100%;
            box-sizing: border-box;
        }
        .footer-content {
            width: 100%;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: stretch;
            gap: 0.1rem;
            text-align: left;
            box-sizing: border-box;
        }
        .footer-col {
            flex: 1 1 0;
            min-width: 0;
            max-width: 33.33%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            background: transparent;
            margin: 0 0.1rem;
            border-radius: 6px;
            padding: 0.3rem 0.5rem;
            box-sizing: border-box;
        }
        .footer-col h4 { font-weight: 700; font-size: 0.98rem; margin-bottom: 0.1rem; color: #0284c7; }
        .footer-col ul { list-style: none; padding: 0; margin: 0; }
        .footer-col ul li { margin-bottom: 0.05rem; }
        .footer-col ul li a { color: #0284c7; text-decoration: none; }
        .footer-col ul li a:hover { text-decoration: underline; }
        .footer-col .footer-btn {
            display: inline-block;
            background: #0284c7;
            color: #fff;
            font-weight: 600;
            padding: 0.1rem 0.4rem;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 0.1rem;
            transition: background 0.2s, box-shadow 0.2s;
            font-size: 0.93rem;
            box-shadow: 0 1px 4px rgba(2,132,199,0.08);
        }
        .footer-col .footer-btn:hover {
            background: #0369a1;
            box-shadow: 0 2px 8px rgba(2,132,199,0.13);
        }
        .footer-bottom { margin-top: 0.2rem; color: #64748b; font-size: 0.9rem; text-align: center; }
        @media (max-width: 900px) {
            .navbar, .hero, .summary-section, .features-section, .benefits-section, footer {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .navbar {
                flex-direction: column;
                gap: 0.5rem;
                padding: 0.5rem 0.5rem;
            }
            .nav-links {
                gap: 0.7rem;
            }
            .hero {
                
                gap: 1rem !important;
                padding: 0.5rem 0.1rem 0.5rem 0 rem;
            }
            .hero-content, .hero-image {
                max-width: 100% !important;
                min-width: 0 !important;
            }
            .hero-content h1 {
                font-size: 1.3rem !important;
            }
            .hero-image img {
                max-width: 90vw;
                min-width: 120px;
            }
            .features-grid, .benefits-list {
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }
            .feature-card, .benefit-item {
                max-width: 100%;
                min-width: 0;
                padding: 0.2rem 0.1rem;
            }
            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }
            .footer-col {
                max-width: 100%;
                align-items: center;
                margin: 0.1rem 0;
                padding: 0.2rem 0.1rem;
            }
        }
        @media (max-width: 500px) {
            .navbar, .hero, .summary-section, .features-section, .benefits-section, footer {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            .navbar .logo img { width: 32px; height: 32px; }
            .hero-content h1 { font-size: 1.05rem !important; }
            .footer-col h4 { font-size: 0.85rem; }
        }
    </style>
</head>
<body>
<nav class="navbar">
        <a href="{{ route('welcome') }}" class="logo">
            <img src="{{ asset('log.jpg') }}" alt="Logo">
            Smart Water Tank
            <a href="{{ route('contact') }}">Contact Us</a>

        </a>
        <div class="nav-links">
            <a href="{{ route('welcome') }}">Home</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('login') }}" class="button">Login</a>
    </div>
</nav>
    <section class="hero">
        <div class="hero-content">
            <h1>Smart Water Tank Monitoring</h1>
            <p>
                IoT-powered smart water tank monitoring is a revolutionary tool that enhances efficiency, reliability, and sustainability. By offering real-time tracking, smart alerts, mobile access, and data-driven insights, this system empowers users to optimize water usage, reduce waste, and ensure a continuous supply. Embracing this technology is a step toward better resource management, benefiting individuals, businesses, and the environment.
            </p>
            <a href="{{ route('login') }}" class="get-started">Get Started</a>
            </div>
    </section>
    <section class="summary-section">
        <h2>Project Overview</h2>
        <p>
            The Smart Water Tank Monitoring System leverages IoT technology to provide real-time insights into water levels, usage, and tank health. Designed for homes, businesses, and communities, our platform helps you prevent water shortages, reduce waste, and optimize resource management with smart alerts and easy mobile access.
        </p>
    </section>
    <section class="benefits-section">
            <h2>Key Features</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>Real-time Monitoring</h3>
                <p>Monitor water levels and tank status in real time from anywhere, ensuring you always know your supply situation.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-bell"></i>
                <h3>Smart Alerts</h3>
                <p>Receive instant notifications for low water, leaks, or maintenance needs, so you can act before problems arise.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-mobile-alt"></i>
                <h3>Mobile Access</h3>
                <p>Access your tank data and receive alerts on your phone, tablet, or computer—anytime, anywhere.</p>
            </div>
        </div>
    </section>
    <section class="benefits-section">
        <h2>Why Choose Smart Water Tank?</h2>
        <div class="benefits-list">
            <div class="benefit-item">
                <i class="fas fa-leaf"></i>
                <p><strong>Sustainability:</strong> Reduce water waste and support eco-friendly practices.</p>
            </div>
            <div class="benefit-item">
                <i class="fas fa-shield-alt"></i>
                <p><strong>Reliability:</strong> Ensure a continuous water supply and avoid unexpected shortages.</p>
            </div>
            <div class="benefit-item">
                <i class="fas fa-cogs"></i>
                <p><strong>Automation:</strong> Let smart technology handle monitoring and alerting for you.</p>
            </div>
            <div class="benefit-item">
                <i class="fas fa-users"></i>
                <p><strong>For Everyone:</strong> Perfect for homes, businesses, and community water systems.</p>
            </div>
        </div>
    </section>
    <footer>
    <div class="footer-content">
            <div class="footer-col">
                <h4>Smart Water Tank</h4>
                <div>Empowering water management with smart technology, real-time monitoring, and actionable insights for a sustainable future.</div>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
            <ul>
                <li><a href="{{ route('welcome') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
            </ul>
                <a href="{{ route('contact') }}" class="footer-btn">Request Demo</a>
            </div>
            <div class="footer-col">
                <h4>Contact Us</h4>
                <div><i class="fas fa-envelope" style="color:#0284c7; margin-right:0.5rem;"></i><a href="mailto:maseemmy200@gmail.com">maseemmy200@gmail.com</a></div>
                <div style="margin-top:0.3rem;"><i class="fas fa-phone" style="color:#0284c7; margin-right:0.5rem;"></i><a href="tel:+250782330531">+250 782 330 531</a></div>
        </div>
        </div>
        <div class="footer-bottom">&copy; {{ date('Y') }} Smart Water Tank. All rights reserved.</div>
</footer>
</body>
</html>