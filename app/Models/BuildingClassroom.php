<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BuildingClassroom extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'building_id', 
        'name'
    ];

    protected $hidden = [
        'building_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function account()
    {
        return $this->hasOneThrough(
            Account::class,
            Building::class, 
            'id', 
            'id', 
            'building_id', 
            'account_id'
        );
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function hasAccount(int $account_id): bool
    {
        if ($this->account->id == $account_id) {
            return true;
        }
        return false;
    }
}
