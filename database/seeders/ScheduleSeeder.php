<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account = Account::first();
        $faculty = $account->faculties()->first();
        $department = $faculty ? $faculty->departments()->first() : null;
        $schedule_setting = $account->schedule_settings()->first();
        $subject = $account->subjects()->first();
        $building = $account->buildings()->first();
        $building_classroom = $building->building_classrooms()->first();
        $teacher = $account->teachers()->first();

        Schedule::factory()->count(3)->create([
            'account_id' => $account->id,
            'department_id' => $department->id ?? null,
            'schedule_setting_id' => $schedule_setting->id ?? null,
            'subject_id' => $subject->id ?? null,
            'building_id' => $building->id ?? null,
            'building_classroom_id' => $building_classroom->id ?? null,
            'teacher_id' => $teacher->id ?? null
        ]);
    }
}
