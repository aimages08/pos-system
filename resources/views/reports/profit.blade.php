@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Profit / Loss Report</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/reports') }}">Reports</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profit / Loss</li>
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
                            <button type="submit" class="btn btn-primary"><i class="bi bi-filter me-1"></i> Filter</button>
                            <a href="{{ url('/reports/profit') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Loss Warning --}}
            @if ($summary['net_profit'] < 0)
                <div class="alert alert-warning d-flex align-items-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                    <div>
                        <strong>Loss this period.</strong>
                        Expenses ({{ number_format($summary['total_expenses'], 2) }})
                        exceed gross profit ({{ number_format($summary['gross_profit'], 2) }}).
                    </div>
                </div>
            @elseif ($summary['net_profit'] > 0)
                <div class="alert alert-success d-flex align-items-center mb-4">
                    <i class="bi bi-graph-up-arrow me-2 fs-4"></i>
                    <div>
                        <strong>Profit this period.</strong>
                        Net profit: {{ number_format($summary['net_profit'], 2) }}
                        ({{ number_format($summary['margin'], 2) }}% margin).
                    </div>
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card text-bg-primary">
                        <div class="card-body">
                            <div class="small">Total Sales</div>
                            <h4 class="mb-0">{{ number_format($summary['total_sales'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-warning">
                        <div class="card-body">
                            <div class="small">Cost of Goods Sold</div>
                            <h4 class="mb-0">{{ number_format($summary['cogs'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-info">
                        <div class="card-body">
                            <div class="small">Expenses</div>
                            <h4 class="mb-0">{{ number_format($summary['total_expenses'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card {{ $summary['net_profit'] >= 0 ? 'text-bg-success' : 'text-bg-danger' }}">
                        <div class="card-body">
                            <div class="small">{{ $summary['net_profit'] >= 0 ? 'Net Profit' : 'Net Loss' }}</div>
                            <h4 class="mb-0">{{ number_format(abs($summary['net_profit']), 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card card-primary card-outline h-100">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Summary ({{ $from }} → {{ $to }})</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td>Total Sales</td>
                                    <td class="text-end">{{ number_format($summary['total_sales'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td>− Cost of Goods Sold</td>
                                    <td class="text-end">- {{ number_format($summary['cogs'], 2) }}</td>
                                </tr>
                                <tr class="fw-bold border-top">
                                    <td>Gross Profit</td>
                                    <td class="text-end {{ $summary['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($summary['gross_profit'], 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>− Total Expenses</td>
                                    <td class="text-end">- {{ number_format($summary['total_expenses'], 2) }}</td>
                                </tr>
                                <tr class="fw-bold border-top fs-5">
                                    <td>{{ $summary['net_profit'] >= 0 ? 'Net Profit' : 'Net Loss' }}</td>
                                    <td class="text-end {{ $summary['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format(abs($summary['net_profit']), 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Profit Margin</td>
                                    <td class="text-end">
                                        @if ($summary['total_sales'] > 0)
                                            <span class="{{ $summary['margin'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($summary['margin'], 2) }}%
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-danger card-outline h-100">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Expense Breakdown</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($expenseBreakdown as $row)
                                        <tr>
                                            <td>{{ $row->category ?? 'Uncategorized' }}</td>
                                            <td class="text-end">{{ number_format($row->total, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center text-secondary py-3">No expenses in this range.</td></tr>
                                    @endforelse
                                </tbody>
                                @if ($expenseBreakdown->count() > 0)
                                    <tfoot>
                                        <tr class="fw-bold border-top">
                                            <td>Total</td>
                                            <td class="text-end">{{ number_format($summary['total_expenses'], 2) }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection