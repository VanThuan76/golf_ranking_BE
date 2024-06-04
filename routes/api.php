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

Route::group([
    'middleware' => 'api',

], static function ($router) {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout');
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('reset-password', 'Auth\ResetPasswordController@resetPassword');

    Route::get('user/{id}', 'UserController@getById');
    Route::get('userEmail/{email}', 'UserController@getUserByEmail');
    Route::post('check-email-exists', 'UserController@checkEmailExists');

    Route::get('groups', 'GroupController@getList');
    Route::get('groups-by-tournament/{tournament_id}', 'GroupController@getListByTournament');
    Route::get('organisers', 'OrganiserController@getList');
    Route::get('common-code', 'CommonCodeController@getList');

    Route::get('first-get-register-member/{id}', 'FirstRegisterMemberController@getById');
    Route::post('first-register-member', 'FirstRegisterMemberController@firstRegisterMember');
    Route::put('first-update-register-member/{memberId}', 'FirstRegisterMemberController@firstUpdateRegisterMember');
    Route::post('first-get-register-member', 'FirstRegisterMemberController@getByRegisterMember');

    Route::post('register-member', 'MemberController@registerMember');
    Route::put('update-member/{memberId}', 'MemberController@updateMember');
    Route::get('members/{id}', 'MemberController@getById');
    Route::post('members/search', [MemberController::class, 'searchMember']);
    Route::get('nationality-members', 'MemberController@getListNationality');
    Route::post('check-handicap-vga-exists', 'MemberController@checkHandicapVgaExists');
    Route::post('check-vjgr-code-exists', 'MemberController@checkCodeVjgrExists');

    Route::post('tournament-summary', 'TournamentSummaryController@getList');
    Route::post('tournament-detail', 'TournamentDetailController@getList');

    Route::post('tournaments/search', [TournamentController::class, 'searchTournament']);
    Route::get('tournaments/{id}', 'TournamentController@getById');

    Route::get('tournaments-type', 'TournamentTypeController@getList');

    Route::get('category', 'CategoryController@getList');
    Route::post('news/search', 'NewsController@searchNews');
    Route::get('news/{slug}', 'NewsController@getBySlug');
});