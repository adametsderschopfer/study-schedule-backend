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

    public static function saveOrCreate(ExternalAccount $externalAccount)
    {
        $account = Account::where('external_id', $externalAccount->getExternalId())->first();

        $data = $externalAccount->serialize();

        if ($account) {
            $account->update($data);
        } else {
            Account::create($data);
        }

        return $account->fresh();
    }
}
