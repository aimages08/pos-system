@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Stock Report</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/reports') }}">Reports</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Stock</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card text-bg-primary">
                        <div class="card-body">
                            <div class="small">Total Products</div>
                            <h4 class="mb-0">{{ $summary['total_items'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-success">
                        <div class="card-body">
                            <div class="small">Total Stock (Units)</div>
                            <h4 class="mb-0">{{ $summary['total_stock'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-info">
                        <div class="card-body">
                            <div class="small">Stock Value (Cost)</div>
                            <h4 class="mb-0">{{ number_format($summary['stock_value'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-danger">
                        <div class="card-body">
                            <div class="small">Low Stock Items</div>
                            <h4 class="mb-0">{{ $summary['low_stock_count'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">Current Stock</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th class="text-end">Stock</th>
                                <th class="text-end">Min</th>
                                <th class="text-end">Cost</th>
                                <th class="text-end">Stock Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->sku ?? '—' }}</td>
                                    <td>{{ $p->category->name ?? '—' }}</td>
                                    <td class="text-end">{{ $p->stock }}</td>
                                    <td class="text-end">{{ $p->minimum_stock }}</td>
                                    <td class="text-end">{{ number_format($p->cost_price, 2) }}</td>
                                    <td class="text-end">{{ number_format($p->stock * $p->cost_price, 2) }}</td>
                                    <td>
                                        @if ($p->stock <= $p->minimum_stock)
                                            <span class="badge text-bg-danger">Low Stock</span>
                                        @else
                                            <span class="badge text-bg-success">OK</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection