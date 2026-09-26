<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayment;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerPaymentController extends Controller
{
    public function index()
    {
        $payments = CustomerPayment::with(['customer', 'sale'])->latest()->paginate(10);
        $total = CustomerPayment::sum('amount');
        return view('customer_payments.index', compact('payments', 'total'));
    }

    public function create(Request $request)
    {
        $customers = Customer::where('status', 'active')->get();

        // Pre-select customer if passed in query (?customer_id=5)
        $selectedCustomerId = $request->customer_id;

        // If customer selected, load their unpaid/partial sales for the dropdown
        $unpaidSales = [];
        if ($selectedCustomerId) {
            $unpaidSales = Sale::where('customer_id', $selectedCustomerId)
                ->where('due_amount', '>', 0)
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('customer_payments.create', compact('customers', 'unpaidSales', 'selectedCustomerId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'sale_id'        => 'nullable|exists:sales,id',
            'payment_date'   => 'required|date',
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank,other',
            'notes'          => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Save payment
            $payment = CustomerPayment::create($data);

            // Reduce customer balance
            Customer::where('id', $data['customer_id'])->decrement('opening_balance', $data['amount']);

            // If linked to a sale → reduce its due_amount too
            if (!empty($data['sale_id'])) {
                $sale = Sale::find($data['sale_id']);
                if ($sale) {
                    $newPaid = $sale->paid_amount + $data['amount'];
                    $newDue  = max(0, $sale->grand_total - $newPaid);

                    $status = 'unpaid';
                    if ($newDue <= 0)       $status = 'paid';
                    elseif ($newPaid > 0)   $status = 'partial';

                    $sale->update([
                        'paid_amount'    => $newPaid,
                        'due_amount'     => $newDue,
                        'payment_status' => $status,
                    ]);
                }
            }

            DB::commit();

            return redirect('/customer-payments')->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $payment = CustomerPayment::findOrFail($id);

        DB::beginTransaction();
        try {
            // Revert customer balance
            Customer::where('id', $payment->customer_id)->increment('opening_balance', $payment->amount);

            // Revert linked sale
            if ($payment->sale_id) {
                $sale = Sale::find($payment->sale_id);
                if ($sale) {
                    $newPaid = max(0, $sale->paid_amount - $payment->amount);
                    $newDue  = max(0, $sale->grand_total - $newPaid);

                    $status = 'unpaid';
                    if ($newDue <= 0)     $status = 'paid';
                    elseif ($newPaid > 0) $status = 'partial';

                    $sale->update([
                        'paid_amount'    => $newPaid,
                        'due_amount'     => $newDue,
                        'payment_status' => $status,
                    ]);
                }
            }

            $payment->delete();
            DB::commit();

            return redirect('/customer-payments')->with('success', 'Payment deleted and balance reverted.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // AJAX endpoint — get unpaid sales for a customer
    public function unpaidSales($customerId)
    {
        $sales = Sale::where('customer_id', $customerId)
            ->where('due_amount', '>', 0)
            ->orderBy('id', 'desc')
            ->get(['id', 'invoice_number', 'grand_total', 'paid_amount', 'due_amount']);

        return response()->json($sales);
    }
}