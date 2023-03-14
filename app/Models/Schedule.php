<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Department;
use App\Models\ScheduleSetting;
use App\Models\ScheduleSettingItem;
use App\Models\DepartmentSubject;
use App\Models\Teacher;

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
        'department_subject_id', 
        'department_group_id',
        'teacher_id', 
        'shedule_setting_item_order',
        'day_of_week',
        'repeatability',
        'type',
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
        'department_subject_id' => 'integer',
        'department_group_id' => 'integer',
        'teacher_id' => 'integer',
        'shedule_setting_item_order' => 'integer',
        'day_of_week' => 'integer',
        'repeatability' => 'integer',
        'type' => 'integer',
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
                    ->where('order', $this->shedule_setting_item_order)
                    ->get() ?? null;
    }

    public function department_subject()
    {
        return $this->belongsTo(DepartmentSubject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public static function checkRelations(array $input, $account_id): bool
    {
        $department = Department::findOrFail($input['department_id']);
        $scheduleSetting = ScheduleSetting::findOrFail($input['schedule_setting_id']);
        $departmentSubject = DepartmentSubject::findOrFail($input['department_subject_id']);
        $departmentGroup = DepartmentGroup::findOrFail($input['department_group_id']);
        $teacher = Teacher::findOrFail($input['teacher_id']);

        return (
            $department->hasAccount($account_id) &&
            $scheduleSetting->hasAccount($account_id) &&
            $departmentSubject->hasAccount($account_id) &&
            $departmentGroup->hasAccount($account_id) &&
            $teacher->hasAccount($account_id)
        );
    }
}
