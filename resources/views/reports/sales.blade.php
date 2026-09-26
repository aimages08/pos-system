@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Sales Report</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/reports') }}">Reports</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sales</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- Filter --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">From</label>
                            <input type="date" name="from" class="form-control" value="{{ $from }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To</label>
                            <input type="date" name="to" class="form-control" value="{{ $to }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                            <a href="{{ url('/reports/sales') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card text-bg-primary">
                        <div class="card-body">
                            <div class="small">Total Sales</div>
                            <h4 class="mb-0">{{ number_format($summary['grand_total'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-success">
                        <div class="card-body">
                            <div class="small">Paid</div>
                            <h4 class="mb-0">{{ number_format($summary['paid'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-danger">
                        <div class="card-body">
                            <div class="small">Due</div>
                            <h4 class="mb-0">{{ number_format($summary['due'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-info">
                        <div class="card-body">
                            <div class="small">Invoices</div>
                            <h4 class="mb-0">{{ $summary['count'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Credit Sales Card --}}
            @php
                $creditSales = $sales->where('payment_method', 'credit');
                $creditTotal = $creditSales->sum('grand_total');
                $creditDue   = $creditSales->sum('due_amount');
            @endphp

            @if ($creditSales->count() > 0)
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card text-bg-dark">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small">Credit Sales (Udhaar) — {{ $creditSales->count() }} invoices</div>
                                    <h4 class="mb-0">{{ number_format($creditTotal, 2) }}</h4>
                                </div>
                                <i class="bi bi-credit-card-2-back" style="font-size: 2rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card text-bg-warning">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small">Credit Due (still unpaid)</div>
                                    <h4 class="mb-0">{{ number_format($creditDue, 2) }}</h4>
                                </div>
                                <i class="bi bi-exclamation-triangle" style="font-size: 2rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Table --}}
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">Sales between {{ $from }} and {{ $to }}</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Method</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">Disc</th>
                                <th class="text-end">Tax</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Due</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $sale)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{ url('/sales/'.$sale->id) }}">{{ $sale->invoice_number }}</a></td>
                                    <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                                    <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                                    <td>
                                        @if ($sale->payment_method === 'credit')
                                            <span class="badge text-bg-dark">Credit</span>
                                        @else
                                            {{ ucfirst($sale->payment_method) }}
                                        @endif
                                    </td>
                                    <td class="text-end">{{ number_format($sale->subtotal, 2) }}</td>
                                    <td class="text-end">{{ number_format($sale->discount, 2) }}</td>
                                    <td class="text-end">{{ number_format($sale->tax, 2) }}</td>
                                    <td class="text-end"><strong>{{ number_format($sale->grand_total, 2) }}</strong></td>
                                    <td class="text-end">{{ number_format($sale->paid_amount, 2) }}</td>
                                    <td class="text-end text-danger">{{ number_format($sale->due_amount, 2) }}</td>
                                    <td>
                                        @if ($sale->payment_method === 'credit' && $sale->due_amount > 0)
                                            <span class="badge text-bg-dark">Udhaar</span>
                                        @elseif ($sale->payment_status === 'paid')
                                            <span class="badge text-bg-success">Paid</span>
                                        @elseif ($sale->payment_status === 'partial')
                                            <span class="badge text-bg-warning">Partial</span>
                                        @else
                                            <span class="badge text-bg-danger">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="12" class="text-center text-secondary py-4">No sales in this range.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection