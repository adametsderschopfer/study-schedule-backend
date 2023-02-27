<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentTeacher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'department_id', 
        'full_name',
        'position', 
        'degree'
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
}
