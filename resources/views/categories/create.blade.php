@extends('layouts.app')

@section('content')

<main class="app-main">
    {{-- Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Add Category</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/categories') }}">Categories</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="app-content">
        <div class="container-fluid">

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card card-primary card-outline mb-4">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">Add Category</h3>
                    <a href="{{ url('/categories') }}" class="btn btn-sm btn-secondary ms-auto">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>

                <form action="{{ url('/categories') }}" method="POST">
                    @csrf

                    <div class="card-body">

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter category name"
                                value="{{ old('name') }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea
                                name="description"
                                id="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="4"
                                placeholder="Enter description"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active"   {{ old('status') == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="disabled" {{ old('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer d-flex align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Submit
                        </button>
                        <a href="{{ url('/categories') }}" class="btn btn-outline-secondary ms-auto">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</main>

@endsection