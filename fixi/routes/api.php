<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientProfileController;
use App\Http\Controllers\ProviderProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('user')->middleware('auth:sanctum')->group(function () {
        //Verifica se está logado
        Route::get('/me', [UserController::class, 'me'])->name('user.me');
        Route::get('/', [UserController::class, 'getAll'])->name('user.get.all');
        Route::get('/{id}', [UserController::class, 'getById'])->where('id', '[0-9]+')->name('user.get');
        Route::post('/', [UserController::class, 'create'])->name('user.create');
        Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::get('/search', [UserController::class, 'search'])->name('user.search');
    });
    Route::prefix('client')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ClientProfileController::class, 'me'])->name('client.me');
        Route::get('/{id}', [ClientProfileController::class, 'get'])->where('id', '[0-9]+')->name('client.get');
        Route::post('/', [ClientProfileController::class, 'create'])->name('client.create');
        Route::put('/', [ClientProfileController::class, 'update'])->name('client.update');
        Route::delete('/', [ClientProfileController::class, 'delete'])->name('client.delete');
    });
    Route::prefix('provider')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ProviderProfileController::class, 'me'])->name('provider.me');
        Route::get('/search', [ProviderProfileController::class, 'search'])->name('provider.search');
        Route::get('/{id}', [ProviderProfileController::class, 'get'])->where('id', '[0-9]+')->name('provider.get');
        Route::post('/', [ProviderProfileController::class, 'create'])->name('provider.create');
        Route::put('/', [ProviderProfileController::class, 'update'])->name('provider.update');
        Route::delete('/', [ProviderProfileController::class, 'delete'])->name('provider.delete');
    });

    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');//Em construção
    });
});
