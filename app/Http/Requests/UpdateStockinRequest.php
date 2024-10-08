<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_tiket' => 'required|string|max:255|unique:stockins,kode_tiket,' . $this->route('stockin')->id,
            'jumlah' => 'required|integer|min:1',
            'deskripsi' => 'required|string|max:1000',
            'datetime' => 'required|date',
        ];
    }
}
