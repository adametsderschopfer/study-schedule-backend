<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Faculty;
use App\Models\Account;
use App\Models\Group;
use App\Models\Teacher;
use App\Models\Subject;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'faculty_id', 
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'account',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function account()
    {
        return $this->hasOneThrough(
            Account::class,
            Faculty::class, 
            'id', 
            'id', 
            'faculty_id', 
            'account_id'
        );
    }

    public function groups()
    {
        return $this->morphToMany(Group::class, 'groupable');
    }

    public function teachers()
    {
        return $this->morphToMany(Teacher::class, 'teacherable');
    }

    public function subjects()
    {
        return $this->morphToMany(Subject::class, 'subjectable');
    }

    public function hasAccount(int $account_id): bool
    {
        if ($this->account->id == $account_id) {
            return true;
        }
        return false;
    }
}
