<?php

namespace App\Http\Controllers\API\v1\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Http\Requests\ScheduleGetRequest;
use App\Http\Actions\v1\ScheduleAction;
use DateTime;

class ScheduleClientController extends Controller
{
    public function __construct(
        AccountService $accountService, 
        ScheduleAction $scheduleAction
    ) {
        $this->accountService = $accountService;
        $this->scheduleAction = $scheduleAction;
    }

     /**
     * @OA\Get(
     * path="/api/v1/client/schedules?page={page}&date_start={dateStart}&date_end={dateEnd}",
     *   tags={"Schedules Client"},
     *   summary="Получение списка расписаний",
     *   operationId="get_client_schedules",
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
     *   @OA\Parameter(
     *      name="dateStart",
     *      in="path",
     *      required=true, 
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * 
     *   @OA\Parameter(
     *      name="dateEnd",
     *      in="path",
     *      required=true, 
     *      @OA\Schema(
     *           type="string"
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
    protected function index(ScheduleGetRequest $request)
    {
        $input = $request->validated();

        $schedulesData = $this->scheduleAction->get($input);

        return $this->sendPaginationResponse($schedulesData['data'], $schedulesData['includes']);
    }
}