<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\ScheduleSetting;

class ScheduleSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account_id = Account::first()->getId();

        $scheduleSetting = ScheduleSetting::firstOrCreate([
            'account_id' => $account_id, 
            'name' => 'Test setting',
        ]);

        $scheduleSettingItems = array_fill(0, 3, []);

        $scheduleSetting->giveScheduleSettingItems($scheduleSettingItems);
    }
}
