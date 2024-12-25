<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\LoanManagement\Models\Loan;

class Farmer extends Model
{
    // Specify the table name
    protected $table = 'farmers';

    // Specify the fillable attributes for mass assignment
    protected $fillable = ['first_name', 'last_name', 'phone', 'location'];

    // Define relationships
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
