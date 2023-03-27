<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\DTO\ExternalAccount;
use App\Services\AccountService;
use App\Models\Account;

class ExternalAuthRequest
{
    private const TOKEN_KEY = 'token';

    public function __construct(AccountService $accountService)
    {
        $this->auth_url = config('auth.auth_app.url');
        $this->accountService = $accountService;
    }

    public function getExternalAccountData(): ?ExternalAccount
    {
        $headers = getallheaders();
        
        if (!isset($headers[self::TOKEN_KEY]) || !isset($headers[Account::EXTERNAL_ACCOUNT_ID_HEADER_KEY])) {
            return false;
        }

        $response = Http::withHeaders([
                self::TOKEN_KEY => $headers[self::TOKEN_KEY]
            ])
            ->get($this->auth_url);
                    
        if (!$response->successful()) {
            session()->flush();
            return false;
        }

        $accountInfo = $response->json();

        if ($accountInfo['id'] !== (int) $headers[Account::EXTERNAL_ACCOUNT_ID_HEADER_KEY]) {
            return false;
        }

        $externalAccountData = [
            'external_id' =>  $accountInfo['id'],
            'email' => $accountInfo['email'],
            'name' => $accountInfo['firstName'],
            'role' => $accountInfo['role']
        ];

        $externalAccount = new ExternalAccount();
        $externalAccount->setData($externalAccountData);

        return $externalAccount;
    }

    public function sendWithCreate()
    {
        $externalAccount = $this->getExternalAccountData();
        $account = Account::saveOrCreate($externalAccount);

        if (!$account) {
            return false;
        }

        $this->accountService->setData($account->getData());

        return true;
    }

    public function sendWithCheck()
    {
        $externalAccount = $this->getExternalAccountData();
        $account = Account::where('external_id', $externalAccount->getExternalId())->first();

        $this->accountService->setData($account->getData());

        return true;
    }
}
