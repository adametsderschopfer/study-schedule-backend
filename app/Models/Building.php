<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Building extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'account_id', 
        'name',
        'address'
    ];

    protected $hidden = [
        'account_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function building_classrooms()
    {
        return $this->hasMany(BuildingClassroom::class);
    }

    public function hasAccount(int $account_id): bool
    {
        if ($this->account_id == $account_id) {
            return true;
        }
        return false;
    }

    public function updateBuildingsClassrooms(array $inputClassrooms): void
    {
        $this->removeBuildingsClassrooms($inputClassrooms);
        $this->giveBuildingsClassrooms($inputClassrooms);
    }

    public function giveBuildingsClassrooms(array $inputClassrooms): void
    {
        foreach ($inputClassrooms as $inputClassroom) {
            $buildingClassroom = BuildingClassroom::find($inputClassroom['id']);
            $arr[] = $inputClassroom['id'];
            if (!$buildingClassroom) {
                $inputClassroom['building_id'] = $this->id;
                BuildingClassroom::create($inputClassroom);
            } else {
                $buildingClassroom->name = $inputClassroom['name'];
                $buildingClassroom->update();
            }
        }
    }

    private function removeBuildingsClassrooms(array $inputClassrooms): void
    {
        $buildingClassrooms = $this->building_classrooms()->pluck('id');
        foreach ($buildingClassrooms as $value) {
            $key = array_search($value, array_column($inputClassrooms, 'id'));
            if ($key === false) {
                $buildingClassroom = BuildingClassroom::find($value);
                if ($buildingClassroom) {
                    $buildingClassroom->delete();
                }
            }
        }
    }
}
