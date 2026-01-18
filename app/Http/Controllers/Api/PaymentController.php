<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $query = Payment::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->with(['transaction.user', 'transaction.book'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'amount' => 'required|numeric|min:0',
            'proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $transaction = Transaction::find($request->transaction_id);

        if ($transaction->payment) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah memiliki pembayaran'
            ], 422);
        }

        // Upload proof
        $path = $request->file('proof')->store('payment-proofs', 'public');

        $payment = Payment::create([
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount,
            'proof' => $path,
            'status' => 'pending',
            'payment_date' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dibuat',
            'data' => $payment->load('transaction')
        ], 201);
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        $payment = Payment::with(['transaction.user', 'transaction.book'])->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'sometimes|required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $request->only(['amount']);
        $payment->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diupdate',
            'data' => $payment->fresh()->load('transaction')
        ]);
    }

    /**
     * Remove the specified payment.
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }

        // Delete proof
        if ($payment->proof) {
            Storage::disk('public')->delete($payment->proof);
        }

        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dihapus'
        ]);
    }

    /**
     * Approve payment
     */
    public function approve($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }

        if ($payment->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah disetujui'
            ], 422);
        }

        $payment->update(['status' => 'approved']);

        // Update transaction status
        $payment->transaction->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran disetujui',
            'data' => $payment->fresh()->load('transaction')
        ]);
    }

    /**
     * Reject payment
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        // Update transaction status
        $payment->transaction->update(['status' => 'cancelled']);
        $payment->transaction->book->increment('stock');

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran ditolak',
            'data' => $payment->fresh()->load('transaction')
        ]);
    }
}
