<?php

namespace App\Http\Controllers\User;

use App\Models\Field;
use App\Http\Controllers\Controller;

class UserFieldController extends Controller
{
    public function index()
    {
        // Mengambil data lapangan
        $fields = Field::all();
        return view('user.fields.index', compact('fields')); // Mengirim data ke view
    }
}
