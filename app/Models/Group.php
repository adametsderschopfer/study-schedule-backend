<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'account_id', 
        'teacher_id', 
        'letter', 
        'sub_group',
        'degree',
        'year_of_education',
        'form_of_education',
        'name'
    ];

    protected $hidden = [
        'account_id', 
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot'
    ];

    protected $casts = [
        'teacher_id' => 'integer',
        'sub_group' => 'integer',
        'degree' => 'integer',
        'year_of_education' => 'integer',
        'form_of_education' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function groupable()
    {
        return $this->morphTo();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function faculties()
    {
        return $this->morphedByMany(Faculty::class, 'groupable');
    }

    public function departments()
    {
        return $this->morphedByMany(Department::class, 'groupable');
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
