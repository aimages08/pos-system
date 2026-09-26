@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Purchase Report</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/reports') }}">Reports</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Purchases</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

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
                            <button type="submit" class="btn btn-primary"><i class="bi bi-filter me-1"></i> Filter</button>
                            <a href="{{ url('/reports/purchases') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card text-bg-primary">
                        <div class="card-body">
                            <div class="small">Total Purchases</div>
                            <h4 class="mb-0">{{ number_format($summary['total'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-bg-success">
                        <div class="card-body">
                            <div class="small">Total Quantity</div>
                            <h4 class="mb-0">{{ $summary['qty'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-bg-info">
                        <div class="card-body">
                            <div class="small">Entries</div>
                            <h4 class="mb-0">{{ $summary['count'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">Purchases between {{ $from }} and {{ $to }}</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Product</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Cost</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($purchases as $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->purchase_date->format('Y-m-d') }}</td>
                                    <td>{{ $p->supplier->name ?? '—' }}</td>
                                    <td>{{ $p->product->name ?? '—' }}</td>
                                    <td class="text-end">{{ $p->quantity }}</td>
                                    <td class="text-end">{{ number_format($p->cost_price, 2) }}</td>
                                    <td class="text-end"><strong>{{ number_format($p->total, 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-secondary py-4">No purchases in this range.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection