<?php

namespace App\Http\Controllers\API\v1\Client;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Services\AccountService;

class AccountClientController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/client/me",
     *   tags={"Client Account"},
     *   summary="Получение информации об аккаунте",
     *   operationId="get_client_account",
     * 
     *   @OA\Response(
     *      response=200,
     *      description="type: 0 = Университет, 1 = Колледж, 2 = Школа",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
     * @param Request $request
     * @return bool
     */
    public function index()
    {
        return Account::findOrFail($this->accountService->getId());
    }
}
