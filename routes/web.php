<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\SchoolYearController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function() { return view('layout');});

Route::get('/login', [LoginController::class, 'loginView'])->name('login');

Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/logout', [LoginController::class, 'logOut'])->name('logout');

Route::middleware(['LecturerAuthenticate'])->group(function () {
    Route::prefix('/index')->group(function () {
        Route::get('/', [CourseController::class, 'init'])->name('index');
        Route::post("/", [AttendanceController::class, 'create'])->name('create');
        Route::post("/course", [StudentController::class, "StudentList"])->name('course_attendance');
        Route::get('/lesson/{id}', [StudentController::class, 'getLessonAttendanceList'])->name('get_lesson');
    });
});

Route::middleware(['AdminAuthenticate'])->group(function () {
    Route::get('/admin', function () {
        return view('dashboard');
    })->name('admin');

    Route::prefix('/course')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('course');
        Route::get('/new', [CourseController::class, 'create'])->name('new_course');
        Route::post('/new', [CourseController::class, 'store'])->name('store_course');
        Route::get('/{id}', [CourseController::class, 'detail'])->name('course_detail');
        Route::get('/{id}/edit', [CourseController::class, 'edit'])->name('edit_course');
        Route::post('/{id}', [CourseController::class, 'updates'])->name('update_course');
        Route::get('/{id}/delete', [CourseController::class, 'delete'])->name('delete_course');
    });

    Route::prefix('/class')->group(function () {
        Route::get('/', [ClassController::class, 'index'])->name('class');
        Route::get('/new', [ClassController::class, 'create'])->name('new_class');
        Route::post('/new', [ClassController::class, 'store'])->name('store_class');
        Route::get('/{id}', [ClassController::class, 'detail'])->name('class_detail');
        Route::get('/{id}/edit', [ClassController::class, 'edit'])->name('edit_class');
        Route::post('/{id}', [ClassController::class, 'updates'])->name('update_class');
        Route::get('/{id}/delete', [ClassController::class, 'delete'])->name('delete_class');
    });

    Route::prefix('/subject')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('subject');
        Route::get('/new', [SubjectController::class, 'create'])->name('new_subject');
        Route::post('/new', [SubjectController::class, 'store'])->name('store_subject');
        Route::get('/{id}', [SubjectController::class, 'detail'])->name('subject_detail');
        Route::get('/{id}/edit', [SubjectController::class, 'edit'])->name('edit_subject');
        Route::post('/{id}', [SubjectController::class, 'updates'])->name('update_subject');
        Route::get('/{id}/delete', [SubjectController::class, 'delete'])->name('delete_subject');
    });

    Route::prefix('/major')->group(function () {
        Route::get('/', [MajorController::class, 'index'])->name('major');
        Route::get('/new', [MajorController::class, 'create'])->name('new_major');
        Route::post('/new', [MajorController::class, 'store'])->name('store_major');
        Route::get('/{id}', [MajorController::class, 'detail'])->name('major_detail');
        Route::get('/{id}/edit', [MajorController::class, 'edit'])->name('edit_major');
        Route::post('/{id}', [MajorController::class, 'updates'])->name('update_major');
        Route::get('/{id}/delete', [MajorController::class, 'delete'])->name('delete_major');
    });

    Route::prefix('/schoolyear')->group(function () {
        Route::get('/', [SchoolYearController::class, 'index'])->name('schoolyear');
        Route::get('/new', [SchoolYearController::class, 'create'])->name('new_schoolyear');
        Route::post('/new', [SchoolYearController::class, 'store'])->name('store_schoolyear');
        Route::get('/{id}', [SchoolYearController::class, 'detail'])->name('schoolyear_detail');
        Route::get('/{id}/edit', [SchoolYearController::class, 'edit'])->name('edit_schoolyear');
        Route::post('/{id}', [SchoolYearController::class, 'updates'])->name('update_schoolyear');
        Route::get('/{id}/delete', [SchoolYearController::class, 'delete'])->name('delete_schoolyear');
    });
});