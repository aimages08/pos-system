<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'product'])->latest()->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $products  = Product::where('status', 'active')->get();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'purchase_date' => 'required|date',
            'supplier_id'   => 'required|exists:suppliers,id',
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'cost_price'    => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        $data['total'] = $data['quantity'] * $data['cost_price'];

        DB::transaction(function () use ($data) {
            Purchase::create($data);

            // Increase product stock
            Product::where('id', $data['product_id'])->increment('stock', $data['quantity']);

            // Increase supplier balance
            Supplier::where('id', $data['supplier_id'])->increment('opening_balance', $data['total']);
        });

        return redirect('/purchases')->with('success', 'Purchase recorded successfully.');
    }

    public function edit($id)
    {
        $purchase  = Purchase::findOrFail($id);
        $suppliers = Supplier::where('status', 'active')->get();
        $products  = Product::where('status', 'active')->get();
        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $data = $request->validate([
            'purchase_date' => 'required|date',
            'supplier_id'   => 'required|exists:suppliers,id',
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'cost_price'    => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        $data['total'] = $data['quantity'] * $data['cost_price'];

        DB::transaction(function () use ($purchase, $data) {
            // Revert old stock & balance
            Product::where('id', $purchase->product_id)->decrement('stock', $purchase->quantity);
            Supplier::where('id', $purchase->supplier_id)->decrement('opening_balance', $purchase->total);

            // Apply new
            $purchase->update($data);

            Product::where('id', $data['product_id'])->increment('stock', $data['quantity']);
            Supplier::where('id', $data['supplier_id'])->increment('opening_balance', $data['total']);
        });

        return redirect('/purchases')->with('success', 'Purchase updated successfully.');
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        DB::transaction(function () use ($purchase) {
            // Revert stock & supplier balance
            Product::where('id', $purchase->product_id)->decrement('stock', $purchase->quantity);
            Supplier::where('id', $purchase->supplier_id)->decrement('opening_balance', $purchase->total);

            $purchase->delete();
        });

        return redirect('/purchases')->with('success', 'Purchase deleted successfully.');
    }
}