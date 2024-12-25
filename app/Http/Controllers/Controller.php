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
        $totalFarmers = Farmer::count();
        $totalFarmersWithLoans = Loan::distinct('farmer_id')->count('farmer_id');
        $totalFarmSupportedFarmers = FarmSupport::distinct('farmer_id')->count('farmer_id');
        $totalSupportedProducts = SupportedProduct::count();
        $farmers = Farmer::all();

        return view('dashboard', compact('totalFarmers', 'totalFarmersWithLoans', 'totalFarmSupportedFarmers', 'totalSupportedProducts', 'farmers'));
    }
}
