<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $data = Account::where('external_id', session('account_id'))->first();
        return $this->sendResponse($data);
    }

    public function saveOrCreate(array $data): void
    {
        $account = Account::where('external_id', session('account_id'))->first();

        $data['external_id'] = $data['account_id'];

        if ($account) {
            $account->update($data);
        } else {
            Account::create($data);
        }
    }
}
