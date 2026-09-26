@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Edit Expense</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/expenses') }}">Expenses</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Expense</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ url('/expenses/'.$expense->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Expense Details</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="expense_date" class="form-label">Date <span class="text-danger">*</span></label>
                                        <input type="date" name="expense_date" id="expense_date"
                                               class="form-control @error('expense_date') is-invalid @enderror"
                                               value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                                        @error('expense_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <input type="text" name="category" id="category"
                                               class="form-control @error('category') is-invalid @enderror"
                                               value="{{ old('category', $expense->category) }}">
                                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title', $expense->title) }}" required>
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" name="amount" id="amount"
                                               class="form-control @error('amount') is-invalid @enderror"
                                               value="{{ old('amount', $expense->amount) }}" required>
                                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select name="payment_method" id="payment_method"
                                                class="form-select @error('payment_method') is-invalid @enderror" required>
                                            <option value="cash"  {{ old('payment_method', $expense->payment_method) == 'cash'  ? 'selected' : '' }}>Cash</option>
                                            <option value="bank"  {{ old('payment_method', $expense->payment_method) == 'bank'  ? 'selected' : '' }}>Bank</option>
                                            <option value="card"  {{ old('payment_method', $expense->payment_method) == 'card'  ? 'selected' : '' }}>Card</option>
                                            <option value="other" {{ old('payment_method', $expense->payment_method) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" id="notes" rows="3"
                                              class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $expense->notes) }}</textarea>
                                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Update Expense
                                </button>
                                <a href="{{ url('/expenses') }}" class="btn btn-outline-secondary ms-auto">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</main>

@endsection