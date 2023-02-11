<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_id', 
        'time_start',
        'time_end',
        'offset'
    ];

    protected $hidden = [
        'id',
        'setting_id',
        'offset'
    ];

    protected $casts = [
        'setting_id' => 'integer', 
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i',
        'offset' => 'integer'
    ];

    public $timestamps = false;
}
