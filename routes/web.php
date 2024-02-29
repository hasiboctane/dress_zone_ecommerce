<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\DashboardController;
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

Route::get('/', function () {
    return view('welcome');
});

;
Route::prefix('admin')->group(function(){
    Route::middleware(['admin.guest'])->group(function(){
        Route::get('/login', [AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class,'authenticate'])->name('admin.authenticate');
    });
    Route::middleware(['admin.auth'])->group(function(){
        Route::get('/dashboard', [DashboardController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[DashboardController::class,'logout'])->name('admin.logout');
    });
});
