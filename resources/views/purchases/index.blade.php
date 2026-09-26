@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Purchase History</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Purchases</li>
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
                    <h3 class="card-title mb-0">All Purchases</h3>
                    <a href="{{ url('/purchases/create') }}" class="btn btn-sm btn-primary ms-auto">
                        <i class="bi bi-plus-lg me-1"></i> New Purchase
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Cost</th>
                                <th>Total</th>
                                <th style="width: 180px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchases->firstItem() + $loop->index }}</td>
                                    <td>{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                                    <td>{{ $purchase->supplier->name ?? '—' }}</td>
                                    <td>{{ $purchase->product->name ?? '—' }}</td>
                                    <td>{{ $purchase->quantity }}</td>
                                    <td>{{ number_format($purchase->cost_price, 2) }}</td>
                                    <td><strong>{{ number_format($purchase->total, 2) }}</strong></td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ url('/purchases/'.$purchase->id.'/edit') }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <form action="{{ url('/purchases/'.$purchase->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this purchase? Stock and supplier balance will be reverted.');">
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
                                    <td colspan="8" class="text-center text-secondary py-4">
                                        No purchases found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($purchases->hasPages())
                    <div class="card-footer">
                        {{ $purchases->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</main>

@endsection