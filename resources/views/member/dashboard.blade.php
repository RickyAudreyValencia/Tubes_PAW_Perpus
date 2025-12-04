<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Dashboard — Atma Library</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
<div class="container" style="padding-top:2rem">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <h1>Welcome, {{ $name ?? 'Member' }}</h1>
        <a href="/member/logout" class="btn btn-outline-primary">Logout</a>
    </div>

    <section class="mt-4">
        <h3>Your Loans</h3>
        <ul>
            <li>No active loans — borrow something today!</li>
        </ul>
    </section>

</div>
</body>
</html>