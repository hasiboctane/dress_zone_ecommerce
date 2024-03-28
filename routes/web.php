<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImageController;
use Illuminate\Http\Request;
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

        // Slug
        Route::get('/getSlug', function (Request $request) {
            $slug = '';
            if(!empty($request->title)){
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
        // Temp Image Controller
        Route::post('/upload-temp-image',[TempImageController::class,'create'])->name('temp-image.create');

        // Category Routes
        Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
        Route::get('/category/create',[CategoryController::class,'create'])->name('category.create');
        Route::post('/category',[CategoryController::class,'store'])->name('category.store');
        Route::get('/category/{id}/edit',[CategoryController::class,'edit'])->name('category.edit');
        Route::put('/category/{id}',[CategoryController::class,'update'])->name('category.update');
        Route::delete('/category/{id}',[CategoryController::class,'destroy'])->name('category.destroy');

        // Sub-category Routes
        Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub-categories.index');
        Route::get('/sub-category/create',[SubCategoryController::class,'create'])->name('sub-category.create');
        Route::post('/sub-category',[SubCategoryController::class,'store'])->name('sub-category.store');
        Route::get('/sub-category/{id}/edit',[SubCategoryController::class,'edit'])->name('sub-category.edit');
        Route::put('/sub-category/{id}',[SubCategoryController::class,'update'])->name('sub-category.update');
        Route::delete('/sub-category/{id}',[SubCategoryController::class,'destroy'])->name('sub-category.destroy');



    });
});
