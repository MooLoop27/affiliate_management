<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\CommissionDetail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isOwner() && !auth()->user()->isAdmin() && !auth()->user()->isFinance()) {
            abort(403);
        }

        $totalTransactions = Transaction::count();
        $totalCompanyBalance = Transaction::sum('company_balance_amount');
        $totalSgCommission = Transaction::sum('sg_commission_amount');
        $totalLeaderCommission = Transaction::sum('leader_commission_amount');
        $totalRecipientCommission = Transaction::sum('recipient_total_commission');

        $pendingPayments = CommissionDetail::where('payment_status', 'pending')->count();
        $processingPayments = CommissionDetail::where('payment_status', 'processing')->count();
        $completedPayments = CommissionDetail::where('payment_status', 'paid')->count();

        // Monthly transactions for current year
        $monthlyTransactions = Transaction::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('YEAR(date) as year'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(company_balance_amount) as total_amount')
        )
        ->whereYear('date', now()->year)
        ->groupBy('year', 'month')
        ->orderBy('month')
        ->get();

        // Recent transactions
        $recentTransactions = Transaction::with(['singaporePartner', 'leader'])
            ->latest()
            ->take(5)
            ->get();

        $companyName = Setting::getValue('company_name', 'Affiliate Commission System');

        return view('dashboard', compact(
            'totalTransactions',
            'totalCompanyBalance',
            'totalSgCommission',
            'totalLeaderCommission',
            'totalRecipientCommission',
            'pendingPayments',
            'processingPayments',
            'completedPayments',
            'monthlyTransactions',
            'recentTransactions',
            'companyName'
        ));
    }
}

