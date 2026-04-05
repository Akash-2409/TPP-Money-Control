@extends('layouts.app')

@section('page-title', 'Edit Transaction')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <h3 class="mb-4"><i class="mdi mdi-cash-edit mr-2"></i>Edit Transaction</h3>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-header">Transaction Details</div>

            <div class="card-body">

                <form method="POST" action="{{ route('transactions.update', $entry->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-3">
                            <label>Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="income" {{ $entry->type == 'income' ? 'selected' : '' }}>Income</option>
                                <option value="expense" {{ $entry->type == 'expense' ? 'selected' : '' }}>Expense</option>
                            </select>
                        </div>

                        <div class="col-md-3 {{ $entry->type !== 'expense' ? 'd-none' : '' }}" id="expenseCategoryDiv">
                            <label>Expense Category</label>
                            <select name="expense_category" id="expense_category" class="form-control">
                                <option value="">Select</option>
                                <option value="machinery" {{ $entry->expense_category == 'machinery' ? 'selected' : '' }}>Machinery Expense</option>
                                <option value="other_expances" {{ $entry->expense_category == 'other_expances' ? 'selected' : '' }}>Other Expances</option>
                                <option value="petrol" {{ $entry->expense_category == 'petrol' ? 'selected' : '' }}>Petrol</option>
                                <option value="maintenance" {{ $entry->expense_category == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="worker_udhaar" {{ $entry->expense_category == 'worker_udhaar' ? 'selected' : '' }}>Worker Udhaar</option>
                            </select>
                        </div>

                        <div class="col-md-3 {{ $entry->expense_category !== 'worker_udhaar' ? 'd-none' : '' }}" id="workerDiv">
                            <label>Worker</label>
                            <select name="worker_id" class="form-control">
                                <option value="">Select Worker</option>
                                @foreach($workers as $worker)
                                <option value="{{ $worker->id }}" {{ $entry->worker_id == $worker->id ? 'selected' : '' }}>{{ $worker->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $entry->amount }}" required>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control"
                                value="{{ \Carbon\Carbon::parse($entry->date)->format('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-9 mt-3">
                            <label>Description (Optional)</label>
                            <input type="text" name="description" class="form-control" value="{{ $entry->description }}">
                        </div>

                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update Transaction
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-light ml-2">Cancel</a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const categoryDiv = document.getElementById('expenseCategoryDiv');
        const workerDiv = document.getElementById('workerDiv');
        const categorySelect = document.getElementById('expense_category');

        typeSelect.addEventListener('change', function() {
            categoryDiv.classList.toggle('d-none', this.value !== 'expense');
            if (this.value !== 'expense') {
                categorySelect.value = '';
                workerDiv.classList.add('d-none');
            }
        });

        categorySelect.addEventListener('change', function() {
            workerDiv.classList.toggle('d-none', this.value !== 'worker_udhaar');
        });
    });
</script>
@endsection
