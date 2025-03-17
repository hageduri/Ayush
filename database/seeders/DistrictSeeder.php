<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/districts.json'));
        $districts = json_decode($json, true);

        foreach ($districts as $district) {
            District::create([
                'district_code' => $district['district_code'],
                'district_name' => $district['district_name'],
            ]);
        }
    }
}
