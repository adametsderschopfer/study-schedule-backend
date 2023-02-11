<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Services\AccountService;

class AccountController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

    public function index()
    {
        return Account::findOrFail($this->accountService->getId());
    }
}
