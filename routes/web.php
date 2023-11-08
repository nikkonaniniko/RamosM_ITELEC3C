<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $users = User::all();
        return view('dashboard', compact('users'));
    })->name('dashboard');
});

Route::get('/all/category', [CategoryController::class, 'index'])->name('AllCat');
Route::post('/add_category',[CategoryController::class, 'create']);
// Route::get('/edit_category/{id}', [CategoryController::class, 'edit']);
// Route::post('/edit_category_confirm/{id}',[CategoryController::class, 'edit_confirm']);
// Route::get('/delete_category/{id}', [CategoryController::class, 'delete']);
