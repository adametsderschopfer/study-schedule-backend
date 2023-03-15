<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AccountSeeder::class,
            ScheduleSettingSeeder::class,
            FacultySeeder::class,
            DepartmentSeeder::class,
            TeacherSeeder::class,
            SubjectSeeder::class,
            GroupSeeder::class,
            BuildingSeeder::class,
            BuildingClassroomSeeder::class,
            ScheduleSeeder::class,
        ]);
    }
}
