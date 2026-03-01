@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Kelola Data Lapangan</h1>

    <a href="{{ route('admin.fields.create') }}" class="btn btn-success mb-3">
        + Tambah Lapangan
    </a>

    <table class="table table-bordered align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Lapangan</th>
                <th>Deskripsi</th>
                <th>Harga per Jam (Rp)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fields as $field)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $field->name }}</td>
                    <td>{{ $field->description }}</td>
                    <td>Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.fields.edit', $field->id) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>
                            <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">
                        Belum ada data lapangan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
