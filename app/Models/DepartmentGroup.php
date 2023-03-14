<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'department_id', 
        'name',
        'sub_group',
        'degree',
        'year_of_education',
        'form_of_education'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'account',
        'department', 
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

    public function account()
    {
        return $this->department->account();
    }

    public function hasAccount(int $account_id): bool
    {
        if ($this->account->id == $account_id) {
            return true;
        }
        return false;
    }
}
