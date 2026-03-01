@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Kelola Lapangan</h1>
    <a href="{{ route('admin.fields.create') }}" class="btn btn-primary mb-3">Tambah Lapangan Baru</a>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lapangan</th>
                <th>Deskripsi</th>
                <th>Harga per Jam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fields as $field)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $field->name }}</td>
                    <td>{{ $field->description }}</td>
                    <td>Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('admin.fields.edit', $field->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus lapangan ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
