@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <h3 class="mb-4">Add Worker</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Error:</strong> Please fix the following issues:<br>
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('workers.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Worker Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Monthly Salary (₹)</label>
                        <input type="number" step="0.01" name="monthly_salary" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact (optional)</label>
                        <input type="text" name="contact" class="form-control">
                    </div>

                    <button class="btn btn-primary">Add Worker</button>
                    <a href="{{ route('workers.index') }}" class="btn btn-secondary">Cancel</a>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
