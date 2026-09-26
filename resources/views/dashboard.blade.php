@extends('layouts.app')

@section('content')

<main class="app-main">

    {{-- Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @php
                $can = fn($p) => Auth::user()->hasPermission($p);
            @endphp

            {{-- ===== Top KPI Row ===== --}}
            <div class="row">

                {{-- Today's Sales — needs sales.view --}}
                @if ($can('sales.view'))
                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-primary">
                            <div class="inner">
                                <h3>{{ number_format($todaySales, 0) }}</h3>
                                <p>Today's Sales</p>
                            </div>
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25z"/>
                            </svg>
                            <a href="{{ url('/sales') }}" class="small-box-footer link-light link-underline-opacity-0">
                                {{ $todaySaleCount }} invoices today <i class="bi bi-arrow-right-circle"></i>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Receivable — needs customer-payments.view --}}
                @if ($can('customer-payments.view'))
                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-success">
                            <div class="inner">
                                <h3>{{ number_format($totalReceivable, 0) }}</h3>
                                <p>Total Receivable (Udhaar)</p>
                            </div>
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v.816a3.836 3.836 0 00-1.72.756c-.712.566-1.112 1.35-1.112 2.178 0 .829.4 1.612 1.113 2.178.502.4 1.102.647 1.719.756v2.978a2.536 2.536 0 01-.921-.43.75.75 0 00-.964 1.15 4.037 4.037 0 002.385.98V18a.75.75 0 001.5 0v-.816a3.837 3.837 0 001.72-.756c.712-.566 1.112-1.35 1.112-2.178 0-.829-.4-1.612-1.113-2.178a3.836 3.836 0 00-1.719-.756V8.334a2.535 2.535 0 01.921.43.75.75 0 00.964-1.15 4.037 4.037 0 00-2.385-.98V6z"/>
                            </svg>
                            <a href="{{ url('/customer-payments') }}" class="small-box-footer link-light link-underline-opacity-0">
                                View payments <i class="bi bi-arrow-right-circle"></i>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Payable — needs suppliers.view or purchases.view --}}
                @if ($can('suppliers.view') || $can('purchases.view'))
                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-warning">
                            <div class="inner">
                                <h3>{{ number_format($totalPayable, 0) }}</h3>
                                <p>Payable to Suppliers</p>
                            </div>
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15zM22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5z"/>
                            </svg>
                            <a href="{{ url('/suppliers') }}" class="small-box-footer link-dark link-underline-opacity-0">
                                View suppliers <i class="bi bi-arrow-right-circle"></i>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Low Stock — needs products.view or reports.view --}}
                @if ($can('products.view') || $can('reports.view'))
                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-danger">
                            <div class="inner">
                                <h3>{{ $lowStockCount }}</h3>
                                <p>Low Stock Products</p>
                            </div>
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 7.5a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5zM12 15.75a.75.75 0 100 1.5.75.75 0 000-1.5z"/>
                            </svg>
                            <a href="{{ url('/reports/stock') }}" class="small-box-footer link-light link-underline-opacity-0">
                                View stock report <i class="bi bi-arrow-right-circle"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ===== Second KPI Row ===== --}}
            <div class="row">

                @if ($can('products.view'))
                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-info">
                            <div class="inner">
                                <h3>{{ $totalProducts }}</h3>
                                <p>Total Products</p>
                            </div>
                            <a href="{{ url('/products') }}" class="small-box-footer link-dark link-underline-opacity-0">
                                Manage <i class="bi bi-arrow-right-circle"></i>
                            </a>
                        </div>
                    </div>
                @endif

                @if ($can('customers.view'))
                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-secondary">
                            <div class="inner">
                                <h3>{{ $totalCustomers }}</h3>
                                <p>Total Customers</p>
                            </div>
                            <a href="{{ url('/customers') }}" class="small-box-footer link-light link-underline-opacity-0">
                                Manage <i class="bi bi-arrow-right-circle"></i>
                            </a>
                        </div>
                    </div>
                @endif

                @if ($can('suppliers.view'))
                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-dark">
                            <div class="inner">
                                <h3>{{ $totalSuppliers }}</h3>
                                <p>Total Suppliers</p>
                            </div>
                            <a href="{{ url('/suppliers') }}" class="small-box-footer link-light link-underline-opacity-0">
                                Manage <i class="bi bi-arrow-right-circle"></i>
                            </a>
                        </div>
                    </div>
                @endif

                @if ($can('expenses.view'))
                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-primary">
                            <div class="inner">
                                <h3>{{ number_format($todayExpenses, 0) }}</h3>
                                <p>Today's Expenses</p>
                            </div>
                            <a href="{{ url('/expenses') }}" class="small-box-footer link-light link-underline-opacity-0">
                                View expenses <i class="bi bi-arrow-right-circle"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ===== Month summary + Chart ===== --}}
            @if ($can('sales.view') || $can('expenses.view'))
                <div class="row">
                    @if ($can('sales.view'))
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">Sales — Last 7 Days</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="salesChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="{{ $can('sales.view') ? 'col-lg-4' : 'col-lg-12' }}">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">This Month</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm mb-0">
                                    @if ($can('sales.view'))
                                        <tr>
                                            <td>Sales</td>
                                            <td class="text-end fw-bold">{{ number_format($monthSales, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if ($can('expenses.view'))
                                        <tr>
                                            <td>Expenses</td>
                                            <td class="text-end fw-bold text-danger">{{ number_format($monthExpenses, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if ($can('sales.view'))
                                        <tr>
                                            <td>Today's Sales</td>
                                            <td class="text-end">{{ number_format($todaySales, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Today's Paid</td>
                                            <td class="text-end text-success">{{ number_format($todayPaid, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Today's Due</td>
                                            <td class="text-end text-warning">{{ number_format($todayDue, 2) }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ===== Low stock + Recent sales ===== --}}
            <div class="row">

                @if ($can('products.view') || $can('reports.view'))
                    <div class="col-lg-6">
                        <div class="card card-danger card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0">⚠ Low Stock Products</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-end">Stock</th>
                                            <th class="text-end">Min</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($lowStockProducts as $p)
                                            <tr>
                                                <td>
                                                    <a href="{{ url('/products/'.$p->id.'/edit') }}">{{ $p->name }}</a>
                                                </td>
                                                <td class="text-end text-danger fw-bold">{{ $p->stock }}</td>
                                                <td class="text-end">{{ $p->minimum_stock }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center text-secondary py-3">All stock levels are fine ✅</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($can('sales.view'))
                    <div class="col-lg-6">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Recent Sales</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-end">Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentSales as $s)
                                            <tr>
                                                <td><a href="{{ url('/sales/'.$s->id) }}">{{ $s->invoice_number }}</a></td>
                                                <td>{{ $s->customer->name ?? 'Walk-in' }}</td>
                                                <td class="text-end">{{ number_format($s->grand_total, 2) }}</td>
                                                <td class="text-end {{ $s->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($s->due_amount, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-secondary py-3">No sales yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</main>

{{-- Chart.js --}}
@if ($can('sales.view'))
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Sales',
                    data: @json($chartData),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.15)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    });
    </script>
@endif

@endsection