<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;
use App\Models\BuildingClassroom;

class BuildingClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $building = Building::first();

        BuildingClassroom::factory()->count(3)->create([
            'building_id' => $building->id,
        ]);
    }
}
