<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atma Library — Landing</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body class="bg-light">
    <header class="header container">
        <div class="logo"> <a href="/">Atma Library</a></div>
        <nav class="nav">
            <a href="/member" class="nav-link">Member</a>
            <a href="/petugas/login" class="nav-link btn btn-outline-primary">Officer</a>
        </nav>
    </header>

    <main class="hero container">
        <div class="hero-inner">
            <div class="hero-copy">
                <h1 class="hero-title">Atma Library — Discover knowledge</h1>
                <p class="hero-sub">A modern library with curated collections, seamless borrowing, and supportive staff.
                
                </p>
                <div class="cta">
                    <a href="/member" class="btn btn-primary btn-lg">Explore Books</a>
                    <a href="/petugas/login" class="btn btn-outline-primary btn-lg">For Librarians</a>
                </div>
            </div>
            <div class="hero-media">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1350&q=80" alt="People in library" class="hero-image rounded">
            </div>
        </div>

        <section class="features">
            <div class="feature">
                <h3>Collections</h3>
                <p>Explore categories, curated picks, and newest arrivals — mobile friendly reading lists.</p>
            </div>
            <div class="feature">
                <h3>Easy Borrowing</h3>
                <p>Members can borrow, choose return date, and pay fines online. Flexible return and scheduling system.</p>
            </div>
            <div class="feature">
                <h3>For Staffing</h3>
                <p>Officers manage book inventory, approve loans, and view reports.</p>
            </div>
        </section>

    </main>

    <footer class="footer container">
        <p>&copy; {{ date('Y') }} Atma Library. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>