<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AuthController;


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




Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) 
{
    Route::post('register'                      , [AuthController::class, 'register']);
    Route::post('login'                         , [AuthController::class, 'login']);
    Route::post('logout'                        , [AuthController::class, 'logout']);
    Route::post('refresh'                       , [AuthController::class, 'refresh']);
    Route::post('me'                            , [AuthController::class, 'me']);
});


Route::group(['middleware' => ['JWT']], function () {

    Route::put('/questions/{id}/spam'                   , [QuestionController::class, 'mark_as_spam']);
    Route::post('/questions/search'                      , [QuestionController::class, 'search']);
    Route::ApiResource('/questions'                     , QuestionController::class);
    
    Route::post('/rooms/reply'                          , [RoomController::class, 'reply']);
    Route::ApiResource('/rooms'                         , RoomController::class);

});

