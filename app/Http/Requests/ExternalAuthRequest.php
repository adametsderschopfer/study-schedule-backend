<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Events\ExternalAuthorized;

class ExternalAuthRequest
{
    public function __construct(Request $request)
    {
        $this->token_key = config('auth.auth_app.token_key');
        $this->auth_url = config('auth.auth_app.url');
        $this->token = $request->header($this->token_key);
    }

    public function send(): ?array
    {
        if (!$this->token) {
            return null;
        }

        $response = Http::withHeaders(
            [
                $this->token_key => $this->token
            ]
        )->get($this->auth_url);

        if (!$response->successful()) {
            session()->flush();
            return null;
        }

        $accountData = $response->json();

        $data = [
            'account_id' => $accountData['id'],
            'email' => $accountData['email'],
            'name' => $accountData['firstName'],
            'role' => $accountData['role'],
        ];

        session(['account_id' => $accountData['id']]);
        session(['role' => $accountData['role']]);

        ExternalAuthorized::dispatch($data);

        return $data;
    }
}
