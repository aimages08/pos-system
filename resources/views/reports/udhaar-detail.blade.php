@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">{{ $customer->name }} — Udhaar Details</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/reports/udhaar') }}">Udhaar</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="card card-primary card-outline mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div><strong>Phone:</strong> {{ $customer->phone ?? '—' }}</div>
                            <div><strong>Address:</strong> {{ $customer->address ?? '—' }}</div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="small text-secondary">Total Pending</div>
                            <div class="fs-3 fw-bold text-danger">
                                Rs {{ number_format($customer->opening_balance, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">Unpaid Invoices</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Due</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $s)
                                <tr>
                                    <td><strong>{{ $s->invoice_number }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($s->sale_date)->format('d M Y') }}</td>
                                    <td class="text-end">{{ number_format($s->grand_total, 2) }}</td>
                                    <td class="text-end">{{ number_format($s->paid_amount, 2) }}</td>
                                    <td class="text-end text-danger fw-bold">
                                        {{ number_format($s->due_amount, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url('/sales/'.$s->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-receipt"></i>
                                        </a>
                                        <a href="{{ url('/customer-payments/create') }}"
                                           class="btn btn-sm btn-success">
                                            <i class="bi bi-cash"></i> Receive
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-secondary py-4">
                                        No unpaid invoices.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection