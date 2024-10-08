<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_tiket' => 'required|string|max:255|unique:stockins,kode_tiket',
            'jumlah' => 'required|integer|min:1',
            'deskripsi' => 'required|string|max:1000',
            'datetime' => 'required|date',
        ];
    }
}
