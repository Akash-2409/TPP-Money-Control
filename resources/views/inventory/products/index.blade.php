@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Products</h3>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Name</th>
                <th>Unit</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach($products as $p)
                <tr>
                    <td>{{ $p->category }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->unit }}</td>
                    <td>
                        <a href="{{ route('products.edit',$p->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form method="POST" action="{{ route('products.destroy',$p->id) }}"
                              style="display:inline-block;"
                              onsubmit="return confirm('Delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $products->links() }}
    </div>
    <!-- {{ $products->links() }} -->
</div>
@endsection
