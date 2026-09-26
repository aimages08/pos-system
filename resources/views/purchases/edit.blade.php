@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Edit Purchase</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/purchases') }}">Purchases</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Purchase</li>
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

            <form action="{{ url('/purchases/'.$purchase->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Purchase Details</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    {{-- Date --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                                        <input type="date" name="purchase_date" id="purchase_date"
                                               class="form-control @error('purchase_date') is-invalid @enderror"
                                               value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}" required>
                                        @error('purchase_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Supplier --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                        <select name="supplier_id" id="supplier_id"
                                                class="form-select @error('supplier_id') is-invalid @enderror" required>
                                            <option value="">-- Select Supplier --</option>
                                            @foreach ($suppliers as $sup)
                                                <option value="{{ $sup->id }}"
                                                    {{ old('supplier_id', $purchase->supplier_id) == $sup->id ? 'selected' : '' }}>
                                                    {{ $sup->name }}{{ $sup->company ? ' ('.$sup->company.')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Product --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                                        <select name="product_id" id="product_id"
                                                class="form-select @error('product_id') is-invalid @enderror" required>
                                            <option value="">-- Select Product --</option>
                                            @foreach ($products as $prod)
                                                <option value="{{ $prod->id }}"
                                                        data-cost="{{ $prod->cost_price }}"
                                                    {{ old('product_id', $purchase->product_id) == $prod->id ? 'selected' : '' }}>
                                                    {{ $prod->name }}{{ $prod->sku ? ' ('.$prod->sku.')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Quantity --}}
                                    <div class="col-md-3 mb-3">
                                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" min="1" name="quantity" id="quantity"
                                               class="form-control @error('quantity') is-invalid @enderror"
                                               value="{{ old('quantity', $purchase->quantity) }}" required>
                                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Cost --}}
                                    <div class="col-md-3 mb-3">
                                        <label for="cost_price" class="form-label">Cost/Unit <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" name="cost_price" id="cost_price"
                                               class="form-control @error('cost_price') is-invalid @enderror"
                                               value="{{ old('cost_price', $purchase->cost_price) }}" required>
                                        @error('cost_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                {{-- Notes --}}
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" id="notes" rows="3"
                                              class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $purchase->notes) }}</textarea>
                                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Total</h3>
                            </div>
                            <div class="card-body">
                                <h2 class="mb-0" id="total-display">{{ number_format($purchase->total, 2) }}</h2>
                                <small class="text-secondary">Quantity × Cost per unit</small>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Update Purchase
                                </button>
                                <a href="{{ url('/purchases') }}" class="btn btn-outline-secondary ms-auto">
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

<script>
    const qty = document.getElementById('quantity');
    const cost = document.getElementById('cost_price');
    const display = document.getElementById('total-display');

    function updateTotal() {
        const q = parseFloat(qty.value) || 0;
        const c = parseFloat(cost.value) || 0;
        display.textContent = (q * c).toFixed(2);
    }

    qty.addEventListener('input', updateTotal);
    cost.addEventListener('input', updateTotal);
    updateTotal();

    document.getElementById('product_id').addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        const c = opt.getAttribute('data-cost');
        if (c) {
            cost.value = parseFloat(c).toFixed(2);
            updateTotal();
        }
    });
</script>

@endsection