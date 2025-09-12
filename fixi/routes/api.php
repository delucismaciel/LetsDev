<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientProfileController;
use App\Http\Controllers\ProviderProfileController;
use App\Http\Controllers\ServiceController;
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
    Route::prefix('provider')->group(function () {
        Route::get('/', [ProviderProfileController::class, 'me'])->middleware('auth:sanctum')->name('provider.me');
        Route::delete('/', [ProviderProfileController::class, 'delete'])->middleware('auth:sanctum')->name('provider.delete');
        //Buscas
        Route::get('/search', [ProviderProfileController::class, 'search'])->name('provider.search');
        Route::get('/{id}', [ProviderProfileController::class, 'get'])->where('id', '[0-9]+')->name('provider.get');
    });
    Route::prefix('service')->middleware('auth:sanctum')->group(function () {
        Route::get('/search', [ServiceController::class, 'search'])->name('service.search');
        Route::get('/', [ServiceController::class, 'get'])->name('service.get');
        Route::delete('/', [ServiceController::class, 'delete'])->name('service.delete');
    });

    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');//Em construção
    });
});
