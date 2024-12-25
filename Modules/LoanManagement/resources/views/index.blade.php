@extends('layouts.dashboard')

@section('content')
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold">All Loans</h6>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createLoanModal">Create
                Loan</button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Farmer Name</th>
                    <th>Amount</th>
                    <th>Interest Rate</th>
                    <th>Repayment Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $loan)
                    <tr>
                        <td>{{ $loan->id }}</td>
                        <td>{{ $loan->farmer->first_name . ' ' . $loan->farmer->last_name ?? '' }}</td>
                        <td>{{ $loan->amount }}</td>
                        <td>{{ $loan->interest_rate }}</td>
                        <td>{{ $loan->repayment_duration }}</td>
                        <td>{{ $loan->status }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editLoanModal{{ $loan->id }}">Edit</button>
                            <form action="{{ route('loanmanagement.destroy', $loan->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Loan Modal -->
                    <div class="modal fade" id="editLoanModal{{ $loan->id }}" tabindex="-1"
                        aria-labelledby="editLoanModalLabel{{ $loan->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="editLoanModalLabel{{ $loan->id }}">Edit {{ $loan->farmer->first_name . ' ' . $loan->farmer->last_name }}'s Loan</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('loanmanagement.update', $loan->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">Amount</label>
                                            <input type="number" class="form-control" id="amount" name="amount"
                                                value="{{ $loan->amount }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="interest_rate" class="form-label">Interest Rate</label>
                                            <input type="number" class="form-control" id="interest_rate"
                                                name="interest_rate" value="{{ $loan->interest_rate }}" required
                                                step="0.01" min="0" max="100">
                                        </div>
                                        <div class="mb-3">
                                            <label for="repayment_duration" class="form-label">Repayment Duration</label>
                                            <input type="number" class="form-control" id="repayment_duration"
                                                name="repayment_duration" value="{{ $loan->repayment_duration }}" required
                                                min="1">
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="approved"
                                                    {{ $loan->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="pending" {{ $loan->status == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="rejected"
                                                    {{ $loan->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Loan Modal -->
    <div class="modal fade" id="createLoanModal" tabindex="-1" aria-labelledby="createLoanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLoanModalLabel">Create Loan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('loanmanagement.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="farmer_id" class="form-label">Farmer</label>
                            <select name="farmer_id" class="form-control" id="farmer_id" required>
                                <option value="">--select farmer--</option>
                                @foreach ($farmers as $farmer)
                                    <option value="{{ $farmer->id }}">
                                        {{ $farmer->first_name . ' ' . $farmer->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required
                                step="0.01" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="interest_rate" class="form-label">Interest Rate</label>
                            <input type="number" class="form-control" id="interest_rate" name="interest_rate" required
                                step="0.01" min="0" max="100">
                        </div>
                        <div class="mb-3">
                            <label for="repayment_duration" class="form-label">Repayment Duration (months)</label>
                            <input type="number" class="form-control" id="repayment_duration" name="repayment_duration" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="approved">Approved</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
