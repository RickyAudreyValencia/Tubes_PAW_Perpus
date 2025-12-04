@extends('dashboard')

@section('content')
<div class="container">
    <div class="content-header">
        <h1>Laporan</h1>
    </div>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periode</th>
                        <th>Total Peminjaman</th>
                        <th>Total Buku</th>
                        <th>Total Denda</th>
                        <th>Dibuat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporans as $l)
                        <tr>
                            <td>{{ $l->id }}</td>
                            <td>{{ $l->periode }}</td>
                            <td>{{ $l->total_peminjaman }}</td>
                            <td>{{ $l->total_buku_dipinjam }}</td>
                            <td>{{ $l->total_denda }}</td>
                            <td>{{ $l->pembuat->nama ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $laporans->links() }}
    </div>
</div>
@endsection
