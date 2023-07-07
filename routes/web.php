<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('login', [AuthController::class, 'loginPage'])->name('login');
Route::get('register', [AuthController::class, 'registerPage'])->name('register');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [ProductController::class, 'index']);


Route::middleware(['auth'])->group(function(){
    Route::middleware(['sellerAuth'])->group(function(){
        Route::get('/product/create', [ProductController::class, 'create']);
    });

    Route::get("products/{id}", [ProductController::class, 'show']);
});