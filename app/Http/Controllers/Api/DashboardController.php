<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Content;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        $totalUsers = User::count();
        $totalBooks = Book::count();
        $freeBooks = Book::where('is_free', true)->count();
        $paidBooks = Book::where('is_free', false)->count();

        $totalRevenue = Payment::where('status', 'approved')->sum('amount');

        $activeTransactions = Transaction::where('status', 'active')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();
        $overdueTransactions = Transaction::where('status', 'overdue')->count();
        $lowStockBooks = Book::where('stock', '<=', 5)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'total_books' => $totalBooks,
                'free_books' => $freeBooks,
                'paid_books' => $paidBooks,
                'total_revenue' => $totalRevenue,
                'active_transactions' => $activeTransactions,
                'pending_payments' => $pendingPayments,
                'overdue_transactions' => $overdueTransactions,
                'low_stock_books' => $lowStockBooks
            ]
        ]);
    }

    /**
     * Get revenue data for chart
     */
    public function revenue(Request $request)
    {
        $period = $request->get('period', 'monthly'); // daily, weekly, monthly, yearly

        $query = Payment::where('status', 'approved');

        switch ($period) {
            case 'daily':
                $data = $query->selectRaw('DATE(payment_date) as date, SUM(amount) as revenue')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
            case 'weekly':
                $data = $query->selectRaw('WEEK(payment_date) as week, SUM(amount) as revenue')
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get();
                break;
            case 'monthly':
                $data = $query->selectRaw('MONTH(payment_date) as month, SUM(amount) as revenue')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                break;
            case 'yearly':
                $data = $query->selectRaw('YEAR(payment_date) as year, SUM(amount) as revenue')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get book status data for chart
     */
    public function bookStatus()
    {
        $inStock = Book::where('stock', '>', 0)->count();
        $outOfStock = Book::where('stock', '=', 0)->count();
        $lowStock = Book::where('stock', '>', 0)->where('stock', '<=', 5)->count();

        $byGenre = Book::selectRaw('genre, COUNT(*) as count')
            ->groupBy('genre')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stock_status' => [
                    'in_stock' => $inStock,
                    'out_of_stock' => $outOfStock,
                    'low_stock' => $lowStock
                ],
                'by_genre' => $byGenre
            ]
        ]);
    }

    /**
     * Generate report
     */
    public function generateReport(Request $request)
    {
        $type = $request->get('type', 'pdf'); // pdf or excel
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Get data
        $transactions = Transaction::with(['user', 'book', 'payment'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $revenue = Payment::where('status', 'approved')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        $reportData = [
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'summary' => [
                'total_transactions' => $transactions->count(),
                'total_revenue' => $revenue
            ],
            'transactions' => $transactions
        ];

        // In real implementation, generate PDF/Excel file here
        // and return the file URL

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil digenerate',
            'data' => $reportData
        ]);
    }

    /**
     * Global search
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $books = Book::where('title', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->orWhere('isbn', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'title', 'author', 'cover_image']);

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'email']);

        return response()->json([
            'success' => true,
            'data' => [
                'books' => $books,
                'users' => $users
            ]
        ]);
    }

    /**
     * Get alerts
     */
    public function alerts()
    {
        $alerts = [];

        // Low stock alerts - DISABLED
        // $lowStockBooks = Book::where('stock', '<=', 5)->get();
        // foreach ($lowStockBooks as $book) {
        //     $alerts[] = [
        //         'type' => 'low_stock',
        //         'message' => "Stok buku '{$book->title}' rendah ({$book->stock})",
        //         'book_id' => $book->id
        //     ];
        // }

        // Pending transaction alerts
        $pendingTransactions = Transaction::where('status', 'pending_payment')->count();
        if ($pendingTransactions > 0) {
            $alerts[] = [
                'type' => 'pending_transaction',
                'message' => "{$pendingTransactions} transaksi menunggu pembayaran",
                'count' => $pendingTransactions
            ];
        }

        // Overdue alerts
        $overdueTransactions = Transaction::where('status', 'active')
            ->where('due_date', '<', now())
            ->get();
        foreach ($overdueTransactions as $transaction) {
            $alerts[] = [
                'type' => 'overdue',
                'message' => "Peminjaman overdue: {$transaction->user->name} - {$transaction->book->title}",
                'transaction_id' => $transaction->id
            ];
        }

        // Pending content alerts
        $pendingContents = Content::where('status', 'pending')->get();
        foreach ($pendingContents as $content) {
            $alerts[] = [
                'type' => 'pending_content',
                'message' => "Konten belum disetujui: {$content->title}",
                'content_id' => $content->id
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * Get messages (from writers to admin)
     */
    public function messages(Request $request)
    {
        // In real implementation, this would query a messages table
        $messages = [];

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead($id)
    {
        // In real implementation, update message status
        return response()->json([
            'success' => true,
            'message' => 'Pesan ditandai sudah dibaca'
        ]);
    }

    /**
     * Writer messages
     */
    public function writerMessages(Request $request)
    {
        // Get messages from this writer
        $user = $request->user();

        // For now, return empty array - implement message storage later
        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }

    /**
     * Writer send message to admin
     */
    public function writerSendMessage(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Store message - you may want to create a Message model for this
        // For now, just return success
        return response()->json([
            'success' => true,
            'message' => 'Message sent to admin successfully'
        ]);
    }

    /**
     * Get member profile
     */
    public function memberProfile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'bio' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user->update($request->only(['name', 'email', 'phone', 'address', 'bio']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user->fresh()
        ]);
    }
}
