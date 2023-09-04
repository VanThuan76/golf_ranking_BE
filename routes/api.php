<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TournamentController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::get('groups', 'GroupController@getList');
Route::get('organisers', 'OrganiserController@getList');
Route::post('members', [MemberController::class, 'getList']);
Route::get('members/{id}', 'MemberController@getById');
Route::post('tournaments', [TournamentController::class, 'getList']);
Route::get('tournaments/{id}', 'TournamentController@getById');

Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');






