@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <h3 class="mb-4">Materials</h3>
        
        <a class="btn btn-primary mb-3" href="{{ route('materials.create') }}">Add Material</a>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Current Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $m)
                    <tr>
                        <td>{{ ucfirst($m->material_type) }}</td>
                        <td>{{ $m->name }}</td>
                        <td>{{ $m->unit }}</td>
                        <td>{{ $m->current_stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $materials->links() }}
    </div>

</div>

@endsection
