<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * FieldController Admin - kelola data lapangan futsal
 * CRUD: tampilin, tambah, edit, hapus lapangan
 */
class FieldController extends Controller
{
    /**
     * Tampilin semua daftar lapangan
     */
    public function index()
    {
        $fields = Field::all();
        return view('admin.fields.index', compact('fields'));
    }

    /**
     * Form buat tambah lapangan baru
     */
    public function create()
    {
        return view('admin.fields.create');
    }

    /**
     * Simpan lapangan baru ke database
     * Kalo ada upload gambar, disimpan ke storage/uploads/fields
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price_per_hour']);

        // Upload gambar kalo ada
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/fields', 'public');
        }

        Field::create($data);

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    /**
     * Form edit lapangan
     */
    public function edit(Field $field)
    {
        return view('admin.fields.edit', compact('field'));
    }

    /**
     * Update data lapangan
     * Hapus gambar lama kalo ada upload baru
     */
    public function update(Request $request, Field $field)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price_per_hour']);

        // Ganti gambar kalo ada upload baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama dulu
            if ($field->image) {
                Storage::disk('public')->delete($field->image);
            }
            $data['image'] = $request->file('image')->store('uploads/fields', 'public');
        }

        $field->update($data);

        return redirect()->route('admin.fields.index')->with('success', 'Data lapangan berhasil diperbarui.');
    }

    /**
     * Hapus lapangan dari database
     * Gambar juga ikut dihapus dari storage
     */
    public function destroy(Field $field)
    {
        if ($field->image) {
            Storage::disk('public')->delete($field->image);
        }

        $field->delete();

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil dihapus.');
    }
}
