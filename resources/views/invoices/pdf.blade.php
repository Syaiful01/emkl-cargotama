<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #2563EB; }
        .invoice-info { float: right; text-align: right; }
        .billing-info { margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #eee; padding: 10px; text-align: left; }
        .table th { background: #f9f9f9; }
        .totals { float: right; width: 250px; }
        .totals-row { display: flex; justify-content: space-between; padding: 5px 0; }
        .grand-total { font-weight: bold; font-size: 14px; border-top: 2px solid #333; padding-top: 10px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; color: #999; font-size: 10px; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <div style="float: left;">
            <div class="logo">EMKL SYSTEM</div>
            <div>Jl. Pelabuhan No. 123</div>
            <div>Surabaya, Indonesia</div>
            <div>Tel: +62 31 1234567</div>
        </div>
        <div class="invoice-info">
            <h2 style="margin-top: 0;">INVOICE</h2>
            <div><strong>No:</strong> {{ $invoice->invoice_number }}</div>
            <div><strong>Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}</div>
            <div><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="billing-info">
        <div style="float: left; width: 50%;">
            <strong>BILL TO:</strong><br>
            {{ $invoice->customer->name }}<br>
            {{ $invoice->customer->address }}<br>
            PIC: {{ $invoice->customer->pic }}<br>
            Tel: {{ $invoice->customer->phone }}
        </div>
        <div style="float: right; width: 40%;">
            <strong>SHIPMENT INFO:</strong><br>
            Job No: {{ $invoice->shipment->job_number }}<br>
            Vessel: {{ $invoice->shipment->vessel_name }}<br>
            Container: {{ $invoice->shipment->container_number }} ({{ $invoice->shipment->container_type }})<br>
            POL/POD: {{ $invoice->shipment->pol }} / {{ $invoice->shipment->pod }}
        </div>
        <div class="clear"></div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: center; width: 80px;">Qty</th>
                <th style="text-align: right; width: 120px;">Unit Price</th>
                <th style="text-align: right; width: 120px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td style="text-align: center;">{{ number_format($item->quantity, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->unit_price, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div style="margin-bottom: 5px;">
            <span style="display: inline-block; width: 120px;">Subtotal:</span>
            <span style="float: right;">{{ number_format($invoice->subtotal, 2) }}</span>
            <div style="clear: both;"></div>
        </div>
        <div style="margin-bottom: 5px;">
            <span style="display: inline-block; width: 120px;">Tax (11%):</span>
            <span style="float: right;">{{ number_format($invoice->tax, 2) }}</span>
            <div style="clear: both;"></div>
        </div>
        <div class="grand-total">
            <span style="display: inline-block; width: 120px;">Grand Total:</span>
            <span style="float: right;">{{ number_format($invoice->grand_total, 2) }}</span>
            <div style="clear: both;"></div>
        </div>
    </div>
    <div class="clear"></div>

    <div style="margin-top: 50px;">
        <div style="float: left; width: 200px; text-align: center;">
            Prepared By,<br><br><br><br>
            ( ____________________ )
        </div>
        <div style="float: right; width: 200px; text-align: center;">
            Approved By,<br><br><br><br>
            ( ____________________ )
        </div>
        <div class="clear"></div>
    </div>

    <div class="footer">
        Thank you for your business! Please pay by the due date.
    </div>
</body>
</html>
