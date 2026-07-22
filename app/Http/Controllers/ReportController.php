<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\CommissionDetail;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::query();

        // Filters
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
        if ($request->filled('singapore_partner_id')) {
            $query->where('singapore_partner_id', $request->singapore_partner_id);
        }
        if ($request->filled('leader_id')) {
            $query->where('leader_id', $request->leader_id);
        }

        $transactions = $query->with(['singaporePartner', 'leader', 'commissionDetails'])->get();

        // Calculate totals
        $totalTransactions = $transactions->count();
        $totalCompanyBalance = $transactions->sum('company_balance_amount');
        $totalSgCommission = $transactions->sum('sg_commission_amount');
        $totalLeaderCommission = $transactions->sum('leader_commission_amount');
        $totalRecipientCommission = $transactions->sum('recipient_total_commission');

        $pendingPayments = CommissionDetail::whereIn('transaction_id', $transactions->pluck('id'))
            ->where('payment_status', 'pending')
            ->sum('commission_amount');

        $paidPayments = CommissionDetail::whereIn('transaction_id', $transactions->pluck('id'))
            ->where('payment_status', 'paid')
            ->sum('commission_amount');

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        return view('reports.index', compact(
            'transactions',
            'totalTransactions',
            'totalCompanyBalance',
            'totalSgCommission',
            'totalLeaderCommission',
            'totalRecipientCommission',
            'pendingPayments',
            'paidPayments',
            'dateFrom',
            'dateTo'
        ));
    }

    public function exportExcel(Request $request)
    {
        // This would use a package like PhpSpreadsheet
        // For now, redirect with message
        return redirect()->back()->with('info', 'Excel export feature will be available soon.');
    }

    public function exportPdf(Request $request)
    {
        // This would use a package like DomPDF
        // For now, redirect with message
        return redirect()->back()->with('info', 'PDF export feature will be available soon.');
    }
}

