<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name'
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

    public function subjectable()
    {
        return $this->morphTo();
    }

    public function faculties()
    {
        return $this->morphedByMany(Faculty::class, 'subjectable');
    }

    public function departments()
    {
        return $this->morphedByMany(Department::class, 'subjectable');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function hasAccount(int $account_id): bool
    {
        if ($this->account_id == $account_id) {
            return true;
        }
        return false;
    }
}
