<?php

use App\Http\Controllers\HistoryListController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingListController;

// Middleware untuk memeriksa peran
Route::aliasMiddleware('role', RoleMiddleware::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function (Request $request) {
    return response()->json("Test OK", 200);
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);
Route::post('/logout', [RegisterController::class, 'logout'])->middleware('auth:sanctum');

// Route untuk admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return response()->json("Admin Access", 200);
    });

    // Route CRUD Room khusus admin
    Route::post('rooms/create', [RoomController::class, 'store']); // Menambah room baru
    Route::get('rooms/{roomnumber}', [RoomController::class, 'show']); // Menampilkan detail room berdasarkan roomnumber
    Route::post('rooms/update/{roomnumber}', [RoomController::class, 'update']); // Mengupdate room
    Route::delete('rooms/delete/{roomnumber}', [RoomController::class, 'destroy']); // Menghapus room

    // Route CRUD BookingList khusus admin
    Route::get('/bookings/admin/all', [BookingListController::class, 'adminIndex']); // Menampilkan semua booking
    Route::get('/bookings/admin/{bookingList}', [BookingListController::class, 'show'])->middleware('auth:sanctum', 'role:admin'); // Menampilkan detail booking
    Route::delete('/bookings/admin/delete/{bookingList}', [BookingListController::class, 'destroy'])->middleware('auth:sanctum', 'role:admin'); // Menghapus booking

    // Route untuk History List Admin 
    Route::get('/history/admin', [HistoryListController::class, 'adminIndex']);
    Route::post('/bookings/search', [BookingListController::class, 'search']);
    Route::post('/history/admin/search', [HistoryListController::class, 'search']);
});

// Route untuk user umum
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::get('/user-area', function () {
        return response()->json("User Access", 200);
    });

    // Route untuk user umum agar bisa membuat dan melihat booking milik mereka sendiri
    
    Route::post('/bookings/create', [BookingListController::class, 'store']); // Membuat booking baru
    Route::get('/bookings/user/all', [BookingListController::class, 'userIndex']); // Menampilkan booking user sendiri
    Route::get('/bookings/user/{bookingList}', [BookingListController::class, 'show'])->middleware('auth:sanctum'); // Menampilkan detail booking user sendiri
    Route::post('/bookings/create/{roomnumber}', [BookingListController::class, 'storeByRoomNumber']);

    // Route untuk History List User
    Route::get('/history/user', [HistoryListController::class, 'userIndex']);
    Route::get('/history/user/{id}', [HistoryListController::class, 'show']);
});

// Route umum untuk mengakses semua room (baik untuk admin maupun user)
Route::middleware('auth:sanctum')->get('rooms', [RoomController::class, 'index']); // Menampilkan semua room