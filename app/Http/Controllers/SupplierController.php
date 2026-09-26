<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'company'         => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:30',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'city'            => 'nullable|string|max:100',
            'opening_balance' => 'nullable|numeric',
            'status'          => 'required|in:active,disabled',
        ]);

        $data['opening_balance'] = $data['opening_balance'] ?? 0;

        Supplier::create($data);

        return redirect('/suppliers')->with('success', 'Supplier added successfully.');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'company'         => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:30',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'city'            => 'nullable|string|max:100',
            'opening_balance' => 'nullable|numeric',
            'status'          => 'required|in:active,disabled',
        ]);

        $data['opening_balance'] = $data['opening_balance'] ?? 0;

        $supplier->update($data);

        return redirect('/suppliers')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();

        return redirect('/suppliers')->with('success', 'Supplier deleted successfully.');
    }
}