<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = new Setting;
        $setting->name       = 'Nama kafe/restoran/warunh';
        $setting->address    = 'Jl. .....';
        $setting->telephone  = '';
        $setting->number_of_tables  = 5;
        $setting->save();
    }
}
