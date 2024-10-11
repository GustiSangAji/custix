<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTiketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_tiket' => 'required|string|max:7',
            'name' => 'required|string|max:255',
            'place' => 'required|string|max:255',
            'datetime' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:datetime', 
            'status' => 'required|in:available,unavailable',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'expiry_date.after_or_equal' => 'Masa berlaku harus sama dengan atau setelah tanggal event.',
        ];
    }

}
