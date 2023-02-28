<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\DTO\ExternalAccount;
use App\Models\ScheduleSetting;
use App\Models\Faculty;
use App\Models\Teacher;

class Account extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const EXTERNAL_ACCOUNT_ID_HEADER_KEY = 'X-Account-Id';

    public const TYPES = [
        'UNIVERSITY' => 0,
        'COLLEGE' => 1,
        'SCHOOL' => 2
    ];

    protected $fillable = [
        'external_id', 
        'name',
        'email',
        'role',
    ];

    protected $hidden = [
        'id',
        'external_id',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getData(): array
    {
        return [
            'id' => $this->id,
            'external_id' =>  $this->external_id,
            'email' => $this->email,
            'name' => $this->name,
            'role' => $this->role
        ];
    }

    public function schedule_settings() {
        return $this->hasMany(ScheduleSetting::class);
    }

    public function faculties() {
        return $this->hasMany(Faculty::class);
    }

    public function teachers()
    {
        return $this->morphToMany(Teacher::class, 'teacherable');
    }

    public function hasAccount(int $account_id): bool
    {
        if ($this->id == $account_id) {
            return true;
        }
        return false;
    }

    public static function saveOrCreate(ExternalAccount $externalAccount)
    {
        $account = Account::where('external_id', $externalAccount->getExternalId())->first();

        $data = $externalAccount->serialize();

        if ($account) {
            $account->update($data);
        } else {
            $account = Account::create($data);
        }

        return $account->fresh();
    }

    public function getId(): int
    {
        return $this->id;
    }
}
