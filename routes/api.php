<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public dashboard routes (for admin panel without authentication)
Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
Route::get('/dashboard/revenue', [DashboardController::class, 'revenue']);
Route::get('/dashboard/book-status', [DashboardController::class, 'bookStatus']);
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::get('/transactions', [TransactionController::class, 'index']);
Route::get('/transactions/{id}', [TransactionController::class, 'show']);
Route::get('/alerts', [DashboardController::class, 'alerts']);
Route::get('/messages', [DashboardController::class, 'messages']);
Route::get('/contents', [ContentController::class, 'index']);
Route::get('/contents/{id}', [ContentController::class, 'show']);
Route::get('/contents/{content}/preview', [ContentController::class, 'preview']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/revenue', [DashboardController::class, 'revenue']);
    Route::get('/dashboard/book-status', [DashboardController::class, 'bookStatus']);
    Route::post('/dashboard/report', [DashboardController::class, 'generateReport']);

    // Users
    Route::apiResource('users', UserController::class);
    Route::get('/users/{user}/borrow-history', [UserController::class, 'borrowHistory']);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus']);

    // Books
    Route::apiResource('books', BookController::class);
    Route::get('/books/filter/stock', [BookController::class, 'filterByStock']);
    Route::get('/books/filter/genre', [BookController::class, 'filterByGenre']);
    Route::post('/books/{book}/upload-cover', [BookController::class, 'uploadCover']);

    // Contents
    Route::apiResource('contents', ContentController::class);
    Route::post('/contents/{content}/upload-word', [ContentController::class, 'uploadWordFile']);
    Route::get('/contents/{content}/preview', [ContentController::class, 'preview']);
    Route::post('/contents/{content}/link-book', [ContentController::class, 'linkToBook']);

    // Transactions
    Route::apiResource('transactions', TransactionController::class);
    Route::get('/transactions/{transaction}/status', [TransactionController::class, 'getStatus']);
    Route::get('/transactions/{transaction}/deadline', [TransactionController::class, 'getDeadline']);
    Route::post('/transactions/{transaction}/return', [TransactionController::class, 'returnBook']);

    // Payments
    Route::apiResource('payments', PaymentController::class);
    Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve']);
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject']);

    // Search
    Route::get('/search', [DashboardController::class, 'search']);

    // Alerts
    Route::get('/alerts', [DashboardController::class, 'alerts']);

    // Messages
    Route::get('/messages', [DashboardController::class, 'messages']);
    Route::post('/messages/{message}/read', [DashboardController::class, 'markAsRead']);
});

// Member routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/member/books', [BookController::class, 'memberIndex']);
    Route::get('/member/books/{book}', [BookController::class, 'memberShow']);
    Route::post('/member/books/{book}/borrow', [BookController::class, 'borrowBook']);
    Route::post('/member/books/{book}/borrow-free', [BookController::class, 'borrowFreeBook']);
    Route::get('/member/history', [TransactionController::class, 'memberHistory']);
    Route::get('/member/profile', [DashboardController::class, 'memberProfile']);
    Route::put('/member/profile', [DashboardController::class, 'updateProfile']);
});

// Writer routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/writer/books', [BookController::class, 'writerIndex']);
    Route::get('/writer/contents', [ContentController::class, 'writerContents']);
    Route::post('/writer/contents/upload', [ContentController::class, 'writerUpload']);
    Route::post('/writer/books', [BookController::class, 'writerStoreBook']);
    Route::get('/writer/messages', [DashboardController::class, 'writerMessages']);
    Route::post('/writer/messages', [DashboardController::class, 'writerSendMessage']);
});
