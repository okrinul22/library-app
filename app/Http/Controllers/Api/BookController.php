<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Content;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Filter by genre
        if ($request->has('genre')) {
            $query->where('genre', $request->genre);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', '=', 0);
            }
        }

        // Filter free/paid
        if ($request->has('is_free')) {
            $query->where('is_free', $request->boolean('is_free'));
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        $books = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'genre' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required_unless:is_free,true|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_free' => 'boolean',
            'published_year' => 'required|integer|min:1900|max:2099'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'genre' => $request->genre,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'published_year' => $request->published_year,
            'is_free' => $request->is_free ?? false,
            'cover_image' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dibuat',
            'data' => $book
        ], 201);
    }

    /**
     * Display the specified book.
     */
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'isbn' => 'sometimes|required|string|unique:books,isbn,' . $id,
            'genre' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'nullable|required_unless:is_free,true|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'is_free' => 'boolean',
            'published_year' => 'sometimes|required|integer|min:1900|max:2099'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $request->only(['title', 'author', 'isbn', 'genre', 'description', 'price', 'stock', 'published_year', 'is_free']);

        if ($request->has('is_free')) {
            $data['is_free'] = $request->boolean('is_free');
        }

        $book->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diupdate',
            'data' => $book->fresh()
        ]);
    }

    /**
     * Remove the specified book.
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        // Delete cover image if exists
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ]);
    }

    /**
     * Upload book cover
     */
    public function uploadCover(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        try {
            // Delete old cover if exists
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }

            // Store new cover
            $path = $request->file('cover')->store('book-covers', 'public');
            $book->update(['cover_image' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Cover buku berhasil diupload',
                'data' => $book->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload cover: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter books by stock
     */
    public function filterByStock(Request $request)
    {
        $query = Book::query();

        if ($request->has('status')) {
            if ($request->status === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->status === 'out_of_stock') {
                $query->where('stock', '=', 0);
            }
        }

        $books = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * Filter books by genre
     */
    public function filterByGenre(Request $request)
    {
        $books = Book::where('genre', $request->genre)
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * Member index - show only available books
     */
    public function memberIndex(Request $request)
    {
        $query = Book::where('stock', '>', 0)->with('content');

        // Search by title or author
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Filter by genre
        if ($request->has('genre') && $request->genre) {
            $query->where('genre', $request->genre);
        }

        // Filter free only
        if ($request->has('is_free')) {
            $query->where('is_free', $request->boolean('is_free'));
        }

        // Filter available only (stock > 0)
        if ($request->has('available') && $request->boolean('available')) {
            $query->where('stock', '>', 0);
        }

        $books = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 12);

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * Member show - view book details
     */
    public function memberShow($id)
    {
        $book = Book::with('content')->find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    /**
     * Borrow book (with payment proof upload)
     */
    public function borrowBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $book = Book::find($request->book_id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        if ($book->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Buku sedang tidak tersedia'
            ], 400);
        }

        $user = $request->user();

        // Check if user already has an active transaction for this book
        $existingTransaction = \App\Models\Transaction::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', 'active')
            ->first();

        if ($existingTransaction) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki peminjaman aktif untuk buku ini'
            ], 400);
        }

        // Store payment proof
        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

        // Create transaction
        $transaction = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'transaction_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => 'active',
            'payment_proof' => $proofPath
        ]);

        // Decrease book stock
        $book->decrement('stock');

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dipinjam',
            'data' => $transaction->load(['book', 'user'])
        ]);
    }

    /**
     * Borrow free book (without payment proof)
     */
    public function borrowFreeBook(Request $request, $bookId)
    {
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        if (!$book->is_free) {
            return response()->json([
                'success' => false,
                'message' => 'Buku ini tidak gratis. Silakan upload bukti pembayaran.'
            ], 400);
        }

        if ($book->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Buku sedang tidak tersedia'
            ], 400);
        }

        $user = $request->user();

        // Check if user already has an active transaction for this book
        $existingTransaction = \App\Models\Transaction::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', 'active')
            ->first();

        if ($existingTransaction) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki peminjaman aktif untuk buku ini'
            ], 400);
        }

        // Create transaction without payment proof
        $transaction = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'transaction_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => 'active'
        ]);

        // Decrease book stock
        $book->decrement('stock');

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dipinjam',
            'data' => $transaction->load(['book', 'user'])
        ]);
    }

    /**
     * Writer index - show writer's books (books that have content uploaded by this writer)
     */
    public function writerIndex(Request $request)
    {
        $user = $request->user();

        // Get books that have content uploaded by this writer
        $bookIds = Content::where('uploaded_by', $user->id)
            ->pluck('book_id')
            ->unique()
            ->filter();

        $books = Book::whereIn('id', $bookIds)
            ->with(['contents' => function ($query) use ($user) {
                $query->where('uploaded_by', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * Writer store book - create a new book for writer
     */
    public function writerStoreBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'genre' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_free' => 'boolean',
            'published_year' => 'required|integer|min:1900|max:2099'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'genre' => $request->genre,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'published_year' => $request->published_year,
            'is_free' => $request->is_free ?? false,
            'cover_image' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }
}
