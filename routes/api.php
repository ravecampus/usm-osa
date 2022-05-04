<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\OrganizationController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\FiledDocController;
use App\Http\Controllers\API\UploadController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    
    Route::put('/profile/pass/{id}', [ProfileController::class, 'userPassword']);
    Route::get('/profile/list/', [ProfileController::class, 'getUsers']);
    Route::put('/profile/users/{id}', [ProfileController::class, 'editUsers']);
    Route::post('/profile/users', [ProfileController::class, 'users']);
    Route::post('/profile/newpass', [ProfileController::class, 'newPassword']);
    Route::post('/profile/check', [ProfileController::class, 'changePassword']);
    Route::resource('/profile', ProfileController::class);
    Route::put('/org/accredit/{id}', [OrganizationController::class,'accredit']);
    Route::get('/org/list/{id}', [OrganizationController::class,'lista']);
    Route::get('/org/individual/{id}', [OrganizationController::class,'getIndOrg']);
    Route::get('/org/restore/{id}', [OrganizationController::class,'restore']);
    Route::resource('/org', OrganizationController::class);
    Route::get('/category/restore/{id}', [CategoryController::class,'restore']);
    Route::resource('/category', CategoryController::class);
    Route::resource('/filed-docs', FiledDocController::class);
    Route::get('/download-file/{id}', [UploadController::class, 'downloadFile']);
    Route::resource('/uploads', UploadController::class);
    Route::get('/report/filter', [ReportController::class,'filter']);
    Route::get('/report/categories', [ReportController::class,'categories']);
    Route::get('/report/org/{id}', [ReportController::class,'org']);
    Route::resource('/report', ReportController::class);
});