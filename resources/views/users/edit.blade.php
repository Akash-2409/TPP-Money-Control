@extends('layouts.app')

@section('page-title', 'Edit Access Rights')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-5">
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: 600;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="card-title mb-1 fw-bold" style="font-family: 'Outfit', sans-serif;">{{ $user->name }}</h4>
                        <p class="text-muted mb-0 small">{{ $user->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="fw-semibold text-dark mb-3">Assign Roles</h5>
                    <p class="text-muted small mb-4">Select the administrative roles you want to assign to this user. The <b>superadmin</b> role gives full access to everything.</p>

                    <div class="row g-3 mb-5">
                        @foreach($roles as $role)
                        <div class="col-md-6">
                            <label class="w-100 p-3 border rounded-3 position-relative form-check-label {{ $user->hasRole($role->name) ? 'border-primary bg-primary-subtle' : '' }}" style="cursor: pointer; transition: all 0.2s;" onclick="this.classList.toggle('border-primary'); this.classList.toggle('bg-primary-subtle')">
                                <div class="form-check m-0 d-flex align-items-center">
                                    <input class="form-check-input mt-0 me-3 shadow-none border-secondary" style="transform: scale(1.2);" type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                    <div>
                                        <span class="d-block text-dark fw-bold mb-1">{{ ucfirst($role->name) }} Role</span>
                                        <span class="text-muted small d-block" style="line-height: 1.2;">
                                            @if($role->name == 'superadmin')
                                                Total system access. Caution.
                                            @else
                                                Standard dashboard access.
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <h5 class="fw-semibold text-dark mb-3">Granular Page Permissions</h5>
                    <p class="text-muted small mb-4">Select exactly which pages or actions this user is allowed to access. (Superadmins automatically have all of these).</p>

                    <div class="row g-3 mb-5">
                        @foreach($permissions as $permission)
                        <div class="col-md-4">
                            <label class="w-100 p-2 border rounded-3 position-relative form-check-label {{ $user->hasDirectPermission($permission->name) ? 'border-success bg-success-subtle' : '' }}" style="cursor: pointer; transition: all 0.2s;" onclick="this.classList.toggle('border-success'); this.classList.toggle('bg-success-subtle')">
                                <div class="form-check m-0 d-flex align-items-center">
                                    <input class="form-check-input mt-0 me-2 shadow-none border-secondary" style="transform: scale(1.1);" type="checkbox" name="permissions[]" value="{{ $permission->name }}" {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                                    <span class="text-dark fw-medium" style="font-size: 0.9rem;">{{ ucwords($permission->name) }}</span>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('users.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm" style="transition: all 0.3s ease;">Cancel</a>
                        <button type="submit" class="btn btn-primary text-white rounded-pill px-4 shadow-sm fw-semibold" style="transition: all 0.3s ease;">
                            <i class="mdi mdi-content-save me-1"></i> Save Rights
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
