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
     * path="/api/v1/client/schedules?date_start={dateStart}&date_end={dateEnd}&teacher_id={teacherId}&group_id={groupId}&building_id={buildingId}&building_classroom_id={buildingClassroomId}",
     *   tags={"Schedules Client"},
     *   summary="Получение списка расписаний",
     *   operationId="get_client_schedules",
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
     *   @OA\Parameter(
     *      name="teacherId",
     *      in="path",
     *      required=false, 
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * 
     *   @OA\Parameter(
     *      name="groupId",
     *      in="path",
     *      required=false, 
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * 
     *   @OA\Parameter(
     *      name="buildingId",
     *      in="path",
     *      required=false, 
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * 
     *   @OA\Parameter(
     *      name="buildingClassroomId",
     *      in="path",
     *      required=false, 
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

        return $this->scheduleAction->get($input);
    }
}