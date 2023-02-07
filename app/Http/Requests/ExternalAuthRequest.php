<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\DTO\ExternalAccount;
use App\Services\AccountService;
use App\Models\Account;

class ExternalAuthRequest
{
    public function __construct(Request $request, AccountService $accountService)
    {
        $this->token_key = config('auth.auth_app.token_key');
        $this->auth_url = config('auth.auth_app.url');
        $this->token = $request->header($this->token_key);
        $this->accountService = $accountService;
    }

    public function send(): bool
    {
        if (!$this->token) {
            return false;
        }

        $response = Http::withHeaders(
            [
                $this->token_key => $this->token
            ]
        )->get($this->auth_url);

        if (!$response->successful()) {
            session()->flush();
            return false;
        }

        $accountInfo = $response->json();

        $externalAccountData = [
            'external_id' =>  $accountInfo['id'],
            'email' => $accountInfo['email'],
            'name' => $accountInfo['firstName'],
            'role' => $accountInfo['role']
        ];

        $externalAccount = new ExternalAccount();
        $externalAccount->setData($externalAccountData);

        $account = Account::saveOrCreate($externalAccount);

        if (!$account) {
            return false;
        }

        $this->accountService->setData($account->getData());

        return true;
    }
}
