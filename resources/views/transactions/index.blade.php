@extends('layouts.app')

@section('content')
<div class="main-panel">
<div class="content-wrapper">

<h3 class="mb-4">Finance Ledger</h3>

{{-- KPI SUMMARY --}}
<div class="row mb-4">

<div class="col-md-4">
<div class="card bg-success text-white">
<div class="card-body">
<h6>Total Income</h6>
<h3>₹ {{ number_format($totalIncome,2) }}</h3>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card bg-danger text-white">
<div class="card-body">
<h6>Total Expense</h6>
<h3>₹ {{ number_format($totalExpense,2) }}</h3>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card {{ $balance >=0 ? 'bg-primary' : 'bg-warning' }} text-white">
<div class="card-body">
<h6>Balance</h6>
<h3>₹ {{ number_format($balance,2) }}</h3>
</div>
</div>
</div>

</div>


@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif


{{-- TRANSACTION FORM --}}
<div class="card mb-4">
<div class="card-header">Add Transaction</div>

<div class="card-body">

<form method="POST" action="{{ route('transactions.store') }}">
@csrf

<div class="row">

<div class="col-md-3">
<label>Type</label>
<select name="type" id="type" class="form-control" required>
<option value="income">Income</option>
<option value="expense">Expense</option>
</select>
</div>

<div class="col-md-3 d-none" id="expenseCategoryDiv">
<label>Expense Category</label>
<select name="expense_category" id="expense_category" class="form-control">
<option value="">Select</option>
<option value="machinery">Machinery Expense</option>
<option value="other_expances">Other Expances</option>
<option value="petrol">Petrol</option>
<option value="maintenance">Maintenance</option>
<option value="worker_udhaar">Worker Udhaar</option>
</select>
</div>

<div class="col-md-3 d-none" id="workerDiv">
<label>Worker</label>
<select name="worker_id" class="form-control">
<option value="">Select Worker</option>

@foreach($workers as $worker)
<option value="{{ $worker->id }}">{{ $worker->name }}</option>
@endforeach

</select>
</div>

<div class="col-md-3">
<label>Amount</label>
<input type="number" step="0.01" name="amount" class="form-control" required>
</div>

<div class="col-md-3">
<label>Date</label>
<input type="date" name="date" class="form-control"
value="{{ date('Y-m-d') }}" required>
</div>

<div class="col-md-3">
<label>Description (Optional)</label>
<input type="text" name="description" class="form-control">
</div>

</div>

<button class="btn btn-primary mt-3">
<i class="mdi mdi-content-save"></i> Save Transaction
</button>

</form>

</div>
</div>



{{-- FILTER --}}
<div class="card mb-4">
<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-3">
<select name="type" class="form-control">
<option value="">All Types</option>
<option value="income" {{ $filter=='income' ? 'selected' : '' }}>Income</option>
<option value="expense" {{ $filter=='expense' ? 'selected' : '' }}>Expense</option>
</select>
</div>

<div class="col-md-3">

<input type="month" name="month"
class="form-control"
placeholder="Select month"
value="{{ $month }}">
</div>

<div class="col-md-4">
<input type="text"
name="q"
class="form-control"
placeholder="Search description..."
value="{{ $search }}">
</div>

<div class="col-md-2">
<button class="btn btn-secondary w-100">
Filter
</button>
</div>

</div>

</form>

</div>
</div>



{{-- TRANSACTION TABLE --}}
<div class="card">

<div class="card-header">
Recent Transactions
</div>

<div class="table-responsive">

<table class="table table-striped mb-0">

<thead>
<tr>
<th>Date</th>
<th>Type</th>
<th class="text-end">Amount</th>
<th>Description</th>
<th class="text-center">Action</th>
</tr>
</thead>

<tbody>

@forelse ($entries as $e)

<tr>

<td>{{ $e->date }}</td>

<td>

@if($e->type == 'income')
<span class="badge badge-success">Income</span>
@else
<span class="badge badge-danger">Expense</span>
@endif

</td>

<td class="text-end">
₹ {{ number_format($e->amount,2) }}
</td>

<td>{{ $e->description }}</td>

<td class="text-center">
    <div class="d-flex justify-content-center align-items-center">
        <a href="{{ route('transactions.edit', $e->id) }}" class="btn btn-warning btn-sm mr-2 text-white" title="Edit">
            <i class="mdi mdi-pencil"></i>
        </a>
        <form method="POST"
        action="{{ route('transactions.destroy',$e->id) }}"
        onsubmit="return confirm('Delete entry?')" class="m-0 p-0">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger btn-sm" title="Delete">
            <i class="mdi mdi-delete"></i>
        </button>
        </form>
    </div>
</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center text-muted">
No transactions found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

<div class="p-3">
{{ $entries->links() }}
</div>

</div>


</div>
</div>



<script>

const typeSelect = document.getElementById('type');
const categoryDiv = document.getElementById('expenseCategoryDiv');
const workerDiv = document.getElementById('workerDiv');
const categorySelect = document.getElementById('expense_category');

typeSelect.addEventListener('change', function() {

categoryDiv.classList.toggle('d-none', this.value !== 'expense');

});

categorySelect.addEventListener('change', function() {

workerDiv.classList.toggle('d-none', this.value !== 'worker_udhaar');

});

</script>

@endsection