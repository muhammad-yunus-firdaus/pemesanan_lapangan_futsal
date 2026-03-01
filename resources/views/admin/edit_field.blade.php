@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Data Lapangan</h1>

    <form action="{{ route('admin.fields.update', $field->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama Lapangan</label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                class="form-control" 
                value="{{ $field->name }}" 
                required 
                placeholder="Masukkan nama lapangan">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea 
                name="description" 
                id="description" 
                class="form-control" 
                rows="3" 
                required 
                placeholder="Masukkan deskripsi singkat lapangan">{{ $field->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="price_per_hour" class="form-label">Harga per Jam (Rp)</label>
            <input 
                type="number" 
                name="price_per_hour" 
                id="price_per_hour" 
                class="form-control" 
                value="{{ $field->price_per_hour }}" 
                required 
                placeholder="Contoh: 50000">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('admin.fields.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
