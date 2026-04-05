@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <h3 class="mb-4">Edit Party</h3>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('parties.update', $party->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name', $party->name) }}">
                            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6">
                            <label>Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" required>
                                <option value="both" {{ old('type', $party->type) == 'both' ? 'selected' : '' }}>Both (Customer & Supplier)</option>
                                <option value="customer" {{ old('type', $party->type) == 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="supplier" {{ old('type', $party->type) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                            </select>
                            @error('type')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $party->phone) }}">
                            @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-4">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $party->email) }}">
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-4">
                            <label>GST Number</label>
                            <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number', $party->gst_number) }}">
                            @error('gst_number')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Billing Address</label>
                            <textarea name="billing_address" class="form-control" rows="3">{{ old('billing_address', $party->billing_address) }}</textarea>
                            @error('billing_address')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6">
                            <label>Shipping Address</label>
                            <textarea name="shipping_address" class="form-control" rows="3">{{ old('shipping_address', $party->shipping_address) }}</textarea>
                            @error('shipping_address')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>Opening Balance</label>
                            <input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ old('opening_balance', $party->opening_balance) }}">
                            <small class="text-muted">Put positive for receivable, negative for payable.</small>
                            @error('opening_balance')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Party</button>
                    <a href="{{ route('parties.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
