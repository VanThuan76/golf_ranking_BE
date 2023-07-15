<?php

use Illuminate\Routing\Router;


Admin::routes();

Route::resource('admin/auth/users', \App\Admin\Controllers\CustomUserController::class)->middleware(config('admin.route.middleware'));

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('/core/business', Core_BusinessController::class);
    $router->resource('/core/branch', Core_BranchController::class);
    $router->resource('/core/bankaccount', Core_AccountController::class);
    $router->resource('/core/common', Core_CommonCodeController::class);
    $router->resource('/edu/tuitionCollection', Edu_TuitionCollectionController::class);
    $router->resource('/edu/employee', Edu_EmployeeController::class);
    $router->resource('/edu/classes', Edu_ClassController::class);
    $router->resource('/edu/student', Edu_StudentController::class);
    $router->resource('/edu/teacher', Edu_TeacherController::class);
    
});
