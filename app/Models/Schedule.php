<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Department;
use App\Models\ScheduleSetting;
use App\Models\ScheduleSettingItem;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Building;
use App\Models\BuildingClassroom;

class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const REPEATABILITIES = [
        'ONCE' => 0,
        'EVERY' => 1,
        'EVEN' => 2,
        'ODD' => 3
    ];

    protected $fillable = [
        'account_id', 
        'department_id', 
        'schedule_setting_id',
        'teacher_id', 
        'building_id',
        'building_classroom_id',
        'schedule_setting_item_order',
        'day_of_week',
        'repeatability',
        'type',
        'group_id', 
        'subject_id', 
        'sub_group',
        'repeat_start',
        'repeat_end'
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
        'repeat_start' => 'datetime:Y-m-d',
        'repeat_end' => 'datetime:Y-m-d',
        'department_id' => 'integer',
        'schedule_setting_id' => 'integer',
        'teacher_id' => 'integer',
        'building_id' => 'integer',
        'building_classroom_id' => 'string',
        'schedule_setting_item_order' => 'integer',
        'day_of_week' => 'integer',
        'repeatability' => 'integer',
        'type' => 'integer',
        'group_id' => 'integer',
        'sub_group' => 'integer'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function schedule_setting()
    {
        return $this->belongsTo(ScheduleSetting::class);
    }

    public function schedule_setting_item()
    {
        return ScheduleSettingItem::where('schedule_setting_id', $this->schedule_setting_id)
                    ->where('order', $this->schedule_setting_item_order)
                    ->get() ?? null;
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function building_classroom()
    {
        return $this->belongsTo(BuildingClassroom::class);
    }

    public static function checkRelations(array $input, $account_id): bool
    {
        $hasDepartment = true;
        if (isset($input['department_id'])) {
            $department = Department::findOrFail($input['department_id']);
            if (!$department->hasAccount($account_id)) {
                $hasDepartment = false;
            }
        }

        $scheduleSetting = ScheduleSetting::findOrFail($input['schedule_setting_id']);
        $subject = Subject::findOrFail($input['subject_id']);
        $group = Group::findOrFail($input['group_id']);
        $teacher = Teacher::findOrFail($input['teacher_id']);
        $building = Building::findOrFail($input['building_id']);
        $buildingClassroom = BuildingClassroom::findOrFail($input['building_classroom_id']);

        return (
            $hasDepartment &&
            $scheduleSetting->hasAccount($account_id) &&
            $subject->hasAccount($account_id) &&
            $group->hasAccount($account_id) &&
            $teacher->hasAccount($account_id) && 
            $building->hasAccount($account_id) &&
            $buildingClassroom->hasAccount($account_id)
        );
    }
}
