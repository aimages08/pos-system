@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Invoice {{ $sale->invoice_number }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/sales') }}">Sales</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Invoice</li>
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

            <div class="row">
                <div class="col-md-8 mx-auto">

                    <div class="card mb-4" id="invoice-print">

                        <div class="card-body p-4">

                            {{-- Header --}}
                            <div class="d-flex justify-content-between mb-4">
                                <div>
                                    <h3 class="mb-1">POS System</h3>
                                    <div class="text-secondary small">
                                        Your Shop Address<br>
                                        Phone: 000-0000000
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h4 class="mb-1">INVOICE</h4>
                                    <div class="small">
                                        <strong>#{{ $sale->invoice_number }}</strong><br>
                                        Date: {{ $sale->sale_date->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Customer / Payment info --}}
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="text-secondary small mb-1">BILL TO</div>
                                    <div>
                                        <strong>{{ $sale->customer->name ?? 'Walk-in Customer' }}</strong><br>
                                        @if ($sale->customer)
                                            @if ($sale->customer->phone) {{ $sale->customer->phone }}<br> @endif
                                            @if ($sale->customer->address) {{ $sale->customer->address }} @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <div class="text-secondary small mb-1">PAYMENT</div>
                                    <div>
                                        Method:
                                        <strong>
                                            @if ($sale->payment_method === 'credit')
                                                Credit / Udhaar
                                            @else
                                                {{ ucfirst($sale->payment_method) }}
                                            @endif
                                        </strong><br>
                                        Status:
                                        @if ($sale->payment_method === 'credit' && $sale->due_amount > 0)
                                            <span class="badge text-bg-dark">Udhaar</span>
                                        @elseif ($sale->payment_status === 'paid')
                                            <span class="badge text-bg-success">Paid</span>
                                        @elseif ($sale->payment_status === 'partial')
                                            <span class="badge text-bg-warning">Partial</span>
                                        @else
                                            <span class="badge text-bg-danger">Unpaid</span>
                                        @endif
                                    </div>

                                    @if ($sale->payment_method === 'credit')
                                        <div class="small text-danger mt-2">
                                            <i class="bi bi-exclamation-circle"></i>
                                            Sale on credit — due added to customer balance.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Items Table --}}
                            <div class="table-responsive mb-4">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Disc</th>
                                            <th class="text-center">Tax %</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sale->items as $i => $item)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $item->product->name ?? '—' }}</td>
                                                <td class="text-end">{{ number_format($item->selling_price, 2) }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">{{ number_format($item->discount, 2) }}</td>
                                                <td class="text-center">{{ number_format($item->tax, 2) }}</td>
                                                <td class="text-end">{{ number_format($item->line_total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Totals --}}
                            <div class="row">
                                <div class="col-6">
                                    @if ($sale->notes)
                                        <div class="text-secondary small mb-1">NOTES</div>
                                        <div>{{ $sale->notes }}</div>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <table class="table table-sm mb-0">
                                        <tr>
                                            <td>Subtotal</td>
                                            <td class="text-end">{{ number_format($sale->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Discount</td>
                                            <td class="text-end">- {{ number_format($sale->discount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tax</td>
                                            <td class="text-end">+ {{ number_format($sale->tax, 2) }}</td>
                                        </tr>
                                        <tr class="fw-bold border-top">
                                            <td>Grand Total</td>
                                            <td class="text-end">{{ number_format($sale->grand_total, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Paid</td>
                                            <td class="text-end">{{ number_format($sale->paid_amount, 2) }}</td>
                                        </tr>
                                        <tr class="fw-bold text-danger">
                                            <td>Due</td>
                                            <td class="text-end">{{ number_format($sale->due_amount, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="text-center text-secondary small mt-4 pt-3 border-top">
                                Thank you for your business!
                            </div>

                        </div>
                    </div>

                   {{-- Action buttons (hidden when printing) --}}
                    <div class="d-flex gap-2 mb-4 d-print-none">
                        <a href="{{ url('/sales/'.$sale->id.'/receipt') }}" target="_blank" class="btn btn-primary">
                            <i class="bi bi-printer me-1"></i> Print Receipt
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Print / Save PDF
                        </button>
                        <a href="{{ url('/sales') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Sales
                        </a>
                        <a href="{{ url('/sales/create') }}" class="btn btn-success ms-auto">
                            <i class="bi bi-plus-lg me-1"></i> New Sale
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</main>

{{-- Print styles --}}
<style>
    @media print {
        .app-header,
        .app-sidebar,
        .app-footer,
        .app-content-header,
        .d-print-none,
        nav[aria-label="breadcrumb"] {
            display: none !important;
        }
        .app-main {
            margin-left: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        body {
            background: #fff !important;
        }
    }
</style>

@endsection