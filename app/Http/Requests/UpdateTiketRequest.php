<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTiketRequest extends FormRequest
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
            'name' => 'required|min:3|max:255',
            'place' => 'required|string|max:255',
            'datetime' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:datetime', 
            'status' => ['required', Rule::in(['available', 'unavailable'])],
            'quantity' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string|max:100000000000', // Menambahkan validasi untuk deskripsi
            'banner' => 'nullable|image|max:2048', // Menambahkan validasi untuk banner
        ];
    }

    public function messages(): array
    {
        return [
            'expiry_date.after_or_equal' => 'Masa berlaku harus sama dengan atau setelah tanggal event.',
   
        ];
    }
}
