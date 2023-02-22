<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ScheduleSettingItem;

class ScheduleSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'account_id', 
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'account_id',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function schedule_setting_items()
    {
        return $this->hasMany(ScheduleSettingItem::class);
    }

    public function giveScheduleSettingItems(array $scheduleSettingItems): void
    {
        $i = 0;

        foreach ($scheduleSettingItems as $item) {
            $item['schedule_setting_id'] = $this->id;
            $item['order'] = $i;

            ScheduleSettingItem::create($item);

            $i++;
        }
    }

    public function deleteScheduleSettingItems(): void
    {
        $this->schedule_setting_items()->delete();
    }
}
