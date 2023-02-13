<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account_id = Account::first()->getId();

        $setting = Setting::firstOrCreate([
            'account_id' => $account_id, 
            'name' => 'Test setting',
        ]);

        $settingItems = array_fill(0, 3, []);

        $setting->giveSettingItems($settingItems);
    }
}
