<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FieldController extends Controller
{
    public function index()
    {
        // Cache daftar lapangan selama 10 menit
        // Data lapangan jarang berubah, jadi aman di-cache
        $fields = Cache::remember('user_fields_list', 600, function () {
            return Field::all();
        });

        return view('user.fields.index', compact('fields'));
    }
}