<?php

namespace App\Services;

interface ExternalAccountService
{
    public function getData();

    public function setData(array $data);
}