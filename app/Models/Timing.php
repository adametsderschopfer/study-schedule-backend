<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timing extends Model
{
    use HasFactory;

    protected $fillable = [
        'mode_id', 
        'time_start',
        'time_end',
        'offset'
    ];

    protected $hidden = [
        'id',
        'mode_id',
        'offset'
    ];

    protected $casts = [
        'mode_id' => 'integer', 
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i',
        'offset' => 'integer'
    ];

    public $timestamps = false;
}
