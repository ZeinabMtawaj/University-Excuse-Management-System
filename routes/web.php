<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DeprivationController;
use App\Http\Controllers\AbsenceExcuseController;
use App\Http\Controllers\MedicalExcuseController;
use App\Http\Controllers\CourseApologyExcuseController;
use App\Http\Controllers\SemesterApologyExcuseController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/signup', [UserController::class, 'create'])->middleware('guest');
Route::post('/users', [UserController::class, 'store']);

Route::get('/signin', [UserController::class, 'signin'])->name('login')->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

Route::put('/user/profile/update', [UserController::class, 'update'])->name('user.profile.update');
Route::get('/user/profile/edit', [UserController::class, 'edit'])->name('user.profile.edit')->middleware('auth');


Route::get('/user/dashboard', [UserController::class, 'dashboard'])->middleware('auth')->name('users.dashboard');


Route::post('/users/authenticate', [UserController::class, 'authenticate']);


Route::get('/absenceExcuses/get', [AbsenceExcuseController::class, 'get'])->middleware('auth')->name("absenceExcuses.read");
Route::get('/absenceExcuses/create', [AbsenceExcuseController::class, 'create'])->middleware('auth');
Route::post('/absenceExcuses/store', [AbsenceExcuseController::class, 'store'])->name('absenceExcuses.store');
Route::post('/absenceExcuses/update-status', [AbsenceExcuseController::class, 'updateStatus']);



Route::get('/courseApologyExcuses/get', [CourseApologyExcuseController::class, 'get'])->middleware('auth')->name("courseApologyExcuses.read");
Route::get('/courseApologyExcuses/create', [CourseApologyExcuseController::class, 'create'])->middleware('auth');
Route::post('/courseApologyExcuses/store', [CourseApologyExcuseController::class, 'store'])->name('courseApologyExcuses.store');
Route::post('/courseApologyExcuses/update-status', [CourseApologyExcuseController::class, 'updateStatus']);



Route::get('/semesterApologyExcuses/get', [SemesterApologyExcuseController::class, 'get'])->middleware('auth')->name("semesterApologyExcuses.read");
Route::get('/semesterApologyExcuses/create', [SemesterApologyExcuseController::class, 'create'])->middleware('auth');
Route::post('/semesterApologyExcuses/store', [SemesterApologyExcuseController::class, 'store'])->name('semesterApologyExcuses.store');
Route::post('/semesterApologyExcuses/update-status', [SemesterApologyExcuseController::class, 'updateStatus']);

Route::get('/medicalExcuses/get', [MedicalExcuseController::class, 'get'])->middleware('auth')->name("medicalExcuses.read");
Route::get('/medicalExcuses/create', [MedicalExcuseController::class, 'create'])->middleware('auth');
Route::post('/medicalExcuses/store', [MedicalExcuseController::class, 'store'])->name('medicalExcuses.store');


Route::get('/deprivations/get', [DeprivationController::class, 'get'])->middleware('auth')->name("deprivations.read");
Route::get('/deprivations/create', [DeprivationController::class, 'create'])->middleware('auth');
Route::post('/deprivations/store', [DeprivationController::class, 'store'])->name('deprivations.store');

// routes/web.php
Route::get('/download/{filename}', [FileController::class, 'download'])->name('download');
Route::get('/file/download/{model}/{folder}/{id}', [FileController::class, 'downloadPrivate'])->name('file.download');
// ->middleware('auth')

Route::post('/api/teachers', [UserController::class, 'getTeachersForStudent'])->name('api.teachers');
Route::post('/api/courses', [CourseController::class, 'getCoursesForStudent'])->name('api.courses');


Route::post('/language-switch', [LanguageController::class, 'switchLang'])->name('language.switch');
