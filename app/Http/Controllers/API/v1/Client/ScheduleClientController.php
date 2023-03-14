<?php

namespace App\Http\Controllers\API\v1\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Schedule;
use App\Http\Requests\ScheduleFormRequest;
use App\Http\Requests\ScheduleGetRequest;
use App\Http\Actions\v1\ScheduleAction;

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
     * path="/api/v1/client/schedules?week={week}&date={date}&teacher_id={teacherId}&department_group_id={departmentGroupId}",
     *   tags={"Schedules Client"},
     *   summary="Получение списка расписаний",
     *   operationId="get_client_schedules",
     * 
     *   @OA\Parameter(
     *      name="week",
     *      in="path",
     *      required=false, 
     *      description="Required if not set date parameter. Values: [ current | next ].",
     *      default="current, 
     *      @OA\Schema(
     *           type="string"
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
     *      name="departmentGroupId",
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
}