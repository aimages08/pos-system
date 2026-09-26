@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Edit Role: {{ $role->label }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/roles') }}">Roles</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($role->name === 'admin')
                <div class="alert alert-warning">
                    <i class="bi bi-shield-lock"></i>
                    The <strong>admin</strong> role always has full access — permissions cannot be changed.
                </div>
            @endif

            <form action="{{ url('/roles/'.$role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-8">
                        {{-- Role info --}}
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Role Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Internal Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $role->name) }}"
                                               {{ $role->name === 'admin' ? 'readonly' : '' }}
                                               required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="label" class="form-label">Display Label <span class="text-danger">*</span></label>
                                        <input type="text" name="label" id="label"
                                               class="form-control @error('label') is-invalid @enderror"
                                               value="{{ old('label', $role->label) }}"
                                               required>
                                        @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" rows="2"
                                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $role->description) }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Permissions --}}
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title mb-0">Permissions</h3>
                                @if ($role->name !== 'admin')
                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="select-all">Select All</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect-all">Deselect All</button>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">

                                @foreach ($permissions as $group => $perms)
                                    <div class="mb-3 border-bottom pb-3">
                                        <h6 class="text-primary mb-2">
                                            <i class="bi bi-folder2-open"></i> {{ $group ?? 'Other' }}
                                        </h6>

                                        <div class="row">
                                            @foreach ($perms as $permission)
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox"
                                                               type="checkbox"
                                                               name="permissions[]"
                                                               id="perm_{{ $permission->id }}"
                                                               value="{{ $permission->id }}"
                                                               {{ in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'checked' : '' }}
                                                               {{ $role->name === 'admin' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                            {{ $permission->label }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body d-flex flex-column gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Update Role
                                </button>
                                <a href="{{ url('/roles') }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</main>

<script>
    document.getElementById('select-all')?.addEventListener('click', () => {
        document.querySelectorAll('.permission-checkbox:not(:disabled)').forEach(c => c.checked = true);
    });

    document.getElementById('deselect-all')?.addEventListener('click', () => {
        document.querySelectorAll('.permission-checkbox:not(:disabled)').forEach(c => c.checked = false);
    });
</script>

@endsection