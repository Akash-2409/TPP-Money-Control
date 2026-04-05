@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Parties (Account Master)</h3>
            <a href="{{ route('parties.create') }}" class="btn btn-primary">Add Party</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Phone</th>
                                <th>GST No.</th>
                                <th>Opening Bal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parties as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($p->type) }}</span></td>
                                <td>{{ $p->phone ?? '-' }}</td>
                                <td>{{ $p->gst_number ?? '-' }}</td>
                                <td>₹ {{ number_format($p->opening_balance, 2) }}</td>
                                <td>
                                    <a href="{{ route('parties.edit', $p->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('parties.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this party?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
