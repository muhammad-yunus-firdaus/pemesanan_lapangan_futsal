<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * FieldController User - tampilkan daftar lapangan
 * User bisa lihat lapangan yang tersedia buat dibooking
 */
class FieldController extends Controller
{
    // Ambil semua lapangan, pake cache biar ga query terus
    public function index()
    {
        $fields = Cache::remember('user_fields_list', 600, function () {
            return Field::all();
        });

        return view('user.fields.index', compact('fields'));
    }
}