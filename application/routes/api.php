<?php

use App\Http\Controllers\AccessLevelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LinkPositionController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MessageController;
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
    Route::post('/', [CategoryController::class, "store"])->name('CategoryStore');
    Route::delete('/{id}', [CategoryController::class, 'delete'])->name('CategoryDelete');
    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name("CategoryEdit");
    Route::patch('/update/{id}', [CategoryController::class, 'update'])->name('CategoryUpdate');
    Route::patch('/restore/{id}', [CategoryController::class, 'restore'])->name('CategoryRestore');
});

Route::group([
    'prefix' => 'author'
], function ($router){
    Route::get("/", [AuthorController::class, "index"]);
    Route::post('/', [AuthorController::class, "store"])->name("AuthorStore");
    Route::delete('/{id}', [AuthorController::class, 'delete'])->name('AuthorDelete');
    Route::get('/edit/{id}', [AuthorController::class, 'edit'])->name("AuthorEdit");
    Route::patch('/update/{id}', [AuthorController::class, 'update'])->name('AuthorUpdate');
    Route::patch('/restore/{id}', [AuthorController::class, 'restore'])->name('AuthorRestore');
});

Route::group([
    'prefix' => 'book'
], function ($router){
    Route::get("/", [BookController::class, "index"]);
    Route::get('/{id}', [BookController::class, "show"]);
    Route::get('/edit/{id}', [BookController::class, 'edit'])->name("BookEdit");
    Route::patch('/update/{id}', [BookController::class, 'update'])->name('BookUpdate');
    Route::post('/', [BookController::class, "store"])->name('BookStore');
    Route::delete('/{id}', [BookController::class, "delete"])->name('BookDelete');
    Route::patch('/restore/{id}', [BookController::class, 'restore'])->name('BookRestore');
});

Route::group([
    'prefix' => 'message'
], function ($router){
    Route::get("/", [MessageController::class, "index"])->name("MessagesGet");
    Route::post('/', [MessageController::class, "store"])->name("MessageStore");
    Route::delete('/{id}', [MessageController::class, "delete"])->name('MessageDelete');
});

Route::group([
    'prefix' => 'messagetype'
], function ($router){
    Route::get("/", [MessageTypeController::class, "index"])->name('MessageTypesGet');
    Route::get('/edit/{id}', [MessageTypeController::class, 'edit'])->name("MessageTypeEdit");
    Route::patch('/update/{id}', [MessageTypeController::class, 'update'])->name('MessageTypeUpdate');
    Route::post('/', [MessageTypeController::class, "store"])->name("MessageTypeStore");
    Route::delete('/{id}', [MessageTypeController::class, "delete"])->name('MessageTypeDelete');
});

Route::group([
    "prefix" => 'accesslevel'
], function($router){
    Route::get("/", [AccessLevelController::class, 'index'])->name('AccessLevelsGet');
});

Route::group([
    'prefix' => 'user'
], function($router){
    Route::get("/", [UserController::class, 'index'])->name('UsersGet');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name("UserEdit");
    Route::patch('/update/{id}', [UserController::class, 'update'])->name('UserUpdate');
    Route::get('/assume/{id}', [AuthController::class, 'UserAssume'])->name("UserAssume");
});

Route::group([
    'prefix' => 'loan'
], function ($router){
    Route::get('/', [LoanController::class, 'index'])->name('LoansGet');
    Route::post('/', [LoanController::class, 'store'])->name('LoanStore');
    Route::patch('/return/{id}', [LoanController::class, 'return'])->name('LoanReturn');
    Route::patch('/extend/{id}', [LoanController::class, 'extend'])->name('LoanExtend');
});

Route::group([
    "prefix" => "favorite"
], function($router){
    Route::get("/", [FavoriteController::class, 'index'])->name('FavoritesGet');
    Route::post("/", [FavoriteController::class, 'store'])->name('FavoriteStore');
    Route::delete("/{id}", [FavoriteController::class, 'delete'])->name("FavoriteDelete");
});

Route::group([
    'prefix' => 'link'
], function ($router){
   Route::get('/', [LinkController::class, 'index'])->name("LinksGet");
   Route::get('/edit/{id}', [LinkController::class, 'edit'])->name("LinkEdit");
   Route::patch('/update/{id}', [LinkController::class, 'update'])->name('LinkUpdate');
   Route::get('/me', [LinkController::class, 'me']);
   Route::get('/everyone', [LinkController::class, 'everyone']);
   Route::post('/', [LinkController::class, "store"])->name('LinkStore');
   Route::delete('/{id}', [LinkController::class, "delete"])->name('LinkDelete');
});

Route::group([
    "prefix" => 'linkposition'
], function($router){
    Route::get("/", [LinkPositionController::class, 'index'])->name('LinkPositionsGet');
});

Route::group([
    "prefix" => "log"
], function($router){
   Route::get('/', [LogController::class, 'index'])->name('LogsGet');
});

 Route::group([
     'prefix' => 'auth'
 ], function ($router) {
     Route::post('login', [AuthController::class, "login"])->name('login');
     Route::get('logout', [AuthController::class, "logout"])->name('logout');
     Route::get('verify/{id}/{token}', [AuthController::class, 'verifyEmail'])->name('verifyEmail');
     Route::post('register', [AuthController::class, "register"])->name('register');
 });