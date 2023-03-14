<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Group;

class Faculty extends Model
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

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function teachers()
    {
        return $this->morphToMany(Teacher::class, 'teacherable');
    }

    public function subjects()
    {
        return $this->morphToMany(Subject::class, 'subjectable');
    }

    public function groups()
    {
        return $this->morphToMany(Group::class, 'groupable');
    }

    public function hasAccount(int $account_id): bool
    {
        if ($this->account_id == $account_id) {
            return true;
        }
        return false;
    }
}
