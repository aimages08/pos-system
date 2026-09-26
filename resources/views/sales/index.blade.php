@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Sales History</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sales</li>
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

            <div class="card card-primary card-outline mb-4">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">All Sales</h3>
                    <a href="{{ url('/sales/create') }}" class="btn btn-sm btn-primary ms-auto">
                        <i class="bi bi-plus-lg me-1"></i> New Sale
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th style="width: 120px">Status</th>
                                <th style="width: 180px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $sale)
                                <tr>
                                    <td>{{ $sales->firstItem() + $loop->index }}</td>
                                    <td><strong>{{ $sale->invoice_number }}</strong></td>
                                    <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                                    <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                                    <td>{{ number_format($sale->grand_total, 2) }}</td>
                                    <td>{{ number_format($sale->paid_amount, 2) }}</td>
                                    <td>{{ number_format($sale->due_amount, 2) }}</td>
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



                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ url('/sales/'.$sale->id) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-receipt"></i> View
                                            </a>
                                            <form action="{{ url('/sales/'.$sale->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this sale? Stock and customer balance will be reverted.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-secondary py-4">
                                        No sales found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($sales->hasPages())
                    <div class="card-footer">
                        {{ $sales->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</main>

@endsection