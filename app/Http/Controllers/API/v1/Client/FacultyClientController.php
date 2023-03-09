<?php

namespace App\Http\Controllers\API\v1\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Faculty;

class FacultyClientController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/client/faculties",
     *   tags={"Faculties Client"},
     *   summary="Получение списка факультетов",
     *   operationId="get_client_faculties",
     * 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
     * @param Request $request
     * @return bool
     */
    protected function index()
    {
        return Faculty::where('account_id', $this->accountService->getId())
            ->with(['departments' => function($q)
                {
                    $q->with('department_groups');
                }
            ])
            ->get();
    }
}
