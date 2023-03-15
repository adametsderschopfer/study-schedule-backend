<?php

namespace App\Http\Controllers\API\v1\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Schedule;
use App\Http\Requests\ScheduleFormRequest;
use App\Http\Requests\ScheduleGetRequest;
use App\Http\Requests\ScheduleGetByYearRequest;
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
     * path="/api/v1/client/schedules_by_year/{year}?teacher_id={teacherId}&group_id={groupId}&building_id={buildingId}&building_classroom_id={buildingClassroomId}",
     *   tags={"Schedules Client"},
     *   summary="Получение списка расписаний за год",
     *   operationId="get_client_schedules_by_year",
     * 
     *   @OA\Parameter(
     *      name="year",
     *      in="path",
     *      required=true, 
     *      @OA\Schema(
     *           type="string",
     *           default="current"
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
    protected function getSchedulesCountByYear(string $year, ScheduleGetByYearRequest $request, string $format='Y')
    {
        $input = $request->validated();

        $y = DateTime::createFromFormat($format, $year);
        if (!$y || $y->format($format) !== $year) {
            abort(404);
        }
        return $this->scheduleAction->getCountByYear($year, $input);
    }
}