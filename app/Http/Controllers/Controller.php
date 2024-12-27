<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Modules\FarmSupport\Models\FarmSupport;
use Modules\FarmSupport\Models\SupportedProduct;
use Modules\LoanManagement\Models\Loan;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $activeModules = app('getActiveModules')();

        $totalFarmers = Farmer::count();
        $farmers = Farmer::paginate(10); 

        // lets create a new array to hold loan data, if loan module is actually active
        $loanData = [];

        // check if the loan module is active and add data if it is
        if (isset($activeModules['LoanManagement'])) {
            $totalFarmersWithLoans = Loan::distinct('farmer_id')->count('farmer_id');
            $totalLoans = Loan::count();
            $totalLoanAmount = Loan::where('application_status', 'approved')->sum('amount');
            $approvedLoans = Loan::where('application_status', 'approved')->count();
            $pendingLoans = Loan::where('application_status', 'pending')->count();
            $rejectedLoans = Loan::where('application_status', 'rejected')->count();

            $loanData = [
                'totalFarmersWithLoans' => $totalFarmersWithLoans,
                'totalLoans' => $totalLoans,
                'totalLoanAmount' => $totalLoanAmount,
                'approvedLoans' => $approvedLoans,
                'pendingLoans' => $pendingLoans,
                'rejectedLoans' => $rejectedLoans,
            ];
        }

        return view('dashboard', compact('totalFarmers', 'loanData', 'farmers'));
    }
}
