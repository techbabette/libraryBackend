<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MessageTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::group([
    'prefix' => 'category'
], function ($router){
    Route::get("/", [CategoryController::class, "index"]);
    Route::post('/book', [CategoryController::class, "store"])->name('StoreCategory');
});

Route::group([
    'prefix' => 'messagetype'
], function ($router){
    Route::get("/", [MessageTypeController::class, "index"]);
    Route::post('/', [MessageTypeController::class, "store"])->name("StoreMessageType");
});

Route::group([
    'prefix' => 'book'
], function ($router){
    Route::get("/", [BookController::class, "index"]);
    Route::get('/{id}', [BookController::class, "show"]);
    Route::post('/', [BookController::class, "store"])->name('StoreBook');
});

Route::group([
    'prefix' => 'user'
], function($router){
    Route::get("/", [UserController::class, 'index'])->name('UsersGet');
    Route::get('/assume/{id}', [AuthController::class, 'assumeUser'])->name("AssumeUser");
});

Route::group([
    'prefix' => 'loan'
], function ($router){
    Route::get('/', [LoanController::class, 'index'])->name('LoansGet');
    Route::post('/', [LoanController::class, 'store'])->name('LoanStore');
    Route::patch('/return/{id}', [LoanController::class, 'return'])->name('ReturnLoan');
    Route::patch('/extend/{id}', [LoanController::class, 'extend'])->name('ExtendLoan');
});

Route::group([
    'prefix' => 'link'
], function ($router){
   Route::get('/', [LinkController::class, 'index']);
   Route::post('/', [LinkController::class, "store"])->name('StoreLink');
});

 Route::group([
     'prefix' => 'auth'
 ], function ($router) {
     Route::post('login', [AuthController::class, "login"])->name('login');
     Route::get('logout', [AuthController::class, "logout"])->name('logout');
     Route::get('verify/{id}/{token}', [AuthController::class, 'verifyEmail'])->name('verifyEmail');
     Route::post('refresh', [AuthController::class, "refresh"]);
     Route::post('me', [AuthController::class, "me"]);
     Route::post('register', [AuthController::class, "register"])->name('register');
 });

Route::group([
    'prefix' => 'author'
], function ($router){
    Route::get("/", [AuthorController::class, "index"]);
    Route::post('/', [AuthorController::class, "store"])->name("StoreAuthor");
});
