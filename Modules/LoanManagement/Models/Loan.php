<?php

namespace Modules\LoanManagement\Models;

use App\Models\Farmer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\LoanManagement\Database\Factories\LoanFactory;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'farmer_id',
        'amount',
        'interest_rate',
        'repayment_duration',
        'application_status',
        'loan_status',
    ];

    // protected static function newFactory(): LoanFactory
    // {
    //     // return LoanFactory::new();
    // }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}