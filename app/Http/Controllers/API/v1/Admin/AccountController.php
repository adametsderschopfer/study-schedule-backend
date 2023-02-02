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
        $account = Account::select('external_id')->where('external_id', session('account_id'))->first();

        if (!$account) {
            $data['external_id'] = $data['account_id'];
            $account = Account::create($data);
        }
    }
}
