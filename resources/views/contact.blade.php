<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact | Smart Water Tank</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Figtree', sans-serif; margin: 0; background: #f8fafc; color: #1e293b; }
        .navbar { background: #fff; display: flex; justify-content: space-between; align-items: center; padding: 1.2rem 3rem; box-shadow: 0 2px 8px rgba(2, 132, 199, 0.05); border-radius: 0 0 16px 16px; }
        .navbar .logo { display: flex; align-items: center; gap: 0.7rem; font-size: 1.5rem; font-weight: 600; color: #0284c7; text-decoration: none; }
        .navbar .logo img { width: 60px; height: 60px; border-radius: 50%; background: #fff; border: 2px solid #e0e7ef; }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a { color: #1e293b; text-decoration: none; font-weight: 500; font-size: 1.1rem; padding: 0.3rem 0.8rem; border-radius: 8px; transition: background 0.2s, color 0.2s; }
        .nav-links a.button { background: #0284c7; color: #fff; font-weight: 600; }
        .nav-links a.button:hover { background: #0369a1; }
        .hero { background: linear-gradient(180deg, #1da1f2 0%, #1da1f2 60%, #f8fafc 100%); color: #fff; text-align: center; padding: 4rem 1rem 4rem 1rem; }
        .hero h1 { font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; }
        .hero p { font-size: 1.2rem; max-width: 700px; margin: 0 auto; color: #f1f5f9; }
        .contact-section { max-width: 900px; margin: 2.5rem auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(2, 132, 199, 0.07); padding: 2.5rem 2rem; }
        .contact-section h2 { color: #0284c7; font-size: 1.5rem; margin-bottom: 1rem; }
        .contact-section form { display: flex; flex-direction: column; gap: 1.2rem; margin-bottom: 2rem; }
        .contact-section input, .contact-section textarea { padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 1rem; }
        .contact-section textarea { min-height: 100px; resize: vertical; }
        .contact-section button { background: #0284c7; color: #fff; font-weight: 600; border: none; border-radius: 8px; padding: 0.8rem 2rem; font-size: 1.1rem; cursor: pointer; transition: background 0.2s; }
        .contact-section button:hover { background: #0369a1; }
        .contact-info { margin-top: 1.5rem; color: #334155; font-size: 1.1rem; }
        .contact-info a { color: #0284c7; text-decoration: none; }
        .contact-info a:hover { text-decoration: underline; }
        .footer { background: #e5e7eb; padding: 2rem 0 1rem 0; text-align: center; color: #1e293b; font-size: 1.1rem; margin-top: 2.5rem; border-top: 1px solid #e0e7ef; }
        .footer .copyright { margin-top: 2rem; color: #64748b; font-size: 0.95rem; }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="{{ route('welcome') }}" class="logo">
        <img src="{{ asset('log.jpg') }}" alt="Logo">
        Smart Water Tank
    </a>
    <div class="nav-links">
        <a href="{{ route('welcome') }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('login') }}" class="button">Login</a>
    </div>
</nav>
<section class="hero">
    <h1>Contact Us</h1>
    <p>We'd love to hear from you! Reach out for demo requests, support, or any questions about Smart Water Tank.</p>
</section>
<div class="contact-section">
    <h2>Send Us a Message</h2>
    <form method="POST" action="#">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
    </form>
    <div class="contact-info">
        <div><i class="fas fa-envelope" style="color:#0284c7; margin-right:0.5rem;"></i><a href="mailto:maseemmy200@gmail.com">maseemmy200@gmail.com</a></div>
        <div style="margin-top:0.5rem;"><i class="fas fa-phone" style="color:#0284c7; margin-right:0.5rem;"></i><a href="tel:+250782330531">+250 782 330 531</a></div>
    </div>
</div>
<footer class="footer">
    <div class="copyright">&copy; {{ date('Y') }} Smart Water Tank. All rights reserved.</div>
</footer>
</body>
</html> 