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
        'department_id', 
        'schedule_setting_id', 
        'department_subject_id', 
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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

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
        return ScheduleSettingItem::where('schedule_setting_id', $this->schedule_setting->id)
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

    public function account()
    {
        return $this->department->account();
    }
}
