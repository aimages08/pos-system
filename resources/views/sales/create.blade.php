@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">New Sale</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/sales') }}">Sales</a></li>
                            <li class="breadcrumb-item active">New Sale</li>
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
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ url('/sales') }}" method="POST" id="sale-form">
                @csrf
                <div id="hidden-items"></div>

                <div class="row g-3">

                    {{-- ============ LEFT: PRODUCTS ============ --}}
                    <div class="col-lg-8">

                        {{-- BIG SEARCH BAR --}}
                        <div class="card shadow-sm mb-3">
                            <div class="card-body py-3">

                                @php $mode = setting('barcode_input_mode', 'hardware'); @endphp

                                @if ($mode !== 'none')
                                    <div class="input-group input-group-lg mb-3">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="bi bi-upc-scan"></i>
                                        </span>

                                        @if ($mode === 'hardware')
                                            <input type="text" id="barcode-input"
                                                   class="form-control"
                                                   placeholder="🔍 Scan barcode here..."
                                                   autocomplete="off">
                                        @else
                                            <input type="text" class="form-control" readonly
                                                   placeholder="Camera mode — click Scan →"
                                                   style="background:#f8f9fa;">
                                            <button type="button" class="btn btn-primary px-4" id="open-camera">
                                                <i class="bi bi-camera-video"></i> Scan
                                            </button>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <small id="scan-feedback" class="text-secondary"></small>
                                    </div>
                                @endif

                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                    <input type="text" id="product-search"
                                           class="form-control"
                                           placeholder="Search product by name, SKU, or barcode..."
                                           autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary" id="clear-search">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                <small class="text-secondary d-block mt-2" id="search-count"></small>

                            </div>
                        </div>

                        {{-- PRODUCT GRID --}}
                        <div id="product-list" class="row g-2" style="max-height: 620px; overflow-y: auto; padding: 4px;">
                            @foreach ($products as $prod)
                                <div class="col-6 col-md-4 col-xl-3 product-item"
                                     data-id="{{ $prod->id }}"
                                     data-name="{{ $prod->name }}"
                                     data-price="{{ $prod->selling_price }}"
                                     data-stock="{{ $prod->stock }}"
                                     data-sku="{{ $prod->sku }}"
                                     data-barcode="{{ $prod->barcode }}"
                                     data-expiry="{{ $prod->expiry_date?->format('Y-m-d') }}"
                                     style="cursor: pointer;">
                                    <div class="card h-100 shadow-sm product-card">
                                        {{-- Image --}}
                                        <div class="position-relative" style="height: 140px; overflow:hidden; background:#f8f9fa;">
                                            @if ($prod->image)
                                                <img src="{{ asset('storage/'.$prod->image) }}"
                                                     class="w-100 h-100" style="object-fit: cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100 text-secondary">
                                                    <i class="bi bi-image" style="font-size: 2.5rem;"></i>
                                                </div>
                                            @endif

                                            {{-- Stock badge --}}
                                            <span class="badge position-absolute top-0 start-0 m-2
                                                {{ $prod->stock <= 0 ? 'text-bg-danger' : ($prod->stock <= $prod->minimum_stock ? 'text-bg-warning' : 'text-bg-primary') }}">
                                                Stock: {{ $prod->stock }}
                                            </span>

                                            {{-- Expiry badge --}}
                                            @php
                                                $expiry = $prod->expiry_date;
                                                $isExpired = $expiry && $expiry->isPast();
                                                $isExpiringSoon = $expiry && !$isExpired && $expiry->diffInDays(now()) <= 30;
                                            @endphp

                                            @if ($isExpired)
                                                <span class="badge text-bg-danger position-absolute top-0 end-0 m-2">
                                                    <i class="bi bi-x-circle"></i> EXPIRED
                                                </span>
                                            @elseif ($isExpiringSoon)
                                                <span class="badge text-bg-warning position-absolute top-0 end-0 m-2">
                                                    <i class="bi bi-exclamation-triangle"></i> {{ $expiry->diffInDays(now()) }}d
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Body --}}
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-truncate" style="font-size: 0.85rem;"
                                                 title="{{ $prod->name }}">
                                                {{ $prod->name }}
                                            </div>
                                            <small class="text-secondary d-block text-truncate">
                                                {{ $prod->sku ?? '—' }}
                                            </small>
                                            <div class="fw-bold text-primary mt-1" style="font-size: 1rem;">
                                                Rs {{ number_format($prod->selling_price, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    {{-- ============ RIGHT: CART + PAYMENT ============ --}}
                    <div class="col-lg-4">

                        {{-- Sale Info --}}
                        <div class="card card-primary card-outline mb-3 shadow-sm">
                            <div class="card-header py-2">
                                <h3 class="card-title mb-0"><i class="bi bi-info-circle"></i> Sale Info</h3>
                            </div>
                            <div class="card-body py-2">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label mb-1 small">Date <span class="text-danger">*</span></label>
                                        <input type="date" name="sale_date" id="sale_date"
                                               class="form-control form-control-sm"
                                               value="{{ old('sale_date', date('Y-m-d')) }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mb-1 small">Customer</label>
                                        <select name="customer_id" id="customer_id" class="form-select form-select-sm">
                                            <option value="">-- Walk-in --</option>
                                            @foreach ($customers as $c)
                                                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                                    {{ $c->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Cart --}}
                        <div class="card card-primary card-outline mb-3 shadow-sm">
                            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0"><i class="bi bi-cart"></i> Cart</h3>
                                <span class="badge text-bg-primary" id="cart-count">0 items</span>
                            </div>
                            <div class="card-body p-0">
                                <div style="max-height: 300px; overflow-y: auto;">
                                    <table class="table table-sm table-hover mb-0 align-middle">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th style="font-size: 0.75rem;">Product</th>
                                                <th style="font-size: 0.75rem;">Qty</th>
                                                <th style="font-size: 0.75rem;" class="text-end">Total</th>
                                                <th style="width: 30px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="cart-body">
                                            <tr id="cart-empty">
                                                <td colspan="4" class="text-center text-secondary py-4">
                                                    <i class="bi bi-cart-x" style="font-size: 1.5rem;"></i>
                                                    <div class="small mt-1">Cart is empty</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Payment --}}
                        <div class="card card-primary card-outline mb-3 shadow-sm">
                            <div class="card-header py-2">
                                <h3 class="card-title mb-0"><i class="bi bi-credit-card"></i> Payment</h3>
                            </div>
                            <div class="card-body py-2">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label mb-1 small">Discount</label>
                                        <input type="number" step="0.01" min="0" name="discount" id="inv-discount"
                                               class="form-control form-control-sm" value="{{ old('discount', 0) }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mb-1 small">Tax</label>
                                        <input type="number" step="0.01" min="0" name="tax" id="inv-tax"
                                               class="form-control form-control-sm" value="{{ old('tax', 0) }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mb-1 small">Method</label>
                                        <select name="payment_method" id="payment_method" class="form-select form-select-sm" required>
                                            <option value="cash">Cash</option>
                                            <option value="card">Card</option>
                                            <option value="bank">Bank</option>
                                            <option value="credit">Credit</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mb-1 small">Paid</label>
                                        <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount"
                                               class="form-control form-control-sm" value="{{ old('paid_amount', 0) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label mb-1 small">Notes</label>
                                        <input type="text" name="notes" id="notes" class="form-control form-control-sm" value="{{ old('notes') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Totals --}}
                        <div class="card card-success card-outline mb-3 shadow-sm">
                            <div class="card-body py-2">
                                <table class="table table-sm mb-0" style="font-size: 0.9rem;">
                                    <tr>
                                        <td class="text-secondary">Subtotal</td>
                                        <td class="text-end"><strong id="sum-subtotal">0.00</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-secondary">Discount</td>
                                        <td class="text-end text-danger">- <span id="sum-discount">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-secondary">Tax</td>
                                        <td class="text-end">+ <span id="sum-tax">0.00</span></td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="fw-bold">Grand Total</td>
                                        <td class="text-end fw-bold fs-5 text-success" id="sum-grand">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-secondary">Paid</td>
                                        <td class="text-end" id="sum-paid">0.00</td>
                                    </tr>
                                    <tr class="text-danger">
                                        <td class="fw-bold">Due</td>
                                        <td class="text-end fw-bold" id="sum-due">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-secondary">Status</td>
                                        <td class="text-end">
                                            <span id="sum-status" class="badge text-bg-danger">Unpaid</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg" id="save-btn" disabled>
                                <i class="bi bi-check-circle me-1"></i> Complete Sale
                            </button>
                            <a href="{{ url('/sales') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</main>

{{-- Camera scanner modal --}}
@if (in_array(setting('barcode_input_mode', 'hardware'), ['mobile', 'laptop']))
    <div class="modal fade" id="scannerModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-camera-video"></i> Scan Barcode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="reader" style="width: 100%;"></div>
                    <div class="text-center mt-2">
                        <small class="text-secondary">
                            Keep scanning items — camera stays open. Click <strong>Done</strong> when finished.
                        </small>
                    </div>
                    <div id="recent-scans" class="mt-2" style="max-height: 140px; overflow-y: auto;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="bi bi-check-lg"></i> Done Scanning
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
@endif

<style>
    .product-card { transition: all 0.15s ease; }
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.12) !important;
        border-color: #0d6efd;
    }
    .product-item.hidden { display: none !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let cart = [];

    const cartBody    = document.getElementById('cart-body');
    const cartCount   = document.getElementById('cart-count');
    const hiddenItems = document.getElementById('hidden-items');
    const saveBtn     = document.getElementById('save-btn');
    const invDiscount = document.getElementById('inv-discount');
    const invTax      = document.getElementById('inv-tax');
    const paidInput   = document.getElementById('paid_amount');
    const searchInput = document.getElementById('product-search');
    const clearBtn    = document.getElementById('clear-search');
    const searchCount = document.getElementById('search-count');

    // =================== PRODUCT CLICK ===================
    document.querySelectorAll('.product-item').forEach(el => {
        el.addEventListener('click', () => {
            // ← Block expired products
            const expiry = el.dataset.expiry;
            if (expiry && new Date(expiry) < new Date()) {
                alert('❌ This product is EXPIRED (expired on ' + expiry + ') and cannot be sold.');
                return;
            }

            addToCart({
                id:       parseInt(el.dataset.id),
                name:     el.dataset.name,
                price:    parseFloat(el.dataset.price),
                stock:    parseInt(el.dataset.stock),
                qty:      1,
                discount: 0,
                tax:      0,
            });
        });
    });

    // =================== SMART SEARCH ===================
    function normalize(str) {
        return (str || '').toString().toLowerCase().replace(/[\s_\-]+/g, '');
    }

    function filterProducts() {
        const raw = (searchInput.value || '').trim();
        const q   = normalize(raw);
        const items = document.querySelectorAll('.product-item');
        let visible = 0;

        items.forEach(el => {
            const name    = normalize(el.dataset.name);
            const sku     = normalize(el.dataset.sku);
            const barcode = normalize(el.dataset.barcode);

            const match = q === '' || name.includes(q) || sku.includes(q) || barcode.includes(q);
            el.classList.toggle('hidden', !match);
            if (match) visible++;
        });

        searchCount.textContent = q === ''
            ? `${items.length} products`
            : `${visible} of ${items.length} products match "${raw}"`;

        let noRes = document.getElementById('no-results-msg');
        const list = document.getElementById('product-list');
        if (visible === 0 && items.length > 0) {
            if (!noRes) {
                noRes = document.createElement('div');
                noRes.id = 'no-results-msg';
                noRes.className = 'col-12 text-center text-secondary py-5';
                noRes.innerHTML = '<i class="bi bi-search" style="font-size:3rem;"></i><br>No products found';
                list.appendChild(noRes);
            }
            noRes.style.display = '';
        } else if (noRes) {
            noRes.style.display = 'none';
        }
    }

    searchInput.addEventListener('input', filterProducts);
    searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });
    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        filterProducts();
        searchInput.focus();
    });
    filterProducts();

    // =================== CART LOGIC ===================
    function addToCart(item) {
        const existing = cart.find(c => c.id === item.id);
        if (existing) {
            if (existing.qty + 1 > item.stock) {
                alert('Not enough stock. Available: ' + item.stock);
                return;
            }
            existing.qty++;
        } else {
            cart.push(item);
        }
        renderCart();
    }

    function renderCart() {
        cartBody.innerHTML = '';

        if (cart.length === 0) {
            cartBody.innerHTML = `<tr><td colspan="4" class="text-center text-secondary py-4">
                <i class="bi bi-cart-x" style="font-size: 1.5rem;"></i>
                <div class="small mt-1">Cart is empty</div>
            </td></tr>`;
            saveBtn.disabled = true;
            cartCount.textContent = '0 items';
            updateTotals();
            return;
        }

        cart.forEach((item, i) => {
            const afterDisc = (item.qty * item.price) - item.discount;
            const lineTotal = afterDisc + (afterDisc * item.tax / 100);

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="fw-bold" style="font-size: 0.8rem;">${item.name}</div>
                    <small class="text-secondary">Rs ${item.price.toFixed(2)}</small>
                </td>
                <td>
                    <input type="number" min="1" max="${item.stock}" value="${item.qty}"
                           class="form-control form-control-sm text-center" data-idx="${i}" data-field="qty"
                           style="width: 55px;">
                </td>
                <td class="text-end fw-bold">${lineTotal.toFixed(2)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1"
                            data-idx="${i}" data-field="remove">
                        <i class="bi bi-x"></i>
                    </button>
                </td>
            `;
            cartBody.appendChild(tr);
        });

        cartCount.textContent = cart.length + (cart.length === 1 ? ' item' : ' items');

        cartBody.querySelectorAll('input[data-field]').forEach(inp => {
            inp.addEventListener('input', function () {
                const idx = parseInt(this.dataset.idx);
                const field = this.dataset.field;
                let val = parseFloat(this.value) || 0;

                if (field === 'qty') {
                    if (val < 1) val = 1;
                    if (val > cart[idx].stock) {
                        alert('Not enough stock. Available: ' + cart[idx].stock);
                        val = cart[idx].stock;
                        this.value = val;
                    }
                }
                cart[idx][field] = val;
                renderCart();
            });
        });

        cartBody.querySelectorAll('button[data-field="remove"]').forEach(btn => {
            btn.addEventListener('click', function () {
                cart.splice(parseInt(this.dataset.idx), 1);
                renderCart();
            });
        });

        saveBtn.disabled = false;
        updateTotals();
    }

    function updateTotals() {
        let subtotal = 0;
        cart.forEach(item => {
            const afterDisc = (item.qty * item.price) - item.discount;
            subtotal += afterDisc + (afterDisc * item.tax / 100);
        });

        const disc  = parseFloat(invDiscount.value) || 0;
        const tax   = parseFloat(invTax.value) || 0;
        const grand = subtotal - disc + tax;
        const paid  = parseFloat(paidInput.value) || 0;
        const due   = Math.max(0, grand - paid);

        document.getElementById('sum-subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('sum-discount').textContent = disc.toFixed(2);
        document.getElementById('sum-tax').textContent      = tax.toFixed(2);
        document.getElementById('sum-grand').textContent    = grand.toFixed(2);
        document.getElementById('sum-paid').textContent     = paid.toFixed(2);
        document.getElementById('sum-due').textContent      = due.toFixed(2);

        const statusEl = document.getElementById('sum-status');
        if (due <= 0) {
            statusEl.className = 'badge text-bg-success';
            statusEl.textContent = 'Paid';
        } else if (paid > 0) {
            statusEl.className = 'badge text-bg-warning';
            statusEl.textContent = 'Partial';
        } else {
            statusEl.className = 'badge text-bg-danger';
            statusEl.textContent = 'Unpaid';
        }

        buildHiddenInputs();
    }

    function buildHiddenInputs() {
        hiddenItems.innerHTML = '';
        cart.forEach((item, i) => {
            hiddenItems.innerHTML += `
                <input type="hidden" name="items[${i}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${i}][quantity]" value="${item.qty}">
                <input type="hidden" name="items[${i}][selling_price]" value="${item.price}">
                <input type="hidden" name="items[${i}][discount]" value="${item.discount}">
                <input type="hidden" name="items[${i}][tax]" value="${item.tax}">
            `;
        });
    }

    invDiscount.addEventListener('input', updateTotals);
    invTax.addEventListener('input', updateTotals);
    paidInput.addEventListener('input', updateTotals);
    updateTotals();

    // =================== PAYMENT METHOD ===================
    document.getElementById('payment_method').addEventListener('change', function () {
        if (this.value === 'credit') {
            paidInput.value = 0;
            paidInput.setAttribute('readonly', true);
            paidInput.classList.add('bg-light');
        } else {
            paidInput.removeAttribute('readonly');
            paidInput.classList.remove('bg-light');
        }
        updateTotals();
    });

    // =================== BARCODE SCANNER ===================
    window.addToCartExternal = addToCart;

    const feedback       = document.getElementById('scan-feedback');
    const barcodeInput   = document.getElementById('barcode-input');
    const openCameraBtn  = document.getElementById('open-camera');
    const scannerModalEl = document.getElementById('scannerModal');

    let recentScans = [];

    function beep(freq = 800, dur = 100) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain); gain.connect(ctx.destination);
            osc.frequency.value = freq; osc.type = 'sine'; gain.gain.value = 0.1;
            osc.start(); setTimeout(() => osc.stop(), dur);
        } catch (e) {}
    }

    function showFeedback(msg, type = 'success') {
        if (!feedback) return;
        feedback.textContent = msg;
        feedback.className = 'text-' + (type === 'success' ? 'success' : 'danger') + ' fw-bold';
        setTimeout(() => { feedback.textContent = ''; feedback.className = 'text-secondary'; }, 2500);
    }

    function addRecentScan(name, success = true) {
        const el = document.getElementById('recent-scans');
        if (!el) return;
        recentScans.unshift({ name, success });
        if (recentScans.length > 6) recentScans.pop();
        el.innerHTML = recentScans.map(s => `
            <div class="border rounded p-2 mb-1 small ${s.success ? 'border-success text-success' : 'border-danger text-danger'}"
                 style="background:${s.success ? '#f0fff4' : '#fff0f0'};">
                <i class="bi bi-${s.success ? 'check-circle' : 'x-circle'}"></i> ${s.name}
            </div>
        `).join('');
    }

    async function lookupBarcode(code) {
        if (!code) return;
        try {
            const res = await fetch(`/products/by-barcode/${encodeURIComponent(code)}`);
            if (!res.ok) {
                beep(300, 200);
                showFeedback(`❌ Not found: ${code}`, 'error');
                addRecentScan(`Not found: ${code}`, false);
                return;
            }
            const data = await res.json();
            const p = data.product;

            // ← Block expired products from scan
            if (p.expiry_date && new Date(p.expiry_date) < new Date()) {
                beep(300, 200);
                showFeedback(`❌ EXPIRED: ${p.name}`, 'error');
                addRecentScan(`Expired: ${p.name}`, false);
                return;
            }

            beep(1000, 80);
            window.addToCartExternal({
                id: p.id, name: p.name, price: p.selling_price,
                stock: p.stock, qty: 1, discount: 0, tax: 0,
            });
            showFeedback(`✅ Added: ${p.name}`, 'success');
            addRecentScan(p.name, true);
        } catch (err) {
            beep(300, 200);
            showFeedback('❌ Lookup error', 'error');
        }
    }

    if (barcodeInput) {
        barcodeInput.focus();
        barcodeInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const code = this.value.trim();
                this.value = '';
                lookupBarcode(code);
            }
        });
    }

    let html5QrCode = null;
    if (openCameraBtn && scannerModalEl) {
        const modal = new bootstrap.Modal(scannerModalEl);

        openCameraBtn.addEventListener('click', () => {
            modal.show();
            recentScans = [];
            const rs = document.getElementById('recent-scans');
            if (rs) rs.innerHTML = '';

            html5QrCode = new Html5Qrcode("reader");
            const config = {
                fps: 15,
                qrbox: (w, h) => ({ width: Math.floor(w * 0.95), height: Math.floor(h * 0.75) }),
                experimentalFeatures: { useBarCodeDetectorIfSupported: true }
            };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    const now = Date.now();
                    if (window._lastScan === decodedText && now - window._lastScanTime < 1500) return;
                    window._lastScan = decodedText;
                    window._lastScanTime = now;
                    beep(1000, 80);
                    lookupBarcode(decodedText);
                },
                () => {}
            ).catch(err => {
                showFeedback('❌ Camera error: ' + err, 'error');
                modal.hide();
            });
        });

        scannerModalEl.addEventListener('hidden.bs.modal', () => {
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
                html5QrCode = null;
            }
        });
    }

});
</script>

@endsection