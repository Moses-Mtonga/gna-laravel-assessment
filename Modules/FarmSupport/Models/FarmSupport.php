<?php

namespace Modules\FarmSupport\Models;

use App\Models\Farmer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\FarmSupport\Database\Factories\FarmSupportFactory;

class FarmSupport extends Model
{
    protected $fillable = ['farmer_id', 'description'];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function products()
    {
        return $this->belongsToMany(SupportedProduct::class, 'farm_support_products', 'farm_support_id', 'product_id');
    }
}
