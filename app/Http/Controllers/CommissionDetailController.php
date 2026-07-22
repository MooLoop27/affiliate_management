<?php

namespace App\Http\Controllers;

use App\Models\CommissionDetail;
use App\Models\PaymentHistory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CommissionDetailController extends Controller
{
    public function updatePayment(Request $request, CommissionDetail $commissionDetail): RedirectResponse
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,processing,paid,cancelled',
            'payment_notes' => 'nullable|string',
            'transfer_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'transfer_date' => 'nullable|date',
        ]);

        // Handle file upload
        if ($request->hasFile('transfer_proof')) {
            $file = $request->file('transfer_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('transfer-proofs', $filename, 'public');
            $validated['transfer_proof'] = $path;
        }

        $validated['updated_by'] = auth()->id();

        // Set transfer date when status is paid
        if ($validated['payment_status'] === 'paid' && empty($validated['transfer_date'])) {
            $validated['transfer_date'] = now()->format('Y-m-d');
        }

        $commissionDetail->update($validated);

        // Log payment history
        PaymentHistory::create([
            'commission_detail_id' => $commissionDetail->id,
            'user_id' => auth()->id(),
            'status' => $validated['payment_status'],
            'date' => now(),
            'time' => now()->format('H:i:s'),
            'notes' => $validated['payment_notes'] ?? null,
        ]);

        ActivityLog::log('Updated payment status for ' . $commissionDetail->recipient->recipient_name .
            ' to ' . $validated['payment_status']);

        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }

    public function getPaymentHistory(CommissionDetail $commissionDetail)
    {
        $commissionDetail->load('paymentHistories.user', 'recipient');
        return response()->json($commissionDetail);
    }

    public function downloadProof(CommissionDetail $commissionDetail)
    {
        if (!$commissionDetail->transfer_proof) {
            return redirect()->back()->with('error', 'No transfer proof found.');
        }

        return Storage::disk('public')->download($commissionDetail->transfer_proof);
    }
}

