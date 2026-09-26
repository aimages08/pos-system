@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Edit Product</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/products') }}">Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
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

            <form action="{{ url('/products/'.$product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Left column --}}
                    <div class="col-md-8">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Product Information</h3>
                            </div>
                            <div class="card-body">

                                {{-- Name --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $product->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="row">
                                    {{-- Category --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select name="category_id" id="category_id"
                                                class="form-select @error('category_id') is-invalid @enderror">
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Unit --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="unit" class="form-label">Unit</label>
                                        <input type="text" name="unit" id="unit"
                                               class="form-control @error('unit') is-invalid @enderror"
                                               value="{{ old('unit', $product->unit) }}"
                                               placeholder="e.g. pcs, kg, box">
                                        @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- SKU --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="sku" class="form-label">SKU</label>
                                        <input type="text" name="sku" id="sku"
                                               class="form-control @error('sku') is-invalid @enderror"
                                               value="{{ old('sku', $product->sku) }}">
                                        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Barcode --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="barcode" class="form-label">Barcode</label>
                                        <div class="input-group">
                                            <input type="text" name="barcode" id="barcode"
                                                   class="form-control @error('barcode') is-invalid @enderror"
                                                   value="{{ old('barcode', $product->barcode) }}"
                                                   placeholder="Scan or type barcode">
                                            <button type="button" class="btn btn-primary" id="scan-barcode-btn"
                                                    title="Scan with camera">
                                                <i class="bi bi-camera-video"></i> Scan
                                            </button>
                                        </div>
                                        @error('barcode') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        <small id="barcode-status" class="d-block mt-1"></small>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" rows="3"
                                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            </div>
                        </div>

                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Pricing & Stock</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="cost_price" class="form-label">Cost Price <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" name="cost_price" id="cost_price"
                                               class="form-control @error('cost_price') is-invalid @enderror"
                                               value="{{ old('cost_price', $product->cost_price) }}" required>
                                        @error('cost_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="selling_price" class="form-label">Selling Price <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" name="selling_price" id="selling_price"
                                               class="form-control @error('selling_price') is-invalid @enderror"
                                               value="{{ old('selling_price', $product->selling_price) }}" required>
                                        @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="wholesale_price" class="form-label">Wholesale Price</label>
                                        <input type="number" step="0.01" min="0" name="wholesale_price" id="wholesale_price"
                                               class="form-control @error('wholesale_price') is-invalid @enderror"
                                               value="{{ old('wholesale_price', $product->wholesale_price) }}">
                                        @error('wholesale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                        <input type="number" min="0" name="stock" id="stock"
                                               class="form-control @error('stock') is-invalid @enderror"
                                               value="{{ old('stock', $product->stock) }}" required>
                                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="minimum_stock" class="form-label">Minimum Stock <span class="text-danger">*</span></label>
                                        <input type="number" min="0" name="minimum_stock" id="minimum_stock"
                                               class="form-control @error('minimum_stock') is-invalid @enderror"
                                               value="{{ old('minimum_stock', $product->minimum_stock) }}" required>
                                        @error('minimum_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="expiry_date" class="form-label">Expiry Date</label>
                                        <input type="date" name="expiry_date" id="expiry_date"
                                               class="form-control @error('expiry_date') is-invalid @enderror"
                                               value="{{ old('expiry_date', $product->expiry_date?->format('Y-m-d')) }}">
                                        @error('expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <small class="text-secondary">Optional — for food, medicine, cosmetics</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tax" class="form-label">Tax (%)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="tax" id="tax"
                                               class="form-control @error('tax') is-invalid @enderror"
                                               value="{{ old('tax', $product->tax) }}">
                                        @error('tax') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="discount" class="form-label">Discount (%)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="discount" id="discount"
                                               class="form-control @error('discount') is-invalid @enderror"
                                               value="{{ old('discount', $product->discount) }}">
                                        @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Right column --}}
                    <div class="col-md-4">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Image & Status</h3>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    <input type="file" name="image" id="image" accept="image/*"
                                           class="form-control @error('image') is-invalid @enderror"
                                           onchange="previewImage(event)">
                                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                    <img id="image-preview"
                                         src="{{ $product->image ? asset('storage/'.$product->image) : '' }}"
                                         alt=""
                                         class="mt-3 rounded {{ $product->image ? '' : 'd-none' }}"
                                         style="width: 100%; max-height: 220px; object-fit: cover;">
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="active"   {{ old('status', $product->status) == 'active'   ? 'selected' : '' }}>Active</option>
                                        <option value="disabled" {{ old('status', $product->status) == 'disabled' ? 'selected' : '' }}>Disabled</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Update Product
                                </button>
                                <a href="{{ url('/products') }}" class="btn btn-outline-secondary ms-auto">
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

{{-- Scanner modal --}}
<div class="modal fade" id="scannerModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title"><i class="bi bi-camera-video"></i> Scan Barcode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="reader"></div>
                <div class="text-center mt-2">
                    <small class="text-secondary">
                        Point camera at barcode. Scans once, then closes.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
function previewImage(event) {
    const img = document.getElementById('image-preview');
    const file = event.target.files[0];
    if (file) {
        img.src = URL.createObjectURL(file);
        img.classList.remove('d-none');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const barcodeInput   = document.getElementById('barcode');
    const scanBtn        = document.getElementById('scan-barcode-btn');
    const statusEl       = document.getElementById('barcode-status');
    const scannerModalEl = document.getElementById('scannerModal');
    const currentProductId = {{ $product->id }};

    let html5QrCode = null;
    let checkTimer  = null;

    // ============ Duplicate check ============
    function checkBarcode(code) {
        if (!code) { statusEl.innerHTML = ''; return; }
        statusEl.innerHTML = '<i class="bi bi-hourglass-split text-secondary"></i> Checking...';

        fetch(`/products/check-barcode/${encodeURIComponent(code)}?exclude=${currentProductId}`)
            .then(r => r.json())
            .then(data => {
                if (data.exists) {
                    statusEl.innerHTML = `<span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Already used by: <strong>${data.product.name}</strong></span>`;
                } else {
                    statusEl.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Barcode is available</span>';
                }
            })
            .catch(() => {
                statusEl.innerHTML = '<span class="text-secondary">Could not check</span>';
            });
    }

    barcodeInput.addEventListener('input', function () {
        clearTimeout(checkTimer);
        const code = this.value.trim();
        checkTimer = setTimeout(() => checkBarcode(code), 500);
    });

    if (barcodeInput.value.trim()) checkBarcode(barcodeInput.value.trim());

    // ============ Camera scanner ============
    if (scanBtn) {
        const modal = new bootstrap.Modal(scannerModalEl);

        scanBtn.addEventListener('click', () => {
            modal.show();
            html5QrCode = new Html5Qrcode("reader");

            const config = {
                fps: 30,
                qrbox: (viewfinderWidth, viewfinderHeight) => {
                    return {
                        width: Math.floor(viewfinderWidth * 0.95),
                        height: Math.floor(viewfinderHeight * 0.75),
                    };
                },
                aspectRatio: 1.777,
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.EAN_8,
                    Html5QrcodeSupportedFormats.UPC_A,
                    Html5QrcodeSupportedFormats.UPC_E,
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.CODE_39,
                    Html5QrcodeSupportedFormats.ITF,
                    Html5QrcodeSupportedFormats.QR_CODE,
                ],
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                },
                videoConstraints: {
                    facingMode: "environment",
                    width:  { ideal: 1920 },
                    height: { ideal: 1080 },
                    focusMode: "continuous",
                }
            };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    barcodeInput.value = decodedText;
                    checkBarcode(decodedText);

                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.connect(gain); gain.connect(ctx.destination);
                        osc.frequency.value = 1000; osc.type = 'sine'; gain.gain.value = 0.1;
                        osc.start(); setTimeout(() => osc.stop(), 100);
                    } catch (e) {}

                    html5QrCode.stop().then(() => { modal.hide(); barcodeInput.focus(); });
                },
                () => {}
            ).catch(err => { alert('Camera error: ' + err); modal.hide(); });
        });

        scannerModalEl.addEventListener('hidden.bs.modal', () => {
            if (html5QrCode) { html5QrCode.stop().catch(() => {}); html5QrCode = null; }
        });
    }
});
</script>

@endsection