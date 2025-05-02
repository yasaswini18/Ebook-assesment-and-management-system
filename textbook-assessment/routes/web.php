<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Book routes
Route::middleware(['auth'])->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->middleware('role:admin,evaluator')->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->middleware('role:admin,evaluator')->name('books.store');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->middleware('role:admin')->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->middleware('role:admin')->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->middleware('role:admin')->name('books.destroy');
});

// Criteria routes
Route::middleware(['auth'])->group(function () {
    Route::get('/criteria', [CriteriaController::class, 'index'])->name('criteria.index');
    Route::get('/criteria/create', [CriteriaController::class, 'create'])->middleware('role:admin')->name('criteria.create');
    Route::post('/criteria', [CriteriaController::class, 'store'])->middleware('role:admin')->name('criteria.store');
    Route::get('/criteria/{criteria}', [CriteriaController::class, 'show'])->name('criteria.show');
    Route::get('/criteria/{criteria}/edit', [CriteriaController::class, 'edit'])->middleware('role:admin')->name('criteria.edit');
    Route::put('/criteria/{criteria}', [CriteriaController::class, 'update'])->middleware('role:admin')->name('criteria.update');
    Route::delete('/criteria/{criteria}', [CriteriaController::class, 'destroy'])->middleware('role:admin')->name('criteria.destroy');
});

// Evaluation routes
Route::middleware(['auth'])->group(function () {
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluations/create/{book?}', [EvaluationController::class, 'create'])->middleware('role:admin,evaluator')->name('evaluations.create');
    Route::post('/evaluations', [EvaluationController::class, 'store'])->middleware('role:admin,evaluator')->name('evaluations.store');
    Route::get('/evaluations/{evaluation}', [EvaluationController::class, 'show'])->name('evaluations.show');
    Route::get('/evaluations/{evaluation}/edit', [EvaluationController::class, 'edit'])->middleware('role:admin,evaluator')->name('evaluations.edit');
    Route::put('/evaluations/{evaluation}', [EvaluationController::class, 'update'])->middleware('role:admin,evaluator')->name('evaluations.update');
    Route::delete('/evaluations/{evaluation}', [EvaluationController::class, 'destroy'])->middleware('role:admin')->name('evaluations.destroy');
});

// Report routes
Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/book/{book}', [ReportController::class, 'bookReport'])->name('reports.book');
});

require __DIR__.'/auth.php';
