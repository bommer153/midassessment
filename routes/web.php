<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\http\Controllers\LikedDogController;
use App\http\Controllers\ProfileController;
use App\http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['guest']], function () {


    Route::get('/login', [AuthController::class,'login'])->name('login');
    Route::post('/login', [AuthController::class,'elogin'])->name('elogin');
    Route::get('/register', [AuthController::class,'registerPage'])->name('registerPage');
    Route::post('/register', [AuthController::class,'register'])->name('register');


});


Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout',  [AuthController::class,'logout'])->name('logout');
    
    //Posts
    Route::get('/', [LikedDogController::class,'index'])->name('home');  
    Route::post('/dogs/fetch', [LikedDogController::class, 'fetchDogBreeds'])->name('fetchDogBreeds');  
    Route::post('/dogs/select', [LikedDogController::class, 'selectFavoriteDogs'])->name('selectFavoriteDogs');
    //
    Route::get('/profile', [ProfileController::class,'index'])->name('profile');
    Route::put('/profile', [ProfileController::class,'updateProfile'])->name('updateProfile');
    Route::put('/password', [ProfileController::class,'changePassword'])->name('changePassword');

});


