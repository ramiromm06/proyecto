<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projects', ProjectController::class);

    Route::post('projects/{project}/members', [MemberController::class, 'store'])->name('projects.members.store');
    Route::patch('projects/{project}/members/{user}', [MemberController::class, 'update'])->name('projects.members.update');
    Route::delete('projects/{project}/members/{user}', [MemberController::class, 'destroy'])->name('projects.members.destroy');

    Route::resource('projects.tasks', TaskController::class)->except(['index']);

    Route::patch('tasks/{task}/status', [TaskController::class, 'status'])->name('tasks.status');
    Route::patch('tasks/{task}/assign', [TaskController::class, 'assign'])->name('tasks.assign');

    Route::post('tasks/{task}/comments', [CommentController::class, 'store'])->name('tasks.comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/roles', [AdminUserController::class, 'assignRole'])->name('users.assignRole');
    });
});

require __DIR__.'/auth.php';
