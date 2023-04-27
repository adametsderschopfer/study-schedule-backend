<?php

namespace App\DTO;

class ExternalAccount
{
    public int $external_id;
    public string $name;
    public string $email;
    public string $role;
    public string $type;

    public function __construct(
        $external_id = 0, 
        $name = '',
        $email = '', 
        $role = '',
        $type = 0
    )
    {
        $this->external_id = $external_id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->type = $type;
    }

    public function serialize(): array
    {
        return [
            'external_id' => $this->external_id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'type' => $this->type
        ];
    }

    public function setData(array $data)
    {
        $this->external_id = $data['external_id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->role = $data['role'];
        $this->type = $data['type'];
    }

    public function getExternalId(): int
    {
        return $this->external_id;
    }
}