@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Expenses</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Expenses</li>
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

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card text-bg-primary">
                        <div class="card-body">
                            <div class="small">Total Expenses (All Time)</div>
                            <h3 class="mb-0">{{ number_format($total, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline mb-4">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">All Expenses</h3>
                    <a href="{{ url('/expenses/create') }}" class="btn btn-sm btn-primary ms-auto">
                        <i class="bi bi-plus-lg me-1"></i> Add Expense
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th style="width: 180px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expenses as $expense)
                                <tr>
                                    <td>{{ $expenses->firstItem() + $loop->index }}</td>
                                    <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                                    <td>{{ $expense->title }}</td>
                                    <td>{{ $expense->category ?? '—' }}</td>
                                    <td>{{ ucfirst($expense->payment_method) }}</td>
                                    <td><strong>{{ number_format($expense->amount, 2) }}</strong></td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ url('/expenses/'.$expense->id.'/edit') }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <form action="{{ url('/expenses/'.$expense->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this expense?');">
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
                                    <td colspan="7" class="text-center text-secondary py-4">
                                        No expenses found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($expenses->hasPages())
                    <div class="card-footer">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</main>

@endsection