<?php

namespace App\Listeners;

use App\Events\ExternalAuthorized;
use App\Http\Controllers\API\v1\Admin\AccountController;

class SaveOrCreateAccount
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AccountController $accountController)
    {
        $this->accountController = $accountController;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ExternalAuthorized  $event
     * @return void
     */
    public function handle(ExternalAuthorized $event)
    {
        $this->accountController->saveOrCreate($event->getAccountData());
    }
}
