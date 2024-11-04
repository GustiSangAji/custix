<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(Setting::first());
    }

    public function update(Request $request)
    {
        if ($request->wantsJson()) {
            $request->validate([
                'app' => 'required',
                'description' => 'required',
                'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'carousel.*' => 'image|mimes:jpeg,png,jpg|max:8192',
            ]);

            $setting = Setting::first();

            // Delete previous images if exist
            $this->deleteImageIfExists($setting->logo);
            $this->deleteImageIfExists($setting->carousel1);
            $this->deleteImageIfExists($setting->carousel2);
            $this->deleteImageIfExists($setting->carousel3);
            $this->deleteImageIfExists($setting->carousel4);
            $this->deleteImageIfExists($setting->carousel5);
            $this->deleteImageIfExists($setting->carousel6);
            $this->deleteImageIfExists($setting->carousel7);
            $this->deleteImageIfExists($setting->carousel8);
            $this->deleteImageIfExists($setting->carousel9);
            $this->deleteImageIfExists($setting->carousel10);

            $data = $request->all();

            // Process single images
            $data['logo'] = $this->storeImage($request->file('logo'), 'setting');

            // Process carousel images
            $carousels = $request->file('carousel', []);
            for ($i = 0; $i < 10; $i++) {
                $data["carousel" . ($i + 1)] = isset($carousels[$i]) ? $this->storeImage($carousels[$i], 'setting') : null;
            }

            $setting->update($data);

            return response()->json([
                'message' => 'Berhasil memperbarui data Konfigurasi Website',
                'data' => $setting
            ]);
        } else {
            abort(404);
        }
    }

    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function storeImage($file, $path)
    {
        return $file ? '/storage/' . $file->store($path, 'public') : null;
    }
}