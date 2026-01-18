<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;

// Redirect root based on auth (optional)
Route::get('/', function () {
    return redirect('/login');
});

// Auth routes (login/register)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    auth()->guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Admin routes (require authentication middleware - add later)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    // Users routes
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');
    Route::get('/users/create', function () {
        return view('admin.users.create');
    })->name('users.create');
    Route::get('/users/{id}/edit', function ($id) {
        return view('admin.users.edit', ['id' => $id]);
    })->name('users.edit');

    // Books routes
    Route::get('/books', function () {
        return view('admin.books.index');
    })->name('books.index');
    Route::get('/books/create', function () {
        return view('admin.books.create');
    })->name('books.create');
    Route::get('/books/{id}', function ($id) {
        return view('admin.books.show', ['id' => $id]);
    })->name('books.show');
    Route::get('/books/{id}/edit', function ($id) {
        return view('admin.books.edit', ['id' => $id]);
    })->name('books.edit');
    Route::get('/books/{id}/novel-structure', function ($id) {
        return view('admin.books.novel-structure', ['id' => $id]);
    })->name('books.novel-structure');

    // Contents routes
    Route::get('/contents', function () {
        return view('admin.contents.index');
    })->name('contents.index');
    Route::get('/contents/create', function () {
        return view('admin.contents.create');
    })->name('contents.create');
    Route::get('/contents/{id}/edit', function ($id) {
        return view('admin.contents.edit', ['id' => $id]);
    })->name('contents.edit');
    Route::get('/contents/{id}', function ($id) {
        return view('admin.contents.show', ['id' => $id]);
    })->name('contents.show');

    // Transactions routes
    Route::get('/transactions', function () {
        return view('admin.transactions.index');
    })->name('transactions.index');
});

// Member routes
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/library', [MemberController::class, 'library'])->name('library');
    Route::get('/read/{contentId}', [MemberController::class, 'read'])->name('read');
    Route::get('/history', [MemberController::class, 'history'])->name('history');
    Route::get('/profile', [MemberController::class, 'profile'])->name('profile');
});

// Writer routes
Route::prefix('writer')->name('writer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('writer.dashboard');
    })->name('dashboard');

    Route::get('/messages', function () {
        return view('writer.messages');
    })->name('messages');

    Route::get('/profile', function () {
        return view('writer.profile');
    })->name('profile');
});

// Legacy resource routes (can be removed if using API)
Route::resource('users', UserController::class);
Route::resource('books', BookController::class);
