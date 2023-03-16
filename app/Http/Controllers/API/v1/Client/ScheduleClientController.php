<?php

namespace App\Http\Controllers\API\v1\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Http\Requests\ScheduleGetRequest;
use App\Http\Requests\ScheduleGetByPeriodRequest;
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
     * path="/api/v1/client/schedules?week={week}&date={date}&teacher_id={teacherId}&group_id={groupId}&building_id={buildingId}&building_classroom_id={buildingClassroomId}",
     *   tags={"Schedules Client"},
     *   summary="Получение списка расписаний",
     *   operationId="get_client_schedules",
     * 
     *   @OA\Parameter(
     *      name="week",
     *      in="path",
     *      required=false, 
     *      description="Required if not set date parameter. Values: [ current | next ].",
     *      @OA\Schema(
     *           type="string",
     *           default="current"
     *      )
     *   ),
     * 
     *   @OA\Parameter(
     *      name="date",
     *      in="path",
     *      required=false, 
     *      description="Required if not set week parameter. Format: Y-m-d",
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
     *           type="integer"
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

     /**
     * @OA\Get(
     * path="/api/v1/client/schedules_by_period?date_start={dateStart}&date_end={dateEnd}&teacher_id={teacherId}&group_id={groupId}&building_id={buildingId}&building_classroom_id={buildingClassroomId}",
     *   tags={"Schedules Client"},
     *   summary="Получение количества расписаний за период по дням",
     *   operationId="get_client_schedules_count_by_period",
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
     *           type="integer"
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
    protected function getSchedulesCountByPeriod(ScheduleGetByPeriodRequest $request)
    {
        $input = $request->validated();

        return $this->scheduleAction->getCountByPeriod($input);
    }

     /**
     * @OA\Get(
     * path="/api/v1/client/schedules_week_by_date?date={date}&teacher_id={teacherId}&group_id={groupId}&building_id={buildingId}&building_classroom_id={buildingClassroomId}",
     *   tags={"Schedules Client"},
     *   summary="Получение списка расписаний за месяц по выбранному дню",
     *   operationId="get_client_schedules_week_by_date",
     * 
     *   @OA\Parameter(
     *      name="date",
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
     *           type="integer"
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
    protected function getSchedulesWeekByDate(ScheduleGetRequest $request)
    {
        $input = $request->validated();

        return $this->scheduleAction->getWeekByDate($input);
    }
}