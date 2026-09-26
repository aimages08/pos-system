<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->paginate(10);
        $total = Expense::sum('amount');
        return view('expenses.index', compact('expenses', 'total'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_date'   => 'required|date',
            'title'          => 'required|string|max:255',
            'category'       => 'nullable|string|max:100',
            'amount'         => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank,card,other',
            'notes'          => 'nullable|string',
        ]);

        Expense::create($data);

        return redirect('/expenses')->with('success', 'Expense added successfully.');
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $data = $request->validate([
            'expense_date'   => 'required|date',
            'title'          => 'required|string|max:255',
            'category'       => 'nullable|string|max:100',
            'amount'         => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank,card,other',
            'notes'          => 'nullable|string',
        ]);

        $expense->update($data);

        return redirect('/expenses')->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();

        return redirect('/expenses')->with('success', 'Expense deleted successfully.');
    }
}