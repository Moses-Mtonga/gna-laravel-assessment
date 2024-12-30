@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold">All Loans</h6>
            <button class="create-button btn btn-sm" data-bs-toggle="modal" data-bs-target="#createLoanModal">
                <span class="material-icons">add</span> Add Loan
            </button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>m
                    <th>ID</th>
                    <th>Farmer Name</th>
                    <th>Amount</th>
                    <th>Interest Rate</th>
                    <th>Repayment Duration</th>
                    <th>Application Status</th>
                    <th>Loan Status</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $loan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $loan->farmer->first_name . ' ' . $loan->farmer->last_name ?? '' }}</td>
                        <td>{{ $loan->amount }}</td>
                        <td>{{ $loan->interest_rate }}</td>
                        <td>{{ $loan->repayment_duration }}</td>
                        <td>{{ $loan->application_status }}</td>
                        <td>
                            @if (!($loan->application_status !== 'approved'))
                                {{ $loan->loan_status }}
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            <!-- Show Approve button only if the loan is not approved -->
                            @if ($loan->application_status !== 'approved')
                                <form action="{{ route('loanmanagement.approve', $loan->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"
                                        onclick="confirmAction(event, 'Loan', 'approve')">Approve</button>
                                </form>
                            @endif

                            <!-- Show Reject button only if the loan is pending -->
                            @if ($loan->application_status === 'pending')
                                <form action="{{ route('loanmanagement.reject', $loan->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning"
                                        onclick="confirmAction(event, 'Loan', 'reject')">Reject</button>
                                </form>
                            @endif

                            <!-- Show Repaid button only if the loan is approved and not repaid -->
                            @if ($loan->application_status === 'approved' && $loan->loan_status !== 'repaid')
                                <form action="{{ route('loanmanagement.repaid', $loan->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        onclick="confirmAction(event, 'Loan', 'repay')">Repay</button>
                                </form>
                            @endif

                            @if ($loan->application_status === 'pending')
                                <!-- Edit button (always visible) -->
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                    data-bs-target="#editLoanModal{{ $loan->id }}">Edit</button>
                                {{-- delete --}}
                                <form action="{{ route('loanmanagement.destroy', $loan->id) }}" method="POST"
                                    class="d-inline">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="confirmAction(event, 'Loan', 'delete')">Delete</button>
                                </form>
                            @endif
                        </td>

                        </td>
                    </tr>

                    <!-- Edit Loan Modal -->
                    <div class="modal fade" id="editLoanModal{{ $loan->id }}" tabindex="-1"
                        aria-labelledby="editLoanModalLabel{{ $loan->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="editLoanModalLabel{{ $loan->id }}">Edit
                                        {{ $loan->farmer->first_name . ' ' . $loan->farmer->last_name }}'s Loan</h6>
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
                                        <button type="submit" class="create-button btn btn-sm">
                                            <span class="material-icons">add</span> save changes
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>

        </table>
        {{ $loans->links('pagination::bootstrap-5') }}
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
                            <input type="number" class="form-control" id="repayment_duration" name="repayment_duration"
                                required min="1">
                        </div>
                        <button type="submit" class="create-button btn btn-sm">
                            <span class="material-icons">add</span> Submit
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        function confirmAction(event, module, action) {
            event.preventDefault();
            let confirmationText = '';
            switch (action) {
                case 'approve':
                    confirmationText = `Do you want to approve the ${module} loan?`;
                    break;
                case 'reject':
                    confirmationText = `Do you want to reject the ${module} loan?`;
                    break;
                case 'repay':
                    confirmationText = `Do you want to mark the ${module} loan as repaid?`;
                    break;
                case 'delete':
                    confirmationText = `Do you want to delete the ${module} loan?`;
                    break;
                default:
                    confirmationText = `Do you want to perform the ${action} action on the ${module} loan?`;
                    break;
            }
            Swal.fire({
                title: 'Are you sure?',
                text: confirmationText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, do it!',
                cancelButtonText: 'No, cancel',
                heightAuto: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = event.target.closest('form').action;
                }
            });
        }
    </script>
@endsection
