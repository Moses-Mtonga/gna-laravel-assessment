@extends('layouts.dashboard')

@section('content')
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-dashboard1 text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Registered Farmers</h5>
                        <span class="card-text">{{ $totalFarmers }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-dashboard2 text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Farmers with Loans</h5>
                        <span class="card-text">{{ $totalFarmersWithLoans }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-dashboard3 text-white mb-3" >
                    <div class="card-body">
                        <h5 class="card-title">Farm Supported Farmers</h5>
                        <span class="card-text">{{ $totalFarmSupportedFarmers }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-dashboard4 text-white mb-3" >
                    <div class="card-body">
                        <h5 class="card-title">Supported Products</h5>
                        <span class="card-text">{{ $totalSupportedProducts }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h5>Farmers Listing</h5>
                <table class="table table-striped dashboard-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($farmers as $farmer)
                            <tr>
                                <td>{{ $farmer->id }}</td>
                                <td>{{ $farmer->first_name }}</td>
                                <td>{{ $farmer->last_name }}</td>
                                <td>{{ $farmer->email }}</td>
                                <td>{{ $farmer->phone }}</td>
                                <td>
                                    <a href="{{ route('farmers.show', $farmer->id) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('farmers.edit', $farmer->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('farmers.destroy', $farmer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
