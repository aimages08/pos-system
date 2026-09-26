@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Record Payment</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/customer-payments') }}">Customer Payments</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Record</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ url('/customer-payments') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Payment Details</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    {{-- Customer --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                        <select name="customer_id" id="customer_id"
                                                class="form-select @error('customer_id') is-invalid @enderror" required>
                                            <option value="">-- Select Customer --</option>
                                            @foreach ($customers as $c)
                                                <option value="{{ $c->id }}"
                                                        data-balance="{{ $c->opening_balance }}"
                                                    {{ old('customer_id', $selectedCustomerId) == $c->id ? 'selected' : '' }}>
                                                    {{ $c->name }}{{ $c->phone ? ' ('.$c->phone.')' : '' }}
                                                    — Balance: {{ number_format($c->opening_balance, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                        <div class="mt-2" id="customer-balance-info"></div>
                                    </div>

                                    {{-- Date --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                        <input type="date" name="payment_date" id="payment_date"
                                               class="form-control @error('payment_date') is-invalid @enderror"
                                               value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                        @error('payment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Sale --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="sale_id" class="form-label">Against Invoice (optional)</label>
                                        <select name="sale_id" id="sale_id"
                                                class="form-select @error('sale_id') is-invalid @enderror">
                                            <option value="">-- General Payment (no specific invoice) --</option>
                                        </select>
                                        @error('sale_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <small class="text-secondary">Leave empty to just reduce the customer's balance.</small>
                                    </div>

                                    {{-- Amount --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0.01" name="amount" id="amount"
                                               class="form-control @error('amount') is-invalid @enderror"
                                               value="{{ old('amount') }}" required>
                                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Method --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select name="payment_method" id="payment_method"
                                                class="form-select @error('payment_method') is-invalid @enderror" required>
                                            <option value="cash"  {{ old('payment_method') == 'cash'  ? 'selected' : '' }}>Cash</option>
                                            <option value="bank"  {{ old('payment_method') == 'bank'  ? 'selected' : '' }}>Bank</option>
                                            <option value="card"  {{ old('payment_method') == 'card'  ? 'selected' : '' }}>Card</option>
                                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Notes --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <input type="text" name="notes" id="notes"
                                               class="form-control @error('notes') is-invalid @enderror"
                                               value="{{ old('notes') }}">
                                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Save Payment
                                </button>
                                <a href="{{ url('/customer-payments') }}" class="btn btn-outline-secondary ms-auto">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const customerSelect = document.getElementById('customer_id');
    const saleSelect     = document.getElementById('sale_id');
    const balanceInfo    = document.getElementById('customer-balance-info');

    function loadUnpaidSales(customerId) {
        saleSelect.innerHTML = '<option value="">-- General Payment (no specific invoice) --</option>';
        balanceInfo.innerHTML = '';

        if (!customerId) return;

        // Show balance info
        const opt = customerSelect.options[customerSelect.selectedIndex];
        const bal = parseFloat(opt.dataset.balance) || 0;
        balanceInfo.innerHTML = `<span class="badge text-bg-warning">Current Balance: ${bal.toFixed(2)}</span>`;

        // Fetch unpaid sales
        fetch('{{ url('/customer-payments/unpaid-sales') }}/' + customerId)
            .then(r => r.json())
            .then(sales => {
                sales.forEach(s => {
                    const o = document.createElement('option');
                    o.value = s.id;
                    o.textContent = `${s.invoice_number} — Due: ${parseFloat(s.due_amount).toFixed(2)}`;
                    saleSelect.appendChild(o);
                });
            });
    }

    customerSelect.addEventListener('change', function () {
        loadUnpaidSales(this.value);
    });

    // Preload on page load
    if (customerSelect.value) {
        loadUnpaidSales(customerSelect.value);
    }
});
</script>

@endsection