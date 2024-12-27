@extends('layouts.dashboard')

@section('content')
    <div class="container mt-4">
        <div class="container mt-4">
            <div class="row ">
                <div class="col-md-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card card-dashboard1 text-white mb-3">
                            <div class="card-body">
                                <h6 class="card-title">Total Registered Farmers</h6>
                                <span class="card-text">{{ $totalFarmers }}</span>
                            </div>
                        </div>
                    </a>
                </div>

                @if (isset($loanData) && !empty($loanData))
                    <div class="col-md-3">
                        <a href="{{ route('loanmanagement.index') }}" class="text-decoration-none">
                            <div class="card card-dashboard2 text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Farmers with Loans</h6>
                                    <span class="card-text">{{ $loanData['totalFarmersWithLoans'] }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('loanmanagement.index') }}" class="text-decoration-none">
                            <div class="card card-dashboard3 text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Total Loan Applications</h6>
                                    <span class="card-text">{{ $loanData['totalLoans'] }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('loanmanagement.index') }}" class="text-decoration-none">
                            <div class="card card-dashboard4 text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Disbursed Loans Amount</h6>
                                    <span class="card-success">{{ number_format($loanData['totalLoanAmount']) }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>

            @if (isset($loanData) && !empty($loanData))
                <div class="row mb-4">
                    <div class="col-md-9">
                        <a href="#" class="text-decoration-none">

                            <h6 class="text-muted mx-3">Loan Statuses</h6>
                            <div class="d-flex justify-content-start">
                                <a href="{{ route('loanmanagement.index', ['status' => 'pending']) }}"
                                    class="text-decoration-none border border-warning rounded text-warning px-3 mx-3 pt-1">
                                    <h6>Pending: {{ $loanData['pendingLoans'] }}</h6>
                                </a>
                                <a href="{{ route('loanmanagement.index', ['status' => 'approved']) }}"
                                    class="text-decoration-none border border-success rounded text-success px-3 mx-3 pt-1">
                                    <h6>Approved: {{ $loanData['approvedLoans'] }}</h6>
                                </a>
                                <a href="{{ route('loanmanagement.index', ['status' => 'rejected']) }}"
                                    class="text-decoration-none border border-danger rounded text-danger px-3 mx-3 pt-1">
                                    <h6>Rejected: {{ $loanData['rejectedLoans'] }}</h6>
                                </a>
                            </div>

                        </a>
                    </div>
                </div>
            @endif

            <div class="row mt-5">  
                <div>  
                    <div class="d-flex justify-content-between align-items-center mb-3 farmers-header">  
                        <h6 class="">All Farmers</h6>  
                        <button class="create-button btn btn-sm" data-bs-toggle="modal" data-bs-target="#createFarmerModal">  
                            <span class="material-icons">add</span> Create Farmer  
                        </button>  
                    </div>  
                </div>  
            
                <div class="col-12">  
                    <table class="table table-striped table-responsive">  
                        <thead>  
                            <tr>  
                                <th>ID</th>  
                                <th>First Name</th>  
                                <th>Last Name</th>  
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
                                    <td>{{ $farmer->phone }}</td>  
                                    <td>  
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"  
                                            data-bs-target="#editFarmerModal{{ $farmer->id }}">Edit</button>  
                                        <form action="{{ route('farmers.destroy', $farmer->id) }}" method="POST"  
                                            class="d-inline">  
                                            @csrf  
                                            @method('DELETE')  
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>  
                                        </form>  
                                    </td>  
                                </tr>  
                            @endforeach  
                        </tbody>  
                    </table>  
                    {{ $farmers->links('pagination::bootstrap-5') }}
                </div>  
            </div>
            <!-- Create Farmer Modal -->
            <div class="modal fade" id="createFarmerModal" tabindex="-1" aria-labelledby="createFarmerModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createFarmerModalLabel">Create Farmer</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('farmers.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="first-name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="last-name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone"
                                        required>
                                </div>

                                <button type="submit" class="create-button btn btn-sm">
                                    <span class="material-icons">add</span> submit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
