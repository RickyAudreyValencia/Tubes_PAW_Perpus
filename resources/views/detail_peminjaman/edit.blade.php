@extends('dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Detail Peminjaman</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('detail_peminjaman') }}">Detail Peminjaman</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <form action="{{ route('detail_peminjaman.update', [$detail->id_peminjaman, $detail->id_item_buku]) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="id_peminjaman">Peminjaman</label>
                                    <select class="form-control @error('id_peminjaman') is-invalid @enderror" name="id_peminjaman">
                                        <option value="">Pilih Peminjaman</option>
                                        @foreach($peminjamans as $p)
                                            <option value="{{ $p->id_peminjaman }}" {{ (old('id_peminjaman', $detail->id_peminjaman) == $p->id_peminjaman) ? 'selected' : '' }}>
                                                {{ $p->id_peminjaman }} - {{ $p->anggota->nama ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_peminjaman')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="id_item_buku">Item Buku</label>
                                    <select class="form-control @error('id_item_buku') is-invalid @enderror" name="id_item_buku">
                                        <option value="">Pilih Item Buku</option>
                                        @foreach($items as $it)
                                            <option value="{{ $it->id_item_buku }}" {{ (old('id_item_buku', $detail->id_item_buku) == $it->id_item_buku) ? 'selected' : '' }}>
                                                {{ $it->kode_inventaris }} - {{ $it->buku->judul ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_item_buku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('detail_peminjaman.index') }}" class="btn btn-warning">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
