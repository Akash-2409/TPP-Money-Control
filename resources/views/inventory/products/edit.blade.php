@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Product</h3>

    <form method="POST" action="{{ route('products.update',$product->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Unit</label>
            <input type="text" name="unit" value="{{ $product->unit }}" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
