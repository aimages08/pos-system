<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // ---------- Reports Home ----------
    public function index()
    {
        return view('reports.index');
    }

    // ---------- Sales Report ----------
    public function sales(Request $request)
    {
        $from = $request->from ?? date('Y-m-01');   // first of this month
        $to = $request->to ?? date('Y-m-d');

        $sales = Sale::with('customer')
            ->whereBetween('sale_date', [$from, $to])
            ->latest()
            ->get();

        $summary = [
            'count' => $sales->count(),
            'subtotal' => $sales->sum('subtotal'),
            'discount' => $sales->sum('discount'),
            'tax' => $sales->sum('tax'),
            'grand_total' => $sales->sum('grand_total'),
            'paid' => $sales->sum('paid_amount'),
            'due' => $sales->sum('due_amount'),
        ];

        return view('reports.sales', compact('sales', 'summary', 'from', 'to'));
    }

    // ---------- Purchase Report ----------
    public function purchases(Request $request)
    {
        $from = $request->from ?? date('Y-m-01');
        $to = $request->to ?? date('Y-m-d');

        $purchases = Purchase::with(['supplier', 'product'])
            ->whereBetween('purchase_date', [$from, $to])
            ->latest()
            ->get();

        $summary = [
            'count' => $purchases->count(),
            'qty' => $purchases->sum('quantity'),
            'total' => $purchases->sum('total'),
        ];

        return view('reports.purchases', compact('purchases', 'summary', 'from', 'to'));
    }

    // ---------- Stock Report ----------
    public function stock()
    {
        $products = Product::with('category')->orderBy('name')->get();

        $summary = [
            'total_items' => $products->count(),
            'total_stock' => $products->sum('stock'),
            'stock_value' => $products->sum(fn ($p) => $p->stock * $p->cost_price),
            'low_stock_count' => $products->where('stock', '<=', 'minimum_stock')->count(),
        ];

        return view('reports.stock', compact('products', 'summary'));
    }

    // ---------- Profit / Loss ----------
    public function profit(Request $request)
    {
        $from = $request->from ?? date('Y-m-01');
        $to = $request->to ?? date('Y-m-d');

        // Total sales in range
        $totalSales = Sale::whereBetween('sale_date', [$from, $to])->sum('grand_total');

        // Cost of Goods Sold = sum(qty * product cost_price) from sale items
        $cogs = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->whereBetween('sales.sale_date', [$from, $to])
            ->sum(DB::raw('sale_items.quantity * products.cost_price'));

        // Expenses in range
        $totalExpenses = Expense::whereBetween('expense_date', [$from, $to])->sum('amount');

        // Calculations
        $grossProfit = $totalSales - $cogs;
        $netProfit = $grossProfit - $totalExpenses;
        $margin = $totalSales > 0 ? ($netProfit / $totalSales) * 100 : 0;

        $summary = [
            'total_sales' => $totalSales,
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'margin' => $margin,
        ];

        // Expenses breakdown by category
        $expenseBreakdown = Expense::whereBetween('expense_date', [$from, $to])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        return view('reports.profit', compact('summary', 'expenseBreakdown', 'from', 'to'));
    }

    public function udhaar()
{
    $customers = \App\Models\Customer::where('opening_balance', '>', 0)
        ->orderBy('opening_balance', 'desc')
        ->get();

    $total = $customers->sum('opening_balance');

    return view('reports.udhaar', compact('customers', 'total'));
}

public function udhaarDetail($id)
{
    $customer = \App\Models\Customer::findOrFail($id);

    $sales = \App\Models\Sale::where('customer_id', $id)
        ->where('due_amount', '>', 0)
        ->orderBy('sale_date', 'desc')
        ->get();

    return view('reports.udhaar-detail', compact('customer', 'sales'));
}




}
