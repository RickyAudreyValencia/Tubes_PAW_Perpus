<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Petugas</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container" style="max-width:600px;margin-top:2rem">
    <h2>Edit Petugas</h2>
    <form method="POST" action="{{ url('/petugas/update/'.$p->id_petugas) }}">
    @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="nama" class="form-control" value="{{ $p->nama }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $p->email }}" class="form-control" required>
        </div>
        <div style="display:flex;gap:.6rem">
            <button class="btn btn-primary">Save</button>
            <a href="/petugas" class="btn btn-outline-primary">Back</a>
        </div>
    </form>
</div>
</body>
</html>