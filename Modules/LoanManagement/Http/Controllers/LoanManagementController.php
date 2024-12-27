<?php

namespace Modules\LoanManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use Exception;
use Illuminate\Http\Request;
use Modules\LoanManagement\Models\Loan;

class LoanManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with('farmer')->paginate(10);
        $farmers = Farmer::all();
        return view('loanmanagement::index', compact('loans', 'farmers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data  
        $request->validate([
            'farmer_id' => 'required|integer|exists:farmers,id',
            'amount' => 'required|numeric',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'repayment_duration' => 'required|integer|min:1',
        ]);

        // Check if the farmer has any existing loans with a status of 'not paid'  
        $existingLoans = Loan::where('farmer_id', $request->farmer_id)
            ->where('loan_status', '!=', 'repaid')
            ->get();

        if ($existingLoans->count() > 0) {
            // Redirect back with an error message if the farmer has an existing loan  
            return redirect()->route('loanmanagement.index')->with('error', 'Farmer has an existing loan that is not yet paid.');
        }

        try {
            // Create a new loan with default status  
            Loan::create(array_merge(
                $request->all(),
                ['application_status' => 'pending'],
                ['loan_status' => 'not repaid']
            ));

            // Redirect back with a success message  
            return redirect()->route('loanmanagement.index')->with('success', 'Loan created successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message  
            return redirect()->route('loanmanagement.index')->with('error', 'Failed to create loan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Find the loan by ID  
            $loan = Loan::findOrFail($id);

            // Update the loan  
            $loan->update($request->all());

            // Redirect back with a success message  
            return redirect()->route('loanmanagement.index')->with('success', 'Loan updated successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message  
            return redirect()->route('loanmanagement.index')->with('error', 'Failed to update loan: ' . $e->getMessage());
        }
    }

    /**  
     * Delete a loan application.  
     */
    public function destroy($id)
    {
        try {
            // Find the loan by ID  
            $loan = Loan::findOrFail($id);

            // Check if the loan has already been repaid  
            if ($loan->loan_status == 'repaid') {
                return redirect()->route('loanmanagement.index')->with('error', 'Cannot delete a repaid loan.');
            }

            // Delete the loan  
            $loan->delete();

            // Redirect back with a success message  
            return redirect()->route('loanmanagement.index')->with('success', 'Loan deleted successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message  
            return redirect()->route('loanmanagement.index')->with('error', 'Failed to delete loan: ' . $e->getMessage());
        }
    }

    /**
     * Approve a loan application.
     */
    public function approve($id)
    {
        try {
            // Find the loan by ID
            $loan = Loan::findOrFail($id);

            // Update loan status to approved
            $loan->update(['application_status' => 'approved']);

            // Redirect back with a success message
            return redirect()->route('loanmanagement.index')->with('success', 'Loan approved successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message
            return redirect()->route('loanmanagement.index')->with('error', 'Failed to approve loan: ' . $e->getMessage());
        }
    }

    /**
     * Reject a loan application.
     */
    public function reject($id)
    {
        try {
            // Find the loan by ID
            $loan = Loan::findOrFail($id);

            // Update loan status to rejected
            $loan->update(['application_status' => 'rejected']);
            $loan->update(['loan_status' => 'not repaid']);

            // Redirect back with a success message
            return redirect()->route('loanmanagement.index')->with('success', 'Loan rejected successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message
            return redirect()->route('loanmanagement.index')->with('error', 'Failed to reject loan: ' . $e->getMessage());
        }
    }
    public function repaid($id)
    {
        try {
            // Find the loan by ID
            $loan = Loan::findOrFail($id);

            // Update loan payment status
            $loan->update(['loan_status' => 'repaid']);

            // Redirect back with a success message
            return redirect()->route('loanmanagement.index')->with('success', 'Loan updated to paid successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message
            return redirect()->route('loanmanagement.index')->with('error', 'Failed to update loan: ' . $e->getMessage());
        }
    }
}
