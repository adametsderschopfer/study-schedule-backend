<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\ExternalAccountService;
use App\Models\Account;

class ExternalAuthRequest
{
    public function __construct(Request $request, ExternalAccountService $externalAccountService)
    {
        $this->token_key = config('auth.auth_app.token_key');
        $this->auth_url = config('auth.auth_app.url');
        $this->token = $request->header($this->token_key);
        $this->externalAccountService = $externalAccountService;
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
