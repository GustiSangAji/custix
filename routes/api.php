<?php

use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockinController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\TicketWaitingRoomController;
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
    Route::post('register', [AuthController::class, 'register']);
    Route::post('register/get/email/otp', [AuthController::class, 'registerGetEmailOtp']);
    Route::post('register/check/email/otp', [AuthController::class, 'registerCheckEmailOtp']);
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::get('/profile', [ProfileController::class, 'getProfile']);
});
// routes/api.php



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

    Route::middleware(['auth', 'verified', 'json'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });

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

    // Stockin Routes
    Route::prefix('stockin')->group(function () {
        Route::post('/', [StockinController::class, 'index']);
        Route::post('/store', [StockinController::class, 'store']);
        Route::get('/{stockin}', [StockinController::class, 'show']);
        Route::put('/{stockin}', [StockinController::class, 'update']);
        Route::delete('/{stockin}', [StockinController::class, 'destroy']);
    });
});

// Public Routes
Route::get('/tickets', [TicketController::class, 'index']); // Untuk semua tiket
Route::get('/tickets/limited', [TicketController::class, 'limited']); // Untuk tiket terbatas di halaman utama
Route::get('/tickets/{id}', [TicketController::class, 'show']); // Untuk detail tiket
Route::post('/tickets/search', [TicketController::class, 'search']);
Route::get('/tickets/name/{name}', [TicketController::class, 'showByName']);
Route::get('/tickets-by-date', [TicketController::class, 'getTicketsByDate']);


// Laporan Routes
Route::prefix('laporan')->group(function () {
    Route::post('/', [LaporanController::class, 'index']);
    Route::post('/store', [LaporanController::class, 'store']);
    Route::get('api/laporan/{id}', [LaporanController::class, 'show']);// Menampilkan daftar laporan dengan pagination// Menampilkan laporan berdasarkan ID
    Route::get('export/excel', [LaporanController::class, 'export_excel']);
    Route::get('/pdf', [LaporanController::class, 'generatePdf']); // Rute PDF
});


// Order Routes
Route::post('/order', [CartController::class, 'store']);
Route::get('/order', [CartController::class, 'index']);
Route::get('/order-detail/{id}', [CartController::class, 'getOrderDetail']);
Route::get('/order/{id}', [CartController::class, 'show']);
Route::post('/payment/{id}', [CartController::class, 'checkout']);
Route::post('/afterpayment', [CartController::class, 'callback']);
Route::post('/afterpay', [CartController::class, 'afterpayment']);

//Verifikasi Tiket
Route::middleware(['auth'])->group(function () {
    Route::post('/verify-ticket', [CartController::class, 'verifyTicket']);
});

// Waiting Room Status
Route::middleware('auth:api')->group(function () {
    Route::get('/waiting-room-status', [TicketWaitingRoomController::class, 'status']);
    Route::post('/clear-access', [TicketWaitingRoomController::class, 'clearQueue']);
    Route::post('/grant-access', [TicketWaitingRoomController::class, 'grantAccess']);
    Route::post('/terminate-access', [TicketWaitingRoomController::class, 'terminateAccess']);
    Route::post('/remove-access', [CartController::class, 'removeAccess']);
    Route::post('/api/remove-access/{id}', [TicketWaitingRoomController::class, 'terminateAccess']);
});

Route::get('/user/orders', [CartController::class, 'getUserOrders']);
Route::get('/user/orders/{id}', [CartController::class, 'getOrderById']);
Route::post('/save-qr-code', [CartController::class, 'saveQrCode']);


