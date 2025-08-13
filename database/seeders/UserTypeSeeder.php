<?php

namespace Database\Seeders;

use App\Models\Usertype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
       Usertype::insert([
            ['usertype_name'=>"top_managment"],
            ['usertype_name'=>"executive"],
            ['usertype_name'=>"technician"]
        ]);
    }
}
