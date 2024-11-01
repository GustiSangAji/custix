<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Jika ada gambar yang di-upload
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            // Simpan foto baru
            $path = $request->file('photo')->store('profiles', 'public');
            $validatedData['photo'] = $path;
        }

        // Perbarui detail pengguna
        $user->update($validatedData);

        // Mengembalikan response
        return response()->json([
            'message' => 'Profile updated successfully!',
            'photo' => $validatedData['photo'] ?? $user->photo, // Kembalikan foto baru atau foto lama
        ]);
    }
}
