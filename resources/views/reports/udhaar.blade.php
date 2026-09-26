@extends('layouts.app')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0 fs-3">Udhaar Report</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/reports') }}">Reports</a></li>
                            <li class="breadcrumb-item active">Udhaar</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="alert alert-warning d-flex align-items-center">
                <i class="bi bi-cash-coin fs-3 me-3"></i>
                <div>
                    <div class="small">Total Pending Udhaar</div>
                    <div class="fs-3 fw-bold">Rs {{ number_format($total, 2) }}</div>
                    <div class="small">{{ $customers->count() }} customers owe money</div>
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">Customers with Pending Balance</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th class="text-end">Balance</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $i => $c)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><strong>{{ $c->name }}</strong></td>
                                    <td>{{ $c->phone ?? '—' }}</td>
                                    <td class="text-end text-danger fw-bold">
                                        Rs {{ number_format($c->opening_balance, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url('/reports/udhaar/'.$c->id) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-4">
                                        ✅ No pending udhaar. All customers are clear.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection