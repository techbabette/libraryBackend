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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/category", [CategoryController::class, "index"]);

Route::get("/author", [AuthorController::class, "index"]);

Route::get("/messagetype", [MessageTypeController::class, "index"]);

Route::get("/book", [BookController::class, "index"]);

Route::get("/user", [UserController::class, 'index']);

Route::group([
    'prefix' => 'loan'
], function ($router){
    Route::get('/', [LoanController::class, 'index'])->name('GetLoans');
    Route::patch('/return/{id}', [LoanController::class, 'return'])->name('ReturnLoan');
    Route::patch('/extend/{id}', [LoanController::class, 'extend'])->name('ExtendLoan');
});

Route::group([
    'prefix' => 'link'
], function ($router){
   Route::get('/', [LinkController::class, 'index']);
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
    'prefix' => 'admin'
], function ($router) {
    Route::post('/book', [BookController::class, "store"])->name('StoreBook');
    Route::post('/link', [LinkController::class, "store"])->name('StoreLink');
    Route::post('/category', [CategoryController::class, "store"])->name("StoreCategory");
    Route::post('/author', [AuthorController::class, "store"])->name("StoreAuthor");
    Route::post('/messagetype', [MessageTypeController::class, "store"])->name("StoreMessageType");

    Route::get('/user/assume/{id}', [AuthController::class, 'assumeUser'])->name("AssumeUser");
});
