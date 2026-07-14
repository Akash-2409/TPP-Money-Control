@extends('layouts.app')

@section('page-title', 'Create New User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-5">
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: 600;">
                        <i class="mdi mdi-account-plus"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-1 fw-bold" style="font-family: 'Outfit', sans-serif;">Add Employee/User</h4>
                        <p class="text-muted mb-0 small">Create a new core user account that can log into the system.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    
                    <h5 class="fw-semibold text-dark mb-3">Account Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-3 border-secondary text-dark @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control rounded-3 border-secondary text-dark @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="johndoe123" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3 border-secondary text-dark @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="john@example.com" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control rounded-3 border-secondary text-dark @error('password') is-invalid @enderror" placeholder="Min 8 characters" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3 border-secondary text-dark" placeholder="Re-type password" required>
                        </div>
                    </div>

                    <h5 class="fw-semibold text-dark mb-3 mt-4">Assign Roles (Optional)</h5>
                    <p class="text-muted small mb-4">Select the administrative roles you want to assign immediately. If left blank, they will have no access.</p>

                    <div class="row g-3 mb-5">
                        @foreach($roles as $role)
                        <div class="col-md-6">
                            <label class="w-100 p-3 border rounded-3 position-relative form-check-label" style="cursor: pointer; transition: all 0.2s;" onclick="this.classList.toggle('border-primary'); this.classList.toggle('bg-primary-subtle')">
                                <div class="form-check m-0 d-flex align-items-center">
                                    <input class="form-check-input mt-0 me-3 shadow-none border-secondary" style="transform: scale(1.2);" type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                    <div>
                                        <span class="d-block text-dark fw-bold mb-1">{{ ucfirst($role->name) }} Role</span>
                                        <span class="text-muted small d-block" style="line-height: 1.2;">
                                            @if($role->name == 'superadmin') Total system access. @else Standard dashboard access. @endif
                                        </span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <h5 class="fw-semibold text-dark mb-3">Granular Page Permissions</h5>
                    <p class="text-muted small mb-4">Select exactly which pages or actions this newly created user is allowed to access.</p>

                    <div class="row g-3 mb-5">
                        @foreach($permissions as $permission)
                        <div class="col-md-4">
                            <label class="w-100 p-2 border rounded-3 position-relative form-check-label" style="cursor: pointer; transition: all 0.2s;" onclick="this.classList.toggle('border-success'); this.classList.toggle('bg-success-subtle')">
                                <div class="form-check m-0 d-flex align-items-center">
                                    <input class="form-check-input mt-0 me-2 shadow-none border-secondary" style="transform: scale(1.1);" type="checkbox" name="permissions[]" value="{{ $permission->name }}" {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                    <span class="text-dark fw-medium" style="font-size: 0.9rem;">{{ ucwords($permission->name) }}</span>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('users.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm" style="transition: all 0.3s ease;">Cancel</a>
                        <button type="submit" class="btn btn-primary text-white rounded-pill px-4 shadow-sm fw-semibold" style="transition: all 0.3s ease;">
                            <i class="mdi mdi-account-plus me-1"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
