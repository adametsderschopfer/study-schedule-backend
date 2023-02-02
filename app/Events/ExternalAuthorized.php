<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ExternalAuthorized
{
    use Dispatchable;

    public function __construct(array $accountData)
    {
        $this->accountData = $accountData;
    }

    public function getAccountData(): array
    {
        return $this->accountData;
    }
}
