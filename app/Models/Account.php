<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\DTO\ExternalAccount;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id', 
        'name',
        'email',
        'role',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getId(): string
    {
        return $this->id;
    }

    public function getExternalId(): string
    {
        return $this->external_id;
    }

    public static function saveOrCreate(ExternalAccount $externalAccount): void
    {
        $account = Account::where('external_id', $externalAccount->getId())->first();

        $data = $externalAccount->serialize();

        if ($account) {
            $account->update($data);
        } else {
            Account::create($data);
        }
    }
}
