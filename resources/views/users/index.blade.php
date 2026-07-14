@extends('layouts.app')

@section('page-title', 'Access Rights Management')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0 fw-bold" style="font-family: 'Outfit', sans-serif;">System Users</h4>
                    <a href="{{ route('users.create') }}" class="btn btn-primary text-white rounded-pill shadow-sm px-4 fw-semibold" style="transition: all 0.3s ease;">
                        <i class="mdi mdi-account-plus me-1"></i> Add User
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3">
                        <i class="mdi mdi-check-circle me-1"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light text-secondary rounded-top">
                            <tr>
                                <th class="border-0 px-4 py-3 fw-semibold">Name</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Email</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Current Roles</th>
                                <th class="border-0 px-4 py-3 fw-semibold text-end">Manage</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach($users as $user)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 38px; height: 38px; font-weight: 600;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-dark fw-medium">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="text-muted px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge {{ $role->name == 'superadmin' ? 'bg-danger' : 'bg-primary' }} text-white px-3 py-2 rounded-pill me-1" style="font-size: 0.75rem; letter-spacing: 0.3px;">
                                                <i class="mdi {{ $role->name == 'superadmin' ? 'mdi-shield-crown' : 'mdi-account' }} me-1"></i>{{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted fst-italic">No Roles Assigned</span>
                                    @endif
                                </td>
                                <td class="text-end px-4 py-3">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm" style="transition: all 0.3s ease;">
                                        <i class="mdi mdi-pencil me-1"></i> Edit Rights
                                    </a>
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
