@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Reports</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reports</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="card card-primary card-outline h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-graph-up-arrow text-primary" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 mb-2">Sales Report</h4>
                            <p class="text-secondary">View sales, discounts, taxes, payments and dues between two dates.</p>
                            <a href="{{ url('/reports/sales') }}" class="btn btn-primary">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Open Report
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-success card-outline h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-cart-down text-success" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 mb-2">Purchase Report</h4>
                            <p class="text-secondary">View purchases per supplier and product between two dates.</p>
                            <a href="{{ url('/reports/purchases') }}" class="btn btn-success">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Open Report
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-warning card-outline h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-boxes text-warning" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 mb-2">Stock Report</h4>
                            <p class="text-secondary">See current stock, stock value, and low-stock alerts.</p>
                            <a href="{{ url('/reports/stock') }}" class="btn btn-warning">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Open Report
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-danger card-outline h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-cash-coin text-danger" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 mb-2">Profit / Loss</h4>
                            <p class="text-secondary">Sales − COGS − Expenses = net profit, per date range.</p>
                            <a href="{{ url('/reports/profit') }}" class="btn btn-danger">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Open Report
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</main>

@endsection