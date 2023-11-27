<?php

use Illuminate\Routing\Router;
use App\Http\Controllers\UserController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    // $router->post('/api/courses', [CourseController::class, 'getCoursesForStudent'])->name('api.courses');

    // $router->post('/api/teachers', [UserController::class, 'getTeachersForStudent'])->name('api.teachers');

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('faculties', FacultyController::class);
    $router->resource('courses', CourseController::class);
    $router->resource('absence-excuses', AbsenceExcuseController::class);
    $router->resource('course-apology-excuses', CourseApologyExcuseController::class);
    $router->resource('semester-apology-excuses', SemesterApologyExcuseController::class);
    $router->resource('medical-excuses', MedicalExcuseController::class);
    $router->resource('deprivations', DeprivationController::class);




















});
