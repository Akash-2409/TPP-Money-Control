@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <h3 class="mb-3">Add Sale / Dispatch</h3>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('sales.store') }}">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Customer </label>
                            <select name="party_id" class="form-control mb-2" onchange="partySelected(this)">
                                <option value="">Select Customer Party...</option>
                                @isset($parties)
                                    @foreach($parties as $party)
                                        <option value="{{ $party->id }}" data-village="{{ $party->billing_address }}">{{ $party->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            <input type="text" name="customer" class="form-control mb-3" placeholder="Or manual name">
                            
                        </div>
                        <div class="col-md-6">
                            <label>Village</label>
                            <input type="text" name="village" class="form-control mb-3">
                            
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Vehicle No</label>
                            <input type="text" name="vehicle_no" class="form-control mb-3">
                            
                        </div>
                        <div class="col-md-4">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label>Tax Type</label>
                            <select name="tax_type" id="tax_type" class="form-control" onchange="recalculateAll()">
                                <option value="none">No Tax</option>
                                <option value="cgst_sgst">Intra-State (CGST & SGST)</option>
                                <option value="igst">Inter-State (IGST)</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="sales-table">
                            <thead class="table-dark">
                                <tr>
                                    <th width="25%">Product</th>
                                    <th width="15%">Qty</th>
                                    <th width="15%">Rate</th>
                                    <th width="15%">GST %</th>
                                    <th width="20%">Total</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody id="sales-rows">
                                <tr class="sales-row">
                                    <td>
                                        <select name="product_id[]" class="form-control product-select" required onchange="updateStockBadge(this)">
                                            <option value="" data-stock="0">Select Product...</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-stock="{{ $product->inventory ? $product->inventory->current_stock : 0 }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted stock-badge d-none mt-1 d-block"></small>
                                    </td>
                                    <td>
                                        <input type="number" step="0.001" name="quantity[]" class="form-control quantity" required oninput="calculateTotal(this); checkStockLevel(this);">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="rate[]" class="form-control rate" required oninput="calculateTotal(this)">
                                    </td>
                                    <td>
                                        <select name="gst_rate[]" class="form-control gst-rate" onchange="calculateTotal(this)">
                                            <option value="0">0%</option>
                                            <option value="5">5%</option>
                                            <option value="12">12%</option>
                                            <option value="18">18%</option>
                                            <option value="28">28%</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="total[]" class="form-control total" readonly>
                                        <input type="hidden" class="taxable-amount" value="0">
                                        <input type="hidden" class="tax-amount" value="0">
                                        <small class="text-muted tax-breakdown d-block mt-1"></small>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row" title="Remove Row"><i class="mdi mdi-close"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold">Taxable Amount:</td>
                                    <td colspan="2"><h6 id="grand-taxable" class="mb-0">₹0.00</h6></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold">Total Tax:</td>
                                    <td colspan="2"><h6 id="grand-tax" class="mb-0">₹0.00</h6></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold align-middle">Grand Total:</td>
                                    <td colspan="2">
                                        <h4 class="mb-0 text-success" id="grand-total">₹0.00</h4>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <button type="button" class="btn btn-inverse-success mt-3 mb-4" id="add-sales-row">
                        <i class="mdi mdi-plus"></i> Add Row
                    </button>
                    <div class="mb-3">
                        <label>Note (optional)</label>
                        <textarea name="note" class="form-control mb-3"></textarea>
                    </div>

                    <button class="btn btn-primary">Save Sale</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function partySelected(select) {
            let option = select.options[select.selectedIndex];
            let village = option.getAttribute('data-village');
            if (village) {
                document.querySelector('input[name="village"]').value = village;
            }
        }

        document.getElementById('add-sales-row').addEventListener('click', function () {
            let container = document.getElementById('sales-rows');
            let row = document.querySelector('.sales-row').cloneNode(true);

            // Reset values
            row.querySelectorAll('input').forEach(input => {
                if(input.type !== 'hidden') input.value = '';
                else input.value = '0';
            });
            row.querySelector('.product-select').value = '';
            row.querySelector('.gst-rate').value = '0';
            row.querySelector('.tax-breakdown').innerHTML = '';
            row.querySelector('.stock-badge').classList.add('d-none');
            
            container.appendChild(row);
            updateGrandTotal();
        });

        document.addEventListener('click', function (e) {
            // Traverse DOM to find remove button (in case icon is clicked)
            let btn = e.target.closest('.remove-row');
            if (btn) {
                let rows = document.querySelectorAll('.sales-row');
                if (rows.length > 1) {
                    btn.closest('.sales-row').remove();
                    updateGrandTotal();
                }
            }
        });

        function recalculateAll() {
            document.querySelectorAll('.sales-row').forEach(row => {
                calculateTotal(row.querySelector('.quantity')); // Triggers recalculation on all rows
            });
        }

        // Dynamic calculation
        function calculateTotal(element) {
            let row = element.closest('.sales-row');
            let qty = parseFloat(row.querySelector('.quantity').value) || 0;
            let rate = parseFloat(row.querySelector('.rate').value) || 0;
            let gstPercent = parseFloat(row.querySelector('.gst-rate').value) || 0;
            let taxType = document.getElementById('tax_type').value;

            let taxable = qty * rate;
            let taxAmount = 0;
            let breakdownText = '';

            if(taxType !== 'none' && gstPercent > 0) {
                taxAmount = taxable * (gstPercent / 100);
                if (taxType === 'igst') {
                    breakdownText = `IGST: +₹${taxAmount.toFixed(2)}`;
                } else if (taxType === 'cgst_sgst') {
                    let halfTax = taxAmount / 2;
                    breakdownText = `CGST: ₹${halfTax.toFixed(2)} | SGST: ₹${halfTax.toFixed(2)}`;
                }
            }

            let total = taxable + taxAmount;
            
            row.querySelector('.taxable-amount').value = taxable.toFixed(2);
            row.querySelector('.tax-amount').value = taxAmount.toFixed(2);
            row.querySelector('.total').value = total.toFixed(2);
            row.querySelector('.tax-breakdown').innerHTML = breakdownText;
            
            updateGrandTotal();
        }

        // Feature: UI UX Current Stock Display
        function updateStockBadge(selectElement) {
            let row = selectElement.closest('.sales-row');
            let badge = row.querySelector('.stock-badge');
            let selectedOption = selectElement.options[selectElement.selectedIndex];
            
            if (selectElement.value !== "") {
                let stock = parseFloat(selectedOption.getAttribute('data-stock'));
                badge.classList.remove('d-none');
                badge.innerHTML = `Stock Available: <strong>${stock}</strong>`;
                if(stock <= 0) {
                    badge.classList.add('text-danger');
                    badge.classList.remove('text-muted');
                } else {
                    badge.classList.remove('text-danger');
                    badge.classList.add('text-muted');
                }
            } else {
                badge.classList.add('d-none');
            }
            
            // Re-check stock if quantity is already typed
            checkStockLevel(row.querySelector('.quantity'));
        }

        function checkStockLevel(qtyInput) {
            let row = qtyInput.closest('.sales-row');
            let selectElement = row.querySelector('.product-select');
            let selectedOption = selectElement.options[selectElement.selectedIndex];
            
            if (selectElement.value !== "") {
                let availableStock = parseFloat(selectedOption.getAttribute('data-stock')) || 0;
                let desiredQty = parseFloat(qtyInput.value) || 0;
                
                if (desiredQty > availableStock) {
                    qtyInput.classList.add('is-invalid');
                    qtyInput.classList.add('border-danger');
                } else {
                    qtyInput.classList.remove('is-invalid');
                    qtyInput.classList.remove('border-danger');
                }
            }
        }

        function updateGrandTotal() {
            let grandTaxable = 0;
            let grandTax = 0;
            let grandTotal = 0;

            document.querySelectorAll('.sales-row').forEach(row => {
                grandTaxable += parseFloat(row.querySelector('.taxable-amount').value) || 0;
                grandTax += parseFloat(row.querySelector('.tax-amount').value) || 0;
                grandTotal += parseFloat(row.querySelector('.total').value) || 0;
            });

            document.getElementById('grand-taxable').innerText = '₹' + grandTaxable.toFixed(2);
            document.getElementById('grand-tax').innerText = '₹' + grandTax.toFixed(2);
            document.getElementById('grand-total').innerText = '₹' + grandTotal.toFixed(2);
        }
    </script>
</div>
@endsection
