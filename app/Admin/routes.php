<?php

use Illuminate\Routing\Router;


Admin::routes();


Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('/tuition-collection', TuitionCollectionController::class);
    $router->resource('/branchs', BranchController::class);
    $router->resource('/employee', EmployeeController::class);
    $router->resource('/business', BusinessController::class);
    $router->resource('/classes', ClassController::class);
    $router->resource('/account_bank', AccountController::class);
    $router->resource('/student', StudentController::class);
    
});
