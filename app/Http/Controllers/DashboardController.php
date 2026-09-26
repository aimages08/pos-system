<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $thisMonthStart = date('Y-m-01');

        // ---- Today stats ----
        $todaySales     = Sale::whereDate('sale_date', $today)->sum('grand_total');
        $todaySaleCount = Sale::whereDate('sale_date', $today)->count();
        $todayPaid      = Sale::whereDate('sale_date', $today)->sum('paid_amount');
        $todayDue       = Sale::whereDate('sale_date', $today)->sum('due_amount');
        $todayExpenses  = Expense::whereDate('expense_date', $today)->sum('amount');

        // ---- Month stats ----
        $monthSales    = Sale::whereBetween('sale_date', [$thisMonthStart, $today])->sum('grand_total');
        $monthExpenses = Expense::whereBetween('expense_date', [$thisMonthStart, $today])->sum('amount');

        // ---- Totals ----
        $totalProducts  = Product::count();
        $totalCustomers = Customer::count();
        $totalSuppliers = Supplier::count();

        // ---- Receivables / Payables ----
        $totalReceivable = Sale::sum('due_amount');          // Customers owe you
        $totalPayable    = Supplier::sum('opening_balance'); // You owe suppliers

        // ---- Low stock ----
        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->limit(10)
            ->get();
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        // ---- Recent sales ----
        $recentSales = Sale::with('customer')->latest()->limit(5)->get();

        // ---- Recent purchases ----
        $recentPurchases = Purchase::with(['supplier', 'product'])->latest()->limit(5)->get();

        // ---- Expiring soon (within 30 days) ----
        $expiringSoon = Product::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', $today)
            ->whereDate('expiry_date', '<=', date('Y-m-d', strtotime('+30 days')))
            ->orderBy('expiry_date')
            ->limit(10)
            ->get();

        // ---- Expired products ----
        $expiredProducts = Product::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', $today)
            ->orderBy('expiry_date', 'desc')
            ->limit(10)
            ->get();

        // ---- Total Udhaar ----
        $totalUdhaar = Customer::sum('opening_balance');

        // ---- Last 7 days sales chart ----
        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i day"));
            $chartLabels[] = date('d M', strtotime($d));
            $chartData[]   = (float) Sale::whereDate('sale_date', $d)->sum('grand_total');
        }

        return view('dashboard', compact(
            'todaySales', 'todaySaleCount', 'todayPaid', 'todayDue', 'todayExpenses',
            'monthSales', 'monthExpenses',
            'totalProducts', 'totalCustomers', 'totalSuppliers',
            'totalReceivable', 'totalPayable',
            'lowStockProducts', 'lowStockCount',
            'recentSales', 'recentPurchases',
            'expiringSoon', 'expiredProducts', 'totalUdhaar',
            'chartLabels', 'chartData'
        ));
    }
}