<?php

use App\Http\Controllers\Api\V1\ArticlesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\Auth;
use App\Http\Controllers\Api\V1\GuardianController;
use App\Http\Controllers\Api\V1\DataSourceController;
use App\Http\Controllers\Api\V1\NewYorkTimesController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UtilityController;

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


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and will be assigned to the "api" middleware group.
| Make something great!
|
*/

Route::post('auth/register', Auth\RegisterController::class);
Route::post('auth/login', Auth\LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/articles', [DataSourceController::class, 'articles']);
    Route::get('articles/top-headlines', [DataSourceController::class, 'topHeadlines']);
    Route::post('/user/preferences', [UserController::class, 'updatePreferences']);

    Route::get('/categories', [UtilityController::class, 'getCategories']);
    Route::get('/sources', [UtilityController::class, 'getSources']);

    Route::get('/user/preferred-categories', [UserController::class, 'getCategories']);
    Route::get('/user/preferred-sources', [UserController::class, 'getSources']);
    Route::get('/user/preferred-authors', [UserController::class, 'getAuthors']);
});
