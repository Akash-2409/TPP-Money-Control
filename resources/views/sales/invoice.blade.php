<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice #{{ $sale->id }}</title>
    <!-- Use Bootstrap directly for clean printing -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #333;
            font-size: 14px;
        }
        .invoice-card {
            background: #fff;
            max-width: 900px;
            margin: 40px auto;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .invoice-header {
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .brand-logo {
            font-size: 28px;
            font-weight: 800;
            color: #4B49AC;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #555;
        }
        .table th {
            background-color: #f1f1f1 !important;
            color: #000;
            font-size: 13px;
        }
        .table td {
            vertical-align: middle;
        }
        @media print {
            body {
                background-color: #fff;
                margin: 0;
            }
            .invoice-card {
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="text-center my-3 no-print">
        <button onclick="window.print()" class="btn btn-primary px-4 py-2 me-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer me-2" viewBox="0 0 16 16">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
            </svg>
            Print Invoice
        </button>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary px-4 py-2">Back to Sales</a>
    </div>

    <div class="invoice-card">
        
        <!-- Header -->
        <div class="row invoice-header align-items-center">
            <div class="col-sm-6">
                <div class="brand-logo">MFG-ERP</div>
                <div class="text-muted mt-1">Official Subcontractor & Manufacturing</div>
                <div><strong>GSTIN:</strong> 22AAAAA0000A1Z5</div>
            </div>
            <div class="col-sm-6 text-sm-end text-start mt-3 mt-sm-0">
                <div class="invoice-title">TAX INVOICE</div>
                <div><strong>Invoice No:</strong> #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</div>
            </div>
        </div>

        <!-- Bill To -->
        <div class="row mb-4">
            <div class="col-6">
                <h6 class="text-uppercase text-muted mb-2">Billed To:</h6>
                @if($sale->party)
                    <h5 class="mb-1 font-weight-bold">{{ $sale->party->name }}</h5>
                    @if($sale->party->billing_address)
                        <div>{{ $sale->party->billing_address }}</div>
                    @endif
                    @if($sale->party->gst_number)
                        <div><strong>GSTIN:</strong> {{ $sale->party->gst_number }}</div>
                    @endif
                @else
                    <h5 class="mb-1 font-weight-bold">{{ $sale->customer ?? 'Walk-in Customer' }}</h5>
                @endif
                
                @if($sale->village)
                    <div><strong>Village/Location:</strong> {{ $sale->village }}</div>
                @endif
                @if($sale->vehicle_no)
                    <div><strong>Vehicle No:</strong> {{ $sale->vehicle_no }}</div>
                @endif
            </div>
        </div>

        @php
            // Check if there are taxes on this invoice
            $hasIgst = $invoiceItems->where('tax_type', 'igst')->count() > 0;
            $hasCgstSgst = $invoiceItems->where('tax_type', 'cgst_sgst')->count() > 0;
            $hasTax = $hasIgst || $hasCgstSgst;
        @endphp

        <!-- Items Table -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="3%">#</th>
                        <th width="32%">Product Description</th>
                        <th width="10%" class="text-center">Qty</th>
                        <th width="10%" class="text-end">Rate (₹)</th>
                        <th width="15%" class="text-end">Taxable (₹)</th>
                        @if($hasTax)
                        <th width="15%" class="text-center">Tax Info</th>
                        @endif
                        <th width="15%" class="text-end">Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $grandTaxable = 0; 
                        $grandCgst = 0;
                        $grandSgst = 0;
                        $grandIgst = 0;
                        $grandTotal = 0; 
                    @endphp
                    @foreach($invoiceItems as $index => $item)
                        @php 
                            $taxableArea = $item->taxable_amount > 0 ? $item->taxable_amount : ($item->quantity * $item->rate); // Fallback for old records
                            $grandTaxable += $taxableArea;
                            $grandCgst += $item->cgst_amount;
                            $grandSgst += $item->sgst_amount;
                            $grandIgst += $item->igst_amount;
                            $grandTotal += $item->total; 
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->product ? $item->product->name : 'Unknown Product' }}</strong>
                                @if($item->note)
                                    <br><small class="text-muted">{{ $item->note }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                            <td class="text-end">{{ number_format($taxableArea, 2) }}</td>
                            @if($hasTax)
                            <td class="text-muted" style="font-size: 12px;">
                                @if($item->tax_type == 'igst')
                                    IGST @ {{ number_format($item->gst_rate, 0) }}% <br>
                                    (₹{{ number_format($item->igst_amount, 2) }})
                                @elseif($item->tax_type == 'cgst_sgst')
                                    CGST @ {{ number_format($item->gst_rate/2, 1) }}%: ₹{{ number_format($item->cgst_amount, 2) }}<br>
                                    SGST @ {{ number_format($item->gst_rate/2, 1) }}%: ₹{{ number_format($item->sgst_amount, 2) }}
                                @else
                                    None
                                @endif
                            </td>
                            @endif
                            <td class="text-end font-weight-bold">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="row justify-content-end">
            <div class="col-sm-6 col-12">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-end">Total Taxable Value:</td>
                        <td class="text-end fw-bold">₹ {{ number_format($grandTaxable, 2) }}</td>
                    </tr>
                    @if($grandCgst > 0)
                    <tr>
                        <td class="text-end">Total CGST:</td>
                        <td class="text-end">₹ {{ number_format($grandCgst, 2) }}</td>
                    </tr>
                    @endif
                    @if($grandSgst > 0)
                    <tr>
                        <td class="text-end">Total SGST:</td>
                        <td class="text-end">₹ {{ number_format($grandSgst, 2) }}</td>
                    </tr>
                    @endif
                    @if($grandIgst > 0)
                    <tr>
                        <td class="text-end">Total IGST:</td>
                        <td class="text-end">₹ {{ number_format($grandIgst, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="h5 font-weight-bold" style="border-top: 2px solid #ddd;">
                        <td class="text-end pt-2">Grand Total:</td>
                        <td class="text-end text-success pt-2">₹ {{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-5 text-center text-muted" style="font-size: 13px;">
            <hr>
            <p class="mb-0">Thank you for your business!</p>
            <p>This is a computer-generated invoice. No signature is required.</p>
        </div>

    </div>
</div>

</body>
</html>
