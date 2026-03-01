<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::all();
        return view('admin.fields.index', compact('fields'));
    }

    public function create()
    {
        return view('admin.fields.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price_per_hour']);

        if ($request->hasFile('image')) {
            // simpan path relatif saja
            $data['image'] = $request->file('image')->store('uploads/fields', 'public');
        }

        Field::create($data);

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Field $field)
    {
        return view('admin.fields.edit', compact('field'));
    }

    public function update(Request $request, Field $field)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price_per_hour']);

        if ($request->hasFile('image')) {
            if ($field->image) {
                Storage::disk('public')->delete($field->image);
            }

            $data['image'] = $request->file('image')->store('uploads/fields', 'public');
        }

        $field->update($data);

        return redirect()->route('admin.fields.index')->with('success', 'Data lapangan berhasil diperbarui.');
    }

    public function destroy(Field $field)
    {
        if ($field->image) {
            Storage::disk('public')->delete($field->image);
        }

        $field->delete();

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil dihapus.');
    }
}
