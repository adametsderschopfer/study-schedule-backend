<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\DepartmentSubject;

class DepartmentSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department_id = Department::first()->id;

        DepartmentSubject::factory()->count(3)->create([
            'department_id' => $department_id,
        ]);
    }
}
