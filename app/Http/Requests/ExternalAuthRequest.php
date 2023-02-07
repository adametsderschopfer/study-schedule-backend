<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\ExternalAccountService;
use App\Models\Account;

class ExternalAuthRequest
{
    private const TOKEN_KEY = 'token';

    public function __construct(ExternalAccountService $externalAccountService)
    {
        $this->auth_url = config('auth.auth_app.url');
        $this->externalAccountService = $externalAccountService;
    }

    public function send(): bool
    {
        $headers = getallheaders();
        
        if (!isset($headers[self::TOKEN_KEY])) {
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

        $accountData = [
            'external_id' =>  $accountInfo['id'],
            'email' => $accountInfo['email'],
            'name' => $accountInfo['firstName'],
            'role' => $accountInfo['role']
        ];

        $this->externalAccountService->setData($accountData);

        if (!Account::saveOrCreate($this->externalAccountService)) {
            return false;
        }

        return true;
    }
}
