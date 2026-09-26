@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Backups</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">Backups</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card card-primary card-outline mb-4">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">Database Backups</h3>

                    <div class="ms-auto d-flex gap-2">
                        <form action="{{ url('/backups/cleanup') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('Delete backups older than {{ setting('backup_retention_days', 30) }} days?');">
                                <i class="bi bi-trash me-1"></i> Cleanup Old
                            </button>
                        </form>

                        <form action="{{ url('/backups/create') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary"
                                    onclick="this.disabled=true; this.form.submit();">
                                <i class="bi bi-download me-1"></i> Create Backup Now
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>File Name</th>
                                <th>Date</th>
                                <th>Size</th>
                                <th style="width: 260px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($backups as $i => $b)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><code>{{ $b['name'] }}</code></td>
                                    <td>{{ $b['date'] }}</td>
                                    <td>{{ number_format($b['size'] / 1024, 2) }} KB</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ url('/backups/download/'.$b['name']) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-download"></i> Download
                                            </a>

                                            <form action="{{ url('/backups/'.$b['name']) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this backup?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                            <form action="{{ url('/backups/restore') }}"
                                                  method="POST"
                                                  onsubmit="return confirm('⚠️ THIS WILL WIPE ALL CURRENT DATA AND RESTORE FROM THIS BACKUP.\n\nAre you absolutely sure?');">
                                                @csrf
                                                <input type="hidden" name="filename" value="{{ $b['name'] }}">
                                                <input type="hidden" name="confirm" value="1">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-4">
                                        No backups yet. Click <strong>Create Backup Now</strong>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Tip:</strong> Create a backup before making major changes.
                Download important backups to your computer for extra safety.
            </div>

        </div>
    </div>
</main>

@endsection