<?php

namespace App\DTO;

use App\Services\ExternalAccountService;

class ExternalAccount implements ExternalAccountService
{
    public int $external_id;
    public string $name;
    public string $email;
    public string $role;

    public function __construct(
        $external_id = 0, 
        $name = '',
        $email = '', 
        $role = ''
    )
    {
        $this->external_id = $external_id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }

    public function serialize(): array
    {
        return [
            'external_id' => $this->external_id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role
        ];
    }

    public function setData(array $data)
    {
        $this->external_id = $data['external_id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->role = $data['role'];
    }

    public function getId()
    {
        return $this->external_id;
    }
}