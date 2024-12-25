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
        $loans = Loan::with('farmer')->get();
        $farmers = Farmer::all();
        return view('loanmanagement::index', compact('loans', 'farmers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('loanmanagement::create');
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
            'status' => 'required|string|in:approved,pending,rejected',
        ]);

        try {
            // Create a new loan
            Loan::create($request->all());

            // Redirect back with a success message
            return redirect()->route('loanmanagement.index')->with('success', 'Loan created successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message
            return redirect()->route('loanmanagement.index')->with('error', 'Failed to create loan: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('loanmanagement::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('loanmanagement::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'amount' => 'required|numeric',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'repayment_duration' => 'required|integer|min:1',
            'status' => 'required|string|in:approved,pending,rejected',
        ]);

        try {
            // Find the loan by ID
            $loan = Loan::findOrFail($id);

            // Update the loan with the new data
            $loan->update($request->all());

            // Redirect back with a success message
            return redirect()->route('loanmanagement.index')->with('success', 'Loan updated successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message
            return redirect()->route('loanmanagement.index')->with('error', 'Failed to update loan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the loan by ID
            $loan = Loan::findOrFail($id);

            // Delete the loan
            $loan->delete();

            // Redirect back with a success message
            return redirect()->route('loanmanagement.index')->with('success', 'Loan deleted successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message
            return redirect()->route('loanmanagement.index')->with('error', 'Failed to delete loan: ' . $e->getMessage());
        }
    }
}
