<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSettingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_setting_id', 
        'time_start',
        'time_end',
        'order'
    ];

    protected $hidden = [
        'id',
        'schedule_setting_id',
        'order'
    ];

    protected $casts = [
        'schedule_setting_id' => 'integer', 
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i',
        'order' => 'integer'
    ];

    public $timestamps = false;
}
