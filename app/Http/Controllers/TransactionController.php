<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\SingaporePartner;
use App\Models\Leader;
use App\Models\Recipient;
use App\Models\CommissionDetail;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::with(['singaporePartner', 'leader', 'commissionDetails.recipient']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('singaporePartner', function($sq) use ($search) {
                      $sq->where('partner_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('leader', function($lq) use ($search) {
                      $lq->where('leader_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('commissionDetails.recipient', function($rq) use ($search) {
                      $rq->where('recipient_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($request->filled('singapore_partner_id')) {
            $query->where('singapore_partner_id', $request->singapore_partner_id);
        }
        if ($request->filled('leader_id')) {
            $query->where('leader_id', $request->leader_id);
        }
        if ($request->filled('recipient_id')) {
            $query->whereHas('commissionDetails', function($q) use ($request) {
                $q->where('recipient_id', $request->recipient_id);
            });
        }
        if ($request->filled('payment_status')) {
            $query->whereHas('commissionDetails', function($q) use ($request) {
                $q->where('payment_status', $request->payment_status);
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $transactions = $query->latest()->paginate(10);

        $singaporePartners = SingaporePartner::active()->get();
        $leaders = Leader::active()->get();
        $recipients = Recipient::active()->get();

        return view('transactions.index', compact('transactions', 'singaporePartners', 'leaders', 'recipients'));
    }

    public function create(): View
    {
        $singaporePartners = SingaporePartner::active()->get();
        $leaders = Leader::active()->get();
        $recipients = Recipient::active()->get();
        $sgPercentage = Setting::getValue('sg_commission_percentage', '5');
        $leaderPercentage = Setting::getValue('leader_commission_percentage', '10');
        $defaultRecipientPercentage = Setting::getValue('default_recipient_commission_percentage', '2');

        return view('transactions.create', compact(
            'singaporePartners', 'leaders', 'recipients',
            'sgPercentage', 'leaderPercentage', 'defaultRecipientPercentage'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'singapore_partner_id' => 'required|exists:singapore_partners,id',
            'leader_id' => 'required|exists:leaders,id',
            'company_balance_amount' => 'required|numeric|min:0',
            'sg_commission_percentage' => 'required|numeric|min:0|max:100',
            'leader_commission_percentage' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'recipients' => 'required|array|min:1',
            'recipients.*.recipient_id' => 'required|exists:recipients,id',
            'recipients.*.commission_percentage' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Create transaction
            $validated['transaction_code'] = Transaction::generateCode();
            $validated['sg_commission_amount'] = $validated['company_balance_amount'] * $validated['sg_commission_percentage'] / 100;
            $validated['leader_commission_amount'] = $validated['company_balance_amount'] * $validated['leader_commission_percentage'] / 100;
            $validated['total_commission'] = $validated['sg_commission_amount'] + $validated['leader_commission_amount'];

            $transaction = Transaction::create($validated);

            // Create commission details
            $recipientTotalCommission = 0;
            foreach ($request->recipients as $recipientData) {
                $commissionAmount = $validated['company_balance_amount'] * $recipientData['commission_percentage'] / 100;
                $recipientTotalCommission += $commissionAmount;

                $commissionDetail = CommissionDetail::create([
                    'transaction_id' => $transaction->id,
                    'recipient_id' => $recipientData['recipient_id'],
                    'commission_percentage' => $recipientData['commission_percentage'],
                    'commission_amount' => $commissionAmount,
                    'payment_status' => 'pending',
                ]);

                // Log payment history
                \App\Models\PaymentHistory::create([
                    'commission_detail_id' => $commissionDetail->id,
                    'user_id' => auth()->id(),
                    'status' => 'pending',
                    'date' => now(),
                    'time' => now()->format('H:i:s'),
                ]);
            }

            $transaction->update(['recipient_total_commission' => $recipientTotalCommission]);

            DB::commit();

            ActivityLog::log('Created Transaction: ' . $transaction->transaction_code);

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create transaction: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Transaction $transaction): View
    {
        $transaction->load([
            'singaporePartner',
            'leader',
            'commissionDetails.recipient',
            'commissionDetails.paymentHistories.user',
            'commissionDetails.updatedBy',
        ]);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction): View
    {
        $transaction->load('commissionDetails.recipient');
        $singaporePartners = SingaporePartner::active()->get();
        $leaders = Leader::active()->get();
        $recipients = Recipient::active()->get();

        return view('transactions.edit', compact('transaction', 'singaporePartners', 'leaders', 'recipients'));
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'singapore_partner_id' => 'required|exists:singapore_partners,id',
            'leader_id' => 'required|exists:leaders,id',
            'company_balance_amount' => 'required|numeric|min:0',
            'sg_commission_percentage' => 'required|numeric|min:0|max:100',
            'leader_commission_percentage' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'recipients' => 'required|array|min:1',
            'recipients.*.recipient_id' => 'required|exists:recipients,id',
            'recipients.*.commission_percentage' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Update transaction
            $validated['sg_commission_amount'] = $validated['company_balance_amount'] * $validated['sg_commission_percentage'] / 100;
            $validated['leader_commission_amount'] = $validated['company_balance_amount'] * $validated['leader_commission_percentage'] / 100;
            $validated['total_commission'] = $validated['sg_commission_amount'] + $validated['leader_commission_amount'];

            $transaction->update($validated);

            // Remove old commission details
            $transaction->commissionDetails()->delete();

            // Create new commission details
            $recipientTotalCommission = 0;
            foreach ($request->recipients as $recipientData) {
                $commissionAmount = $validated['company_balance_amount'] * $recipientData['commission_percentage'] / 100;
                $recipientTotalCommission += $commissionAmount;

                CommissionDetail::create([
                    'transaction_id' => $transaction->id,
                    'recipient_id' => $recipientData['recipient_id'],
                    'commission_percentage' => $recipientData['commission_percentage'],
                    'commission_amount' => $commissionAmount,
                    'payment_status' => 'pending',
                ]);
            }

            $transaction->update(['recipient_total_commission' => $recipientTotalCommission]);

            DB::commit();

            ActivityLog::log('Updated Transaction: ' . $transaction->transaction_code);

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update transaction: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $code = $transaction->transaction_code;
        $transaction->delete();

        ActivityLog::log('Deleted Transaction: ' . $code);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}

