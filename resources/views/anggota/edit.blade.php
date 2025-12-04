@extends('dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Anggota</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('anggota') }}">Anggota</a></li>
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
                            <form action="{{ route('anggota.update', $a->id_anggota) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $a->nama) }}" placeholder="Masukkan Nama">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $a->email) }}" placeholder="Masukkan Email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="kata_sandi">Kata Sandi (kosongkan jika tidak diubah)</label>
                                    <input type="password" class="form-control @error('kata_sandi') is-invalid @enderror" name="kata_sandi" placeholder="Masukkan Kata Sandi Baru">
                                    @error('kata_sandi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="nomor_telepon">Nomor Telepon</label>
                                    <input type="text" class="form-control" name="nomor_telepon" value="{{ old('nomor_telepon', $a->nomor_telepon) }}" placeholder="Masukkan Nomor Telepon">
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" name="alamat" placeholder="Masukkan Alamat">{{ old('alamat', $a->alamat) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="status_keanggotaan">Status Keanggotaan</label>
                                    <select name="status_keanggotaan" class="form-control">
                                        <option value="aktif" {{ old('status_keanggotaan', $a->status_keanggotaan) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status_keanggotaan', $a->status_keanggotaan) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('anggota.index') }}" class="btn btn-warning">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection