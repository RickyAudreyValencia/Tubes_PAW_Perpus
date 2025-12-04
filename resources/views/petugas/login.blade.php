<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Officer Login — Atma Library</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
<div class="container" style="margin-top:3rem;max-width:420px">
    <h2>Officer Login</h2>
    <div class="alert alert-info">Use seeded admin: <strong>admin@gmail.com</strong> / <strong>admin12345</strong></div>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form action="{{ url('/petugas/login') }}" method="post">
        @csrf
        <div class="mb-3">
            <label>Email</label>
            <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input class="form-control" type="password" name="password" required>
        </div>
        <div style="display:flex;gap:.5rem;align-items:center">
            <button class="btn btn-primary">Login</button>
            <a href="/" class="btn btn-outline-primary">Back</a>
        </div>
    </form>

</div>
</body>
</html>