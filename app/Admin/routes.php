<?php

use Encore\Admin\Facades\Admin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->get('/help', 'HelpController@index');
    $router->resource('/category', CategoryController::class);
    $router->resource('/news', NewsController::class);
    $router->resource('/common-code', CommonCodeController::class);
    $router->resource('/group', GroupController::class);
    $router->resource('/member', MemberController::class);
    $router->resource('/organiser', OrganiserController::class);
    $router->resource('/tournament', TournamentController::class);
    $router->resource('/tournament-type', TournamentTypeController::class);
    $router->resource('/tournament-group', TournamentGroupController::class);
    $router->resource('/tournament-detail', TournamentDetailController::class);
    $router->resource('/tournament-summary', TournamentSummaryController::class);
});
