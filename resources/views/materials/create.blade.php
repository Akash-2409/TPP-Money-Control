@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

    <h3 class="mb-4">Add Material</h3>

    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('materials.store') }}">
                @csrf

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label>Material Type</label>
                        <select name="material_type" class="form-control" required>
                            <option value="raw">Raw Material</option>
                            <option value="packing">Packing Material</option>
                            <option value="chemical">Chemical</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Material Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                </div>

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label>Unit (kg, liter, pcs, etc.)</label>
                        <input type="text" name="unit" class="form-control" required>
                    </div>

                </div>

                <button class="btn btn-primary">Save Material</button>

            </form>

        </div>
    </div>
    </div>
</div>
@endsection
