<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;


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


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    //return $request->user();
    Route::post('article', [ArticleController::class, 'storeArticle']);
    Route::put('article/{id}', [ArticleController::class, 'updateArticle']);
    Route::get('article', [ArticleController::class, 'listArticle']);
    Route::get('article/{id}', [ArticleController::class, 'articleById']);
    Route::delete('article/{id}', [ArticleController::class, 'deleteArticle']);
    /* Route::post('/encrypt', [EncryptionController ::class, 'encryptData'])->middleware('encrypt.decrypt');
    Route::post('/decrypt', [EncryptionController ::class, 'decryptData'])->middleware('encrypt.decrypt'); */
    
});
Route::middleware(['encrypt.decrypt'])->group(function () {
    Route::post('/encrypt', [EncryptionController ::class, 'encryptData']);
});
