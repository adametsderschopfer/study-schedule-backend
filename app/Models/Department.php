<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Faculty;
use App\Models\Account;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id', 
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'account',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public static function facultyHasAccount(int $faculty_id, int $account_id): bool
    {
        return Faculty::where('id', $faculty_id)
                            ->where('account_id', $account_id)
                            ->first();
    }
}
