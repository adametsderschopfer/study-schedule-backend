<?php

namespace App\Services;

interface AccountService
{
    public function setData(array $data);

    public function getId();
}