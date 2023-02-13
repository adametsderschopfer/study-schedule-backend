<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::firstOrCreate([
            'external_id' => 21, 
            'email' => 'hotel@fake.com',
            'name' => 'Administrator', 
            'role' => 'Client',
        ]);
    }
}
