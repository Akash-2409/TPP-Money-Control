@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
    <h3>Add Product</h3>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        {{-- Main Product --}}
        <div class="mb-3">
            <label>Main Product</label>
            <select name="main_product" id="main_product" class="form-control" required>
                <option value="">Select Main Product</option>
                <option value="PP Box Strap">PP Box Strap</option>
                <option value="Packing Grid">Packing Grid</option>
            </select>
        </div>

        {{-- Category --}}
        <div class="mb-3">
            <label>Category</label>
            <select name="category" id="category" class="form-control" required>
                <option value="">Select Category</option>
            </select>
        </div>

        {{-- Product Name --}}
        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- Unit --}}
        <div class="mb-3">
            <label>Unit (optional)</label>
            <input type="text" name="unit" class="form-control" placeholder="kg, pcs, meter etc.">
        </div>

        {{-- Opening Stock --}}
        <div class="mb-3">
            <label>Opening Stock (optional)</label>
            <input type="number" name="opening_stock" class="form-control">
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
    </div>
</div>


{{-- Dependent Dropdown Script --}}
<script>
    const categories = {
        "PP Box Strap": ["Fully Automatic", "Semi Automatic", "Manual", "Virgin Quality", "Mix Quality"],
        "Packing Grid": ["IT Grid", "Auto Grid", "Poll Stand", "Pole Ring"]
    };

    document.getElementById('main_product').addEventListener('change', function() {
        const mainProduct = this.value;
        const categoryDropdown = document.getElementById('category');

        // Clear old options
        categoryDropdown.innerHTML = '<option value="">Select Category</option>';

        if (categories[mainProduct]) {
            categories[mainProduct].forEach(cat => {
                let opt = document.createElement('option');
                opt.value = cat;
                opt.textContent = cat;
                categoryDropdown.appendChild(opt);
            });
        }
    });
</script>

@endsection
