<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::insert([
            ['name'=>'Alta Vision Solar'],
            ['name'=>'Alta Vision Power'],
            ['name'=>'AV R&D'],
            ['name'=>'IOT Solutions']
        ]);
    }
}
