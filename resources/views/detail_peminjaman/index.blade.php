@extends('dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Peminjaman</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('detail_peminjaman') }}">Detail Peminjaman</a></li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('detail_peminjaman.create') }}" class="btn btn-md btn-success mb-3">Tambah Detail</a>
                            <div class="table-responsive p-0">
                                <table class="table table-hover text-no-wrap">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID Peminjaman</th>
                                            <th class="text-center">Nama Anggota</th>
                                            <th class="text-center">Item Buku</th>
                                            <th class="text-center">Kode / Judul</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($details as $d)
                                            <tr>
                                                <td class="text-center">{{ $d->peminjaman->id_peminjaman ?? '-' }}</td>
                                                <td class="text-center">{{ $d->peminjaman->anggota->nama ?? '-' }}</td>
                                                <td class="text-center">{{ $d->itemBuku->kode_inventaris ?? '-' }}</td>
                                                <td class="text-center">{{ $d->itemBuku->buku->judul ?? '-' }}</td>
                                                <td class="text-center">
                                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('detail_peminjaman.destroy', [$d->id_peminjaman, $d->id_item_buku]) }}" method="POST">
                                                        <a href="{{ route('detail_peminjaman.edit', [$d->id_peminjaman, $d->id_item_buku]) }}" class="btn btn-sm btn-primary">EDIT</a>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="alert alert-danger">Data detail peminjaman belum tersedia</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $details->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection