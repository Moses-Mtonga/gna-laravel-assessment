<?php

namespace Modules\FarmSupport\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\FarmSupport\Models\SupportedProduct;

class FarmSupportDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create supported products  
        $products = [
            [
                'name' => 'Seeds',
            ],
            [
                'name' => 'Fertilizers',
            ],
            [
                'name' => 'Pesticides',
            ],
        ];

        // Insert supported products into the database  
        foreach ($products as $product) {
            SupportedProduct::create($product);
        }
    }
}
