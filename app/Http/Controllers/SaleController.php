<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('customer')->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        $products  = Product::where('status', 'active')->where('stock', '>', 0)->get();
        $nextInvoice = $this->generateInvoiceNumber();
        return view('sales.create', compact('customers', 'products', 'nextInvoice'));
    }

    public function store(Request $request)
{
    $request->validate([
        'sale_date'      => 'required|date',
        'customer_id'    => 'nullable|exists:customers,id',
        'payment_method' => 'required|in:cash,card,bank,credit,other',
        'paid_amount'    => 'required|numeric|min:0',
        'discount'       => 'nullable|numeric|min:0',
        'tax'            => 'nullable|numeric|min:0',
        'notes'          => 'nullable|string',
        'items'          => 'required|array|min:1',
        'items.*.product_id'    => 'required|exists:products,id',
        'items.*.quantity'      => 'required|integer|min:1',
        'items.*.selling_price' => 'required|numeric|min:0',
        'items.*.discount'      => 'nullable|numeric|min:0',
        'items.*.tax'           => 'nullable|numeric|min:0',
    ]);

    // Credit sale requires a customer
    if ($request->payment_method === 'credit' && !$request->customer_id) {
        return back()->withInput()->withErrors([
            'customer_id' => 'Customer is required for credit (udhaar) sales.',
        ]);
    }

    // Stock check
    foreach ($request->items as $item) {
        $product = Product::find($item['product_id']);
        if (!$product || $product->stock < $item['quantity']) {
            return back()->withInput()->withErrors([
                'stock' => "Insufficient stock for " . ($product->name ?? 'product') . ". Available: " . ($product->stock ?? 0),
            ]);
        }
    }

    DB::beginTransaction();
    try {
        // ---------- Calculate subtotal from items ----------
        $subtotal = 0;
        foreach ($request->items as $item) {
            $qty     = (int) $item['quantity'];
            $price   = (float) $item['selling_price'];
            $disc    = (float) ($item['discount'] ?? 0);
            $taxPct  = (float) ($item['tax'] ?? 0);

            $base      = $qty * $price;
            $afterDisc = $base - $disc;
            $lineTax   = $afterDisc * ($taxPct / 100);
            $lineTotal = $afterDisc + $lineTax;

            $subtotal += $lineTotal;
        }

        $invDiscount = (float) ($request->discount ?? 0);
        $invTax      = (float) ($request->tax ?? 0);
        $grandTotal  = $subtotal - $invDiscount + $invTax;

        // ---------- Handle payment based on method ----------
        if ($request->payment_method === 'credit') {
            // Full udhaar
            $paidAmount = 0;
            $dueAmount  = $grandTotal;
            $status     = 'unpaid';
        } else {
            $paidAmount = (float) $request->paid_amount;
            $dueAmount  = max(0, $grandTotal - $paidAmount);

            if ($dueAmount <= 0)     $status = 'paid';
            elseif ($paidAmount > 0) $status = 'partial';
            else                     $status = 'unpaid';
        }

        // ---------- Create sale ----------
        $sale = Sale::create([
            'invoice_number' => $this->generateInvoiceNumber(),
            'customer_id'    => $request->customer_id,
            'sale_date'      => $request->sale_date,
            'subtotal'       => $subtotal,
            'discount'       => $invDiscount,
            'tax'            => $invTax,
            'grand_total'    => $grandTotal,
            'paid_amount'    => $paidAmount,
            'due_amount'     => $dueAmount,
            'payment_status' => $status,
            'payment_method' => $request->payment_method,
            'notes'          => $request->notes,
        ]);

        // ---------- Create items + reduce stock ----------
        foreach ($request->items as $item) {
            $qty     = (int) $item['quantity'];
            $price   = (float) $item['selling_price'];
            $disc    = (float) ($item['discount'] ?? 0);
            $taxPct  = (float) ($item['tax'] ?? 0);

            $base      = $qty * $price;
            $afterDisc = $base - $disc;
            $lineTax   = $afterDisc * ($taxPct / 100);
            $lineTotal = $afterDisc + $lineTax;

            SaleItem::create([
                'sale_id'       => $sale->id,
                'product_id'    => $item['product_id'],
                'quantity'      => $qty,
                'selling_price' => $price,
                'discount'      => $disc,
                'tax'           => $taxPct,
                'line_total'    => $lineTotal,
            ]);

            Product::where('id', $item['product_id'])->decrement('stock', $qty);
        }

        // ---------- Update customer balance (udhaar) ----------
        if ($sale->customer_id && $dueAmount > 0) {
            Customer::where('id', $sale->customer_id)->increment('opening_balance', $dueAmount);
        }

        DB::commit();

        return redirect('/sales/' . $sale->id)
            ->with('success', 'Sale completed. Invoice: ' . $sale->invoice_number);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
    }
}

    public function show($id)
    {
        $sale = Sale::with(['customer', 'items.product'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    public function destroy($id)
    {
        $sale = Sale::with('items')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Revert stock
            foreach ($sale->items as $item) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }

            // Revert customer balance
            if ($sale->customer_id && $sale->due_amount > 0) {
                Customer::where('id', $sale->customer_id)->decrement('opening_balance', $sale->due_amount);
            }

            $sale->delete();
            DB::commit();

            return redirect('/sales')->with('success', 'Sale deleted and stock reverted.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

        private function generateInvoiceNumber()
        {
            $last = Sale::orderBy('id', 'desc')->first();
            $next = $last ? ((int) substr($last->invoice_number, -4)) + 1 : 1;
            return 'INV-'.date('Y').'-'.str_pad($next, 4, '0', STR_PAD_LEFT);
        }

        public function receipt($id)
        {
            $sale = Sale::with(['items.product', 'customer'])->findOrFail($id);
            return view('sales.receipt', compact('sale'));
        }
    }