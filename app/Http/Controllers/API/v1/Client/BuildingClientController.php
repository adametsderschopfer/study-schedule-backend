<?php

namespace App\Http\Controllers\API\v1\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Building;
use App\Models\Account;

class BuildingClientController extends Controller
{
    private const BUILDINGS_CLIENT_DEFAULT_LIMIT = 30;

    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/client/buildings?page={page}",
     *   tags={"Buildings Client"},
     *   summary="Получение списка корпусов",
     *   operationId="get_client_buildings",
     * 
     *   @OA\Parameter(
     *      name="page",
     *      in="path",
     *      required=false, 
     *      default=1,
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
        $buildings = Building::where('account_id', $this->accountService->getId())
                ->with('building_classrooms')
                ->paginate(self::BUILDINGS_CLIENT_DEFAULT_LIMIT);

        return $this->sendPaginationResponse($buildings);
    }
}
