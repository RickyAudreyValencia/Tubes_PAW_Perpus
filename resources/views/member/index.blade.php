<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member — Atma Library</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
<div class="container" style="padding-top:2rem">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <h1>Find Books</h1>
        <div>
            <a href="/member/login" class="btn btn-outline-primary">Login</a>
            <a href="/member/register" class="btn btn-primary">Register</a>
        </div>
    </div>

    <form method="GET" action="/member/search" style="margin-top:1rem">
        <input type="search" name="q" class="form-control" placeholder="Search books or categories">
    </form>

    <section class="mt-4">
        <h3>Featured Books</h3>
        <div style="display:flex;gap:1rem;margin-top:.8rem;flex-wrap:wrap">
            <div style="width:200px"><img class="rounded" src="https://images.unsplash.com/photo-1529655683826-aba9b3e77383?auto=format&fit=crop&w=600&q=60" style="width:100%"><h4>Clean Code</h4></div>
            <div style="width:200px"><img class="rounded" src="https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=600&q=60" style="width:100%"><h4>Design Patterns</h4></div>
        </div>
    </section>

</div>
</body>
</html>