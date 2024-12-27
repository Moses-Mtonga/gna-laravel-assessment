<?php
namespace Database\Seeders;  

use Illuminate\Database\Seeder;  
use App\Models\Farmer;  

class FarmersTableSeeder extends Seeder  
{  
    /**  
     * Run the database seeds.  
     *  
     * @return void  
     */  
    public function run()  
    {  
        // Create farmers  
        $farmers = [  
            [  
                'first_name' => 'Moses',  
                'last_name' => 'Mtonga',  
                'phone' => '0976543210',  
                'location' => 'Lusaka',  
            ],  
            [  
                'first_name' => 'Katele',  
                'last_name' => 'Lumande',  
                'phone' => '0965432109',  
                'location' => 'Kitwe',  
            ],  
            [  
                'first_name' => 'Patrick',  
                'last_name' => 'Tembo',  
                'phone' => '0954321098',  
                'location' => 'Ndola',  
            ],  
            [  
                'first_name' => 'Jonas',  
                'last_name' => 'Mwansa',  
                'phone' => '0943210987',  
                'location' => 'Kabwe',  
            ],  
            [  
                'first_name' => 'Dorcus',  
                'last_name' => 'Tembo',  
                'phone' => '0932109876',  
                'location' => 'Chingola',  
            ],  
        ];  

        // Insert farmers into the database  
        foreach ($farmers as $farmer) {  
            Farmer::create($farmer);  
        }  
    }  
}