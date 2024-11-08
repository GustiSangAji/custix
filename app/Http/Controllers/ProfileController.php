<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function getProfile()
    {
        $user = Auth::user();
        return response()->json([
            'name' => $user->nama,
            'email' => $user->email,
            'phone' => $user->phone,
            'photo' => $user->photo,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $user->nama = $request->input('name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');

        // Handle file upload for profile photo
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('fotoprofil', 'public');
            $user->photo = Storage::url($photoPath);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully!',
            'photo' => $user->photo,
        ], 200);
    }
}
