<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;

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

Route::get('/register', function () { return view('register'); })->name('register');

Route::get('/', function () { return view('login'); })->name('login');

//User
Route::post('register', [UserController::class, 'register'])->name('RegisterUser');
Route::post('authenticate', [UserController::class, 'authenticate'])->name('authenticateUser');


Route::group(['middleware' => ['jwt.verify']], function() {

    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    //Projects
    Route::get('projects', [ProjectController::class, 'listProjects'])->name('listProject');
    Route::post('registerProject', [ProjectController::class, 'storage'])->name('registerProject');
    Route::post('updateProject', [ProjectController::class, 'update'])->name('updateProject');
    Route::post('deleteProject', [ProjectController::class, 'delete'])->name('deleteProject');

    //User
    Route::get('logout', [UserController::class, 'logout'])->name('logout');

    //Task
    Route::get('task', [TaskController::class, 'listTaks'])->name('listTaks');
    Route::post('registerTask', [TaskController::class, 'storage'])->name('registerTask');
    Route::post('updateTask', [TaskController::class, 'update'])->name('updateTask');
    Route::post('taskForProject', [TaskController::class, 'taskForProject'])->name('taskForProject');
    Route::post('commentTask', [TaskController::class, 'commentTask'])->name('commentTask');
    Route::post('traceability', [TaskController::class, 'listComment'])->name('traceability');
    Route::post('deleteTask', [TaskController::class, 'delete'])->name('deleteTask');

});
