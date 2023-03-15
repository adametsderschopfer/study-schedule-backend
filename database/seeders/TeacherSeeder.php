<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Department;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account_id = Account::first()->getId();
        $department = Department::first();

        $teachers = Teacher::factory()->count(3)->make([
            'account_id' => $account_id,
        ]);

        $department->teachers()->saveMany($teachers);
    }
}
