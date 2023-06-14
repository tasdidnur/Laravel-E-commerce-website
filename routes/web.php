<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboardcontroller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// dashboard routes
Route::get('/dashboard',[Dashboardcontroller::class, 'index']);
// dashboard routes

// category routes
Route::controller(CategoryController::class)->group(function(){
    Route::get('/dashboard/category','index');
    Route::get('/dashboard/category/add','add');
    Route::get('/dashboard/category/edit/{slug}','edit');
    Route::get('/dashboard/category/trashed','trashed');
    Route::post('/dashboard/category/submit','submit');
    Route::post('/dashboard/category/update','update');
    Route::post('/dashboard/category/delete','softDelete');
    Route::post('/dashboard/category/restore','restore');
    Route::post('/dashboard/category/force_delete','force_delete');
    Route::post('/dashboard/category/mark_delete','markSoftDelete');
    Route::post('/dashboard/category/mark_force_delete','markDelete');
});
// category routes

// user routes
Route::controller(UserController::class)->group(function(){
     Route::get('/dashboard/users','index');
});
// user routes

// default routes
require __DIR__.'/auth.php';
