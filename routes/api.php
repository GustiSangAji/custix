<?php

use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockinController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Tiket Routes
    Route::middleware('can:event')->group(function () {
        Route::get('tiket', [TiketController::class, 'get']);
        Route::post('tiket', [TiketController::class, 'index']);
        Route::post('tiket/store', [TiketController::class, 'store']);
        Route::apiResource('tiket', TiketController::class)
            ->except(['index', 'store'])
            ->scoped(['tiket' => 'uuid']);

        // Route untuk mengupdate stok tiket
        Route::put('tiket/{id}/stok', [TiketController::class, 'updateStok']);
    });

    Route::prefix('orders')->group(function (){
        Route::post('/', [OrderController::class, 'index']);
        Route::get('/show', [OrderController::class, 'show']);
        Route::get('/{id}', [OrderController::class, 'showById']);
        Route::post('/checkout/{id}', [OrderController::class, 'checkout']);
    });



    // Stockin Routes
    Route::prefix('stockin')->group(function () {
        Route::post('/', [StockinController::class, 'index']);
        Route::post('/store', [StockinController::class, 'store']);
        Route::get('/{stockin}', [StockinController::class, 'show']);
        Route::put('/{stockin}', [StockinController::class, 'update']);
        Route::delete('/{stockin}', [StockinController::class, 'destroy']);
    });
    
    });
    
Route::get('/tickets', [TicketController::class, 'index']); // Untuk semua tiket
Route::get('/tickets/limited', [TicketController::class, 'limited']); // Untuk tiket terbatas di halaman utama
Route::get('/tickets/{id}', [TicketController::class, 'show']); // Untuk detail tiket



Route::post('/order', [CartController::class, 'store']);
Route::get('/order', [CartController::class, 'index']);
Route::get('/order/{id}', [CartController::class, 'show']);


