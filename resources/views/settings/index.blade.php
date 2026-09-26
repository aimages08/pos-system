@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Settings</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">Settings</li>
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

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ url('/settings') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Store Info --}}
                    <div class="col-md-6">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0"><i class="bi bi-shop"></i> Store Information</h3>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Store Name</label>
                                    <input type="text" name="store_name" class="form-control"
                                           value="{{ $settings['store_name'] ?? '' }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="store_address" rows="2" class="form-control">{{ $settings['store_address'] ?? '' }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="store_phone" class="form-control"
                                               value="{{ $settings['store_phone'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="store_email" class="form-control"
                                               value="{{ $settings['store_email'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Logo</label>
                                    <input type="file" name="store_logo" accept="image/*" class="form-control">
                                    @if (!empty($settings['store_logo']))
                                        <img src="{{ asset('storage/'.$settings['store_logo']) }}"
                                             class="mt-2 rounded" style="max-height: 80px;">
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Currency & Tax --}}
                    <div class="col-md-6">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0"><i class="bi bi-cash"></i> Currency & Tax</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Currency Symbol</label>
                                        <input type="text" name="currency_symbol" class="form-control"
                                               value="{{ $settings['currency_symbol'] ?? 'Rs.' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Symbol Position</label>
                                        <select name="currency_position" class="form-select">
                                            <option value="before" {{ ($settings['currency_position'] ?? '') == 'before' ? 'selected' : '' }}>
                                                Before (Rs. 100)
                                            </option>
                                            <option value="after"  {{ ($settings['currency_position'] ?? '') == 'after' ? 'selected' : '' }}>
                                                After (100 Rs.)
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Default Tax Rate (%)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="default_tax_rate"
                                               class="form-control"
                                               value="{{ $settings['default_tax_rate'] ?? 0 }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tax Mode</label>
                                        <select name="tax_inclusive" class="form-select">
                                            <option value="0" {{ ($settings['tax_inclusive'] ?? '0') == '0' ? 'selected' : '' }}>
                                                Exclusive (added to price)
                                            </option>
                                            <option value="1" {{ ($settings['tax_inclusive'] ?? '0') == '1' ? 'selected' : '' }}>
                                                Inclusive (already in price)
                                            </option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Receipt --}}
                    <div class="col-md-6">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0"><i class="bi bi-receipt"></i> Receipt</h3>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Receipt Header</label>
                                    <input type="text" name="receipt_header" class="form-control"
                                           value="{{ $settings['receipt_header'] ?? '' }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Receipt Footer</label>
                                    <input type="text" name="receipt_footer" class="form-control"
                                           value="{{ $settings['receipt_footer'] ?? '' }}">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Paper Size</label>
                                        <select name="receipt_paper" class="form-select">
                                            <option value="58mm" {{ ($settings['receipt_paper'] ?? '') == '58mm' ? 'selected' : '' }}>58mm</option>
                                            <option value="80mm" {{ ($settings['receipt_paper'] ?? '') == '80mm' ? 'selected' : '' }}>80mm</option>
                                            <option value="A4"   {{ ($settings['receipt_paper'] ?? '') == 'A4'   ? 'selected' : '' }}>A4</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Invoice Prefix</label>
                                        <input type="text" name="invoice_prefix" class="form-control"
                                               value="{{ $settings['invoice_prefix'] ?? 'INV-' }}">
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input type="hidden" name="show_logo_receipt" value="0">
                                    <input type="checkbox" name="show_logo_receipt" value="1" class="form-check-input"
                                           id="showLogo"
                                           {{ ($settings['show_logo_receipt'] ?? '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="showLogo">Show logo on receipt</label>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- POS Options --}}
                    <div class="col-md-6">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0"><i class="bi bi-upc-scan"></i> POS Options</h3>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Barcode Input Mode</label>
                                    <select name="barcode_input_mode" class="form-select">
                                        <option value="hardware" {{ ($settings['barcode_input_mode'] ?? '') == 'hardware' ? 'selected' : '' }}>
                                            🖨️ Hardware Scanner (USB/Bluetooth)
                                        </option>
                                        <option value="mobile"   {{ ($settings['barcode_input_mode'] ?? '') == 'mobile'   ? 'selected' : '' }}>
                                            📱 Mobile Camera
                                        </option>
                                        <option value="laptop"   {{ ($settings['barcode_input_mode'] ?? '') == 'laptop'   ? 'selected' : '' }}>
                                            💻 Laptop Camera
                                        </option>
                                        <option value="none"     {{ ($settings['barcode_input_mode'] ?? '') == 'none'     ? 'selected' : '' }}>
                                            ❌ Disabled
                                        </option>
                                    </select>
                                    <small class="text-secondary">Choose how barcodes are entered at the POS.</small>
                                </div>

                                <div class="form-check mb-2">
                                    <input type="hidden" name="allow_negative_stock" value="0">
                                    <input type="checkbox" name="allow_negative_stock" value="1" class="form-check-input"
                                           id="negStock"
                                           {{ ($settings['allow_negative_stock'] ?? '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="negStock">Allow negative stock</label>
                                </div>

                                <div class="form-check">
                                    <input type="hidden" name="round_off_total" value="0">
                                    <input type="checkbox" name="round_off_total" value="1" class="form-check-input"
                                           id="roundOff"
                                           {{ ($settings['round_off_total'] ?? '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="roundOff">Round off grand total</label>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Backup --}}
                    <div class="col-md-6">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0"><i class="bi bi-database-down"></i> Backup</h3>
                            </div>
                            <div class="card-body">

                                <div class="form-check mb-2">
                                    <input type="hidden" name="backup_enabled" value="0">
                                    <input type="checkbox" name="backup_enabled" value="1" class="form-check-input"
                                           id="backupEnabled"
                                           {{ ($settings['backup_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="backupEnabled">
                                        Enable automatic backups
                                    </label>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small">Frequency</label>
                                        <select name="backup_frequency" class="form-select form-select-sm">
                                            <option value="daily"   {{ ($settings['backup_frequency'] ?? '') == 'daily'   ? 'selected' : '' }}>Daily</option>
                                            <option value="weekly"  {{ ($settings['backup_frequency'] ?? '') == 'weekly'  ? 'selected' : '' }}>Weekly</option>
                                            <option value="monthly" {{ ($settings['backup_frequency'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small">Time</label>
                                        <input type="time" name="backup_time" class="form-control form-control-sm"
                                               value="{{ $settings['backup_time'] ?? '02:00' }}">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small">Keep backups for (days)</label>
                                    <input type="number" min="1" max="365" name="backup_retention_days"
                                           class="form-control form-control-sm"
                                           value="{{ $settings['backup_retention_days'] ?? 30 }}">
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small">Notify on</label>
                                    <select name="backup_notify_on" class="form-select form-select-sm">
                                        <option value="failure" {{ ($settings['backup_notify_on'] ?? '') == 'failure' ? 'selected' : '' }}>Failure only</option>
                                        <option value="always"  {{ ($settings['backup_notify_on'] ?? '') == 'always'  ? 'selected' : '' }}>Always</option>
                                        <option value="never"   {{ ($settings['backup_notify_on'] ?? '') == 'never'   ? 'selected' : '' }}>Never</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small">Email</label>
                                    <input type="email" name="backup_notify_email" class="form-control form-control-sm"
                                           value="{{ $settings['backup_notify_email'] ?? '' }}"
                                           placeholder="admin@shop.com">
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save me-1"></i> Save Settings
                    </button>
                </div>

            </form>

        </div>
    </div>
</main>

@endsection