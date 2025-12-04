@php
// Minimal dashboard placeholder for petugas
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Officer Dashboard — Atma Library</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
<div class="container" style="padding-top:2rem">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <h1>Officer Dashboard</h1>
        <a href="/petugas/logout" class="btn btn-outline-primary">Logout</a>
    </div>

    <section class="mt-4">
        <h3>Overview</h3>
        <p>Here you will see reports and quick actions to manage loans and books.</p>
        <div style="display:flex;gap:1rem">
            <a class="btn btn-primary" href="/petugas/books">Manage Books</a>
            <a class="btn btn-outline-primary" href="/petugas/reports">Reports</a>
        </div>
    </section>

    <section style="margin-top:1.4rem">
        <h4>Recent Loans</h4>
        <table class="table table-bordered" style="width:100%">
            <thead><tr><th>Loan ID</th><th>Member</th><th>Book</th><th>Status</th></tr></thead>
            <tbody>
                <tr><td>123</td><td>John Doe</td><td>Clean Code</td><td><span class="badge bg-success">Borrowed</span></td></tr>
            </tbody>
        </table>
    </section>
</div>
</body>
</html>