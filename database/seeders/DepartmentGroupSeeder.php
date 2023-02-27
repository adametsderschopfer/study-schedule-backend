<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\DepartmentGroup;

class DepartmentGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department_id = Department::first()->id;

        DepartmentGroup::factory()->count(3)->create([
            'department_id' => $department_id,
        ]);
    }
}
