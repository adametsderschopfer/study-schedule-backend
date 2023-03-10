<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Schedule;

class Teacher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'account_id',
        'full_name',
        'position', 
        'degree'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function teacherable()
    {
        return $this->morphTo();
    }

    public function faculties()
    {
        return $this->morphedByMany(Faculty::class, 'teacherable');
    }

    public function departments()
    {
        return $this->morphedByMany(Department::class, 'teacherable');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getDepartmentGroups(): array
    {
        $department_groups = array();
        $schedules = $this->schedules;

        foreach ($schedules as $schedule) {
            $department_groups[] = $schedule->department_group;
        }
        
        return $department_groups;
    }

    public function hasAccount(int $account_id): bool
    {
        if ($this->account_id == $account_id) {
            return true;
        }
        return false;
    }
}
