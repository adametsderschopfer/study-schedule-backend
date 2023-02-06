<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\DTO\ExternalAccount;

class ExternalLoggedIn
{
    use Dispatchable;

    public function __construct(ExternalAccount $externalAccount)
    {
        $this->externalAccount = $externalAccount;
    }
}
