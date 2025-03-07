<?php

use App\Http\Controllers\Dashboard\TaskController;
use App\Http\Controllers\Dashboard\User\ProfileController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/tasks', TaskController::class);
    Route::put('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::put('/tasks/{task}/mark-in-progress', [TaskController::class, 'markInProgress'])
        ->name('tasks.mark_in_progress');
});

require __DIR__.'/auth.php';
