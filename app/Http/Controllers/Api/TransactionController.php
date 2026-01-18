<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $transactions = $query->with(['user', 'book', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $book = Book::find($request->book_id);

        if ($book->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok buku habis'
            ], 422);
        }

        $transaction = Transaction::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrow_date' => $request->borrow_date,
            'due_date' => $request->due_date,
            'status' => 'active',
            'transaction_id' => 'TRX-' . strtoupper(uniqid())
        ]);

        // Reduce stock
        $book->decrement('stock');

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat',
            'data' => $transaction->load(['user', 'book'])
        ], 201);
    }

    /**
     * Display the specified transaction.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['user', 'book', 'payment'])->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Update the specified transaction.
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|in:active,completed,overdue,cancelled',
            'borrow_date' => 'sometimes|required|date',
            'due_date' => 'sometimes|required|date|after:borrow_date',
            'return_date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $request->only(['status', 'borrow_date', 'due_date', 'return_date']);
        $transaction->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diupdate',
            'data' => $transaction->fresh()->load(['user', 'book', 'payment'])
        ]);
    }

    /**
     * Remove the specified transaction.
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus'
        ]);
    }

    /**
     * Get transaction status
     */
    public function getStatus($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $transaction->status,
                'status_text' => $this->getStatusText($transaction->status)
            ]
        ]);
    }

    /**
     * Get transaction deadline
     */
    public function getDeadline($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $isOverdue = now()->isAfter($transaction->due_date) && $transaction->status === 'active';

        return response()->json([
            'success' => true,
            'data' => [
                'due_date' => $transaction->due_date,
                'is_overdue' => $isOverdue,
                'days_remaining' => now()->diffInDays($transaction->due_date, false)
            ]
        ]);
    }

    /**
     * Return book
     */
    public function returnBook($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        if ($transaction->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Buku sudah dikembalikan'
            ], 422);
        }

        $transaction->update([
            'status' => 'completed',
            'return_date' => now()
        ]);

        // Increase stock
        $transaction->book->increment('stock');

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dikembalikan',
            'data' => $transaction->fresh()->load(['user', 'book'])
        ]);
    }

    /**
     * Member purchase book
     */
    public function purchase(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        if ($book->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok buku habis'
            ], 422);
        }

        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => $book->is_free ? 'active' : 'pending_payment',
            'transaction_id' => 'TRX-' . strtoupper(uniqid())
        ]);

        // Reduce stock
        $book->decrement('stock');

        return response()->json([
            'success' => true,
            'message' => 'Pembelian berhasil',
            'data' => $transaction->load(['user', 'book'])
        ], 201);
    }

    /**
     * Member history
     */
    public function memberHistory(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->with(['book', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    private function getStatusText($status)
    {
        $statuses = [
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'overdue' => 'Terlambat',
            'cancelled' => 'Dibatalkan',
            'pending_payment' => 'Menunggu Pembayaran'
        ];

        return $statuses[$status] ?? $status;
    }
}
