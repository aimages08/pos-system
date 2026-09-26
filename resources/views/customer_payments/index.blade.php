@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Customer Payments</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Payments</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card text-bg-success">
                        <div class="card-body">
                            <div class="small">Total Received (All Time)</div>
                            <h3 class="mb-0">{{ number_format($total, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline mb-4">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">All Payments</h3>
                    <a href="{{ url('/customer-payments/create') }}" class="btn btn-sm btn-primary ms-auto">
                        <i class="bi bi-plus-lg me-1"></i> Record Payment
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Against Invoice</th>
                                <th>Method</th>
                                <th class="text-end">Amount</th>
                                <th style="width: 120px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $p)
                                <tr>
                                    <td>{{ $payments->firstItem() + $loop->index }}</td>
                                    <td>{{ $p->payment_date->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ url('/customers/'.$p->customer_id.'/edit') }}">
                                            {{ $p->customer->name ?? '—' }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($p->sale)
                                            <a href="{{ url('/sales/'.$p->sale_id) }}">
                                                {{ $p->sale->invoice_number }}
                                            </a>
                                        @else
                                            <span class="text-secondary">General (no invoice)</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($p->payment_method) }}</td>
                                    <td class="text-end"><strong>{{ number_format($p->amount, 2) }}</strong></td>
                                    <td class="text-center">
                                        <form action="{{ url('/customer-payments/'.$p->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete this payment? Customer and sale balances will be reverted.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-secondary py-4">
                                        No payments recorded.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($payments->hasPages())
                    <div class="card-footer">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</main>

@endsection