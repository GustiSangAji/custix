<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TiketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanTiketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Route
Route::middleware(['json'])->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']); // Menambahkan route untuk register
    Route::post('register/get/email/otp', [AuthController::class, 'registerGetEmailOtp']);
    Route::post('register/check/email/otp', [AuthController::class, 'registerCheckEmailOtp']);
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

// Setting Routes
Route::prefix('setting')->group(function () {
    Route::get('', [SettingController::class, 'index']);
});

// Routes for users and roles
Route::middleware(['auth', 'verified', 'json'])->group(function () {
    Route::prefix('setting')->middleware('can:setting')->group(function () {
        Route::post('', [SettingController::class, 'update']);
    });

    Route::prefix('master')->group(function () {
        Route::middleware('can:master-user')->group(function () {
            Route::get('users', [UserController::class, 'get']);
            Route::post('users', [UserController::class, 'index']);
            Route::post('users/store', [UserController::class, 'store']);
            Route::apiResource('users', UserController::class)
                ->except(['index', 'store'])->scoped(['user' => 'uuid']);
        });

        Route::middleware('can:master-role')->group(function () {
            Route::get('roles', [RoleController::class, 'get'])->withoutMiddleware('can:master-role');
            Route::post('roles', [RoleController::class, 'index']);
            Route::post('roles/store', [RoleController::class, 'store']);
            Route::apiResource('roles', RoleController::class)
                ->except(['index', 'store']);
        });
    });


    // Rute untuk Laporan Tiket
    Route::middleware('can:event')->group(function () {
        // Mendapatkan data laporan tiket (untuk menampilkan semua tiket)
        Route::get('laporan', [LaporanTiketController::class, 'get']);

        // Untuk pagination atau filtering laporan tiket
        Route::post('laporan', [LaporanTiketController::class, 'index']);

        // Menyimpan laporan tiket baru
        Route::post('laporan/store', [LaporanTiketController::class, 'store']);

        // Menggunakan resource controller tapi hanya untuk show dan destroy (dengan UUID)
        Route::apiResource('laporan', LaporanTiketController::class)
            ->except(['index', 'store'])// Hanya rute show dan destroy
            ->scoped(['laporan' => 'uuid']); // Menggunakan UUID sebagai parameter
    });



    // Tiket Routes
    Route::middleware('can:event')->group(function () {
        Route::get('tiket', [TiketController::class, 'get']);
        Route::post('tiket', [TiketController::class, 'index']);
        Route::post('tiket/store', [TiketController::class, 'store']);
        Route::apiResource('tiket', TiketController::class)
            ->except(['index', 'store'])->scoped(['tiket' => 'uuid']); // Menggunakan UUID sebagai parameter
    });

    Route::prefix('order')->group(function (){

    });

});


