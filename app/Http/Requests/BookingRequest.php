<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Izinkan semua permintaan
    }

    public function rules()
    {
        return [
            'field_id' => 'required|exists:fields,id',
            'booking_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:1',
        ];
    }
}