<?php

namespace App\DTO;

use App\Services\AccountService;

class AccountRepository implements AccountService
{
    public int $id;
    public int $external_id;
    public string $name;
    public string $email;
    public string $role;

    public function __construct(
        $id = 0,
        $external_id = 0, 
        $name = '',
        $email = '', 
        $role = ''
    )
    {
        $this->id = $id;
        $this->external_id = $external_id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role
        ];
    }

    public function setData(array $data)
    {
        $this->id = $data['id'];
        $this->external_id = $data['external_id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->role = $data['role'];
    }

    public function getData(): self
    {
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
}