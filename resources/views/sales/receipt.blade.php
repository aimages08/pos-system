<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt — {{ $sale->invoice_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            margin: 0;
            padding: 10px;
            background: #f5f5f5;
        }

        .receipt {
            max-width: 380px;              /* ~80mm */
            margin: 0 auto;
            background: #fff;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }

        .store-name  { font-size: 18px; font-weight: bold; margin-bottom: 3px; }
        .store-info  { font-size: 11px; line-height: 1.4; }

        .divider {
            border-top: 1px dashed #333;
            margin: 8px 0;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 3px 0; font-size: 12px; vertical-align: top; }
        th { border-bottom: 1px solid #333; text-align: left; }

        .totals td { padding: 2px 0; }
        .totals .grand { font-size: 16px; font-weight: bold; border-top: 1px solid #333; padding-top: 5px; }

        .footer { font-size: 11px; margin-top: 10px; text-align: center; }

        /* ============ PRINT STYLES ============ */
        @media print {
            body { background: #fff; padding: 0; }
            .receipt {
                box-shadow: none;
                max-width: 100%;
                border-radius: 0;
                padding: 0;
            }
            .no-print { display: none !important; }
        }

        /* ============ SCREEN CONTROLS ============ */
        .controls {
            max-width: 380px;
            margin: 0 auto 10px;
            display: flex;
            gap: 8px;
            justify-content: space-between;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-primary { background: #0d6efd; color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>

{{-- ============ SCREEN-ONLY CONTROLS ============ --}}
<div class="controls no-print">
    <button class="btn btn-primary" onclick="window.print()">
        🖨️ Print
    </button>
    <button class="btn btn-secondary" onclick="window.close(); history.back();">
        ✖ Close
    </button>
</div>

{{-- ============ RECEIPT ============ --}}
<div class="receipt">

    {{-- Store header --}}
    <div class="center">
        @if (setting('receipt_show_logo', '1') == '1' && setting('store_logo'))
            <img src="{{ asset('storage/'.setting('store_logo')) }}"
                 alt="Logo" style="max-height: 50px; margin-bottom: 6px;">
        @endif

        <div class="store-name">{{ setting('store_name', 'My POS Shop') }}</div>

        <div class="store-info">
            @if (setting('store_address'))
                {{ setting('store_address') }}<br>
            @endif
            @if (setting('store_phone'))
                Phone: {{ setting('store_phone') }}<br>
            @endif
            @if (setting('store_email'))
                {{ setting('store_email') }}
            @endif
        </div>
    </div>

    <div class="divider"></div>

    {{-- Invoice info --}}
    <table>
        <tr>
            <td>Invoice:</td>
            <td class="right bold">{{ $sale->invoice_number }}</td>
        </tr>
        <tr>
            <td>Date:</td>
            <td class="right">{{ \Carbon\Carbon::parse($sale->sale_date ?? $sale->created_at)->format('d M Y, h:i A') }}</td>
        </tr>
        <tr>
            <td>Customer:</td>
            <td class="right">{{ $sale->customer->name ?? 'Walk-in' }}</td>
        </tr>
        @if (setting('receipt_show_cashier', '1') == '1' && isset($sale->user))
            <tr>
                <td>Cashier:</td>
                <td class="right">{{ $sale->user->name }}</td>
            </tr>
        @endif
    </table>

    <div class="divider"></div>

    {{-- Items --}}
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right" style="width: 40px;">Qty</th>
                <th class="right" style="width: 60px;">Price</th>
                <th class="right" style="width: 70px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Product' }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ number_format($item->selling_price, 2) }}</td>
                    <td class="right">{{ number_format($item->quantity * $item->selling_price - ($item->discount ?? 0), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    {{-- Totals --}}
    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right">{{ number_format($sale->subtotal ?? 0, 2) }}</td>
        </tr>
        @if (($sale->discount ?? 0) > 0)
            <tr>
                <td>Discount</td>
                <td class="right">- {{ number_format($sale->discount, 2) }}</td>
            </tr>
        @endif
        @if (($sale->tax ?? 0) > 0)
            <tr>
                <td>Tax</td>
                <td class="right">+ {{ number_format($sale->tax, 2) }}</td>
            </tr>
        @endif
        <tr class="grand">
            <td>Grand Total</td>
            <td class="right">{{ setting('currency_symbol', 'Rs.') }} {{ number_format($sale->grand_total, 2) }}</td>
        </tr>
        <tr>
            <td>Paid</td>
            <td class="right">{{ number_format($sale->paid_amount ?? 0, 2) }}</td>
        </tr>
        @if (($sale->due_amount ?? 0) > 0)
            <tr>
                <td class="bold">Due</td>
                <td class="right bold">{{ number_format($sale->due_amount, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td>Payment Method</td>
            <td class="right">{{ strtoupper($sale->payment_method ?? 'CASH') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- Footer --}}
    <div class="footer">
        {{ setting('receipt_footer', 'Thank you for shopping!') }}
        <br><br>
        <span style="font-size: 10px;">
            Powered by POS System
        </span>
    </div>

</div>

{{-- ============ AUTO PRINT ============ --}}
@if (setting('auto_print_receipt', '0') == '1')
    <script>
        window.addEventListener('load', () => {
            setTimeout(() => window.print(), 300);
        });
    </script>
@endif

</body>
</html>