@php
// Minimal index listing petugas - placeholder
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Petugas List</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
<div class="container">
    <h1>Petugas</h1>
    <a href="{{ url('/petugas/create') }}" class="btn btn-primary">Create</a>
    <table class="table table-bordered" style="margin-top:1rem">
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr></thead>
        <tbody>
        @foreach ($petugas as $p)
            <tr>
                <td>{{ $p->id_petugas }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->email }}</td>
                <td>
                    <a href="{{ url('/petugas/edit/'.$p->id_petugas) }}" class="btn btn-outline-primary">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $petugas->links() }}

</div>
</body>
</html>