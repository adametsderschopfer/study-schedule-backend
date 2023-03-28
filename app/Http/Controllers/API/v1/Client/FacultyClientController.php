<?php

namespace App\Http\Controllers\API\v1\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Faculty;
use App\Models\Account;

class FacultyClientController extends Controller
{
    private const FACULTIES_CLIENT_DEFAULT_LIMIT = 30;

    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/client/faculties?page={page}",
     *   tags={"Faculties Client"},
     *   summary="Получение списка факультетов",
     *   operationId="get_client_faculties",
     * 
     *   @OA\Parameter(
     *      name="page",
     *      in="path",
     *      required=false,
     *      @OA\Schema(
     *           type="int"
     *      )
     *   ),
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
        $faculties = Faculty::where('account_id', $this->accountService->getId())
                ->with(['departments' => function($q)
                    {
                        $q->with('groups');
                        $q->with('teachers');
                        $q->with('subjects');
                    }
                ])
                ->with('subjects')
                ->with('groups')
                ->with('teachers')
                ->paginate(self::FACULTIES_CLIENT_DEFAULT_LIMIT);

        return $this->sendPaginationResponse($faculties);
    }
}
