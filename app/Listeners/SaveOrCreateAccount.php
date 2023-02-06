<?php

namespace App\Listeners;

use App\Events\ExternalLoggedIn;
use App\Http\Controllers\API\v1\Admin\AccountController;
use Illuminate\Support\Facades\App;
use App\Services\ExternalAccountService;
use App\Models\Account;

class SaveOrCreateAccount
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AccountController $accountController, ExternalAccountService $externalAccountService)
    {
        $this->accountController = $accountController;
        $this->externalAccountService = $externalAccountService;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ExternalLoggedIn  $event
     * @return void
     */
    public function handle(ExternalLoggedIn $event)
    {
        Account::saveOrCreate($this->externalAccountService);
    }
}
