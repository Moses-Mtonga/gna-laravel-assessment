<?php

namespace Modules\FarmSupport\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\FarmSupport\Database\Factories\SupportedProductFactory;

class SupportedProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];

    public function farmSupports()
    {
        return $this->belongsToMany(FarmSupport::class, 'farm_support_product');
    }

    // protected static function newFactory(): SupportedProductFactory
    // {
    //     // return SupportedProductFactory::new();
    // }
}
