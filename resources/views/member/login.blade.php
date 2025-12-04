<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Login — Atma Library</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
<div class="container" style="max-width:420px;margin-top:3rem">
    <h2>Member Login</h2>
    <form method="POST" action="/member/login">
        @csrf
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
        <div style="display:flex;gap:.5rem;align-items:center"><button class="btn btn-primary">Login</button> <a href="/" class="btn btn-outline-primary">Back</a></div>
    </form>
</div>
</body>
</html>