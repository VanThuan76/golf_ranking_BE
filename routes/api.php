<?php

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


Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');
Route::post('register', 'Auth\RegisterController@register');
Route::post('reset-password', 'Auth\ResetPasswordController@reset');

Route::get('user/{id}', 'UserController@getById');
Route::get('groups', 'GroupController@getList');
Route::get('groups-by-tournament/{tournament_id}', 'GroupController@getListByTournament');
Route::get('organisers', 'OrganiserController@getList');
Route::get('common-code', 'CommonCodeController@getList');

Route::post('register-member', 'MemberController@registerMember');
Route::get('members/{id}', 'MemberController@getById');
Route::post('members/search', [MemberController::class, 'searchMember']);

Route::post('tournament-summary', 'TournamentSummaryController@getList');
Route::post('tournament-detail', 'TournamentDetailController@getList');

Route::post('tournaments/search', [TournamentController::class, 'searchTournament']);
Route::get('tournaments/{id}', 'TournamentController@getById');

Route::get('tournaments-type', 'TournamentTypeController@getList');

Route::get('auth/facebook', 'SocialController@redirect');
Route::get('auth/facebook/callback', 'SocialController@loginCallback');







