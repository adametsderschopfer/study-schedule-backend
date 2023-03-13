<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Schedule;
use App\Http\Requests\ScheduleFormRequest;
use App\Http\Requests\ScheduleGetRequest;
use App\Http\Actions\v1\ScheduleAction;

class ScheduleController extends Controller
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
     * path="/api/v1/admin/schedules?week={week}&date={date}&teacher_id={teacherId}&department_group_id={departmentGroupId}",
     *   tags={"Schedules"},
     *   summary="Получение списка расписаний",
     *   operationId="get_schedules",
     * 
     *   @OA\Parameter(
     *      name="week",
     *      in="path",
     *      required=false, 
     *      description="Required if not set date parameter. Values: [ current | next ].",
     *      default="current", 
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

     /**
     * @OA\Get(
     * path="/api/v1/admin/schedules/{scheduleId}",
     *   tags={"Schedules"},
     *   summary="Получение одного расписания",
     *   operationId="show_schedule",
     *
     *   @OA\Parameter(
     *      name="scheduleId",
     *      in="path",
     *      required=true,
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
    protected function show(Schedule $schedule)
    {
        $schedule = $schedule->load('department')
            ->load('schedule_setting')
            ->load('subject')
            ->load('teacher');

        $schedule['schedule_setting_item'] = $schedule->schedule_setting_item();

        return $schedule;
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/schedules/{scheduleId}",
     *   tags={"Schedules"},
     *   summary="Удаление расписания",
     *   operationId="delete_schedule",
     *
     *   @OA\Parameter(
     *      name="scheduleId",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
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
    protected function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/schedules",
     *      tags={"Schedules"},
     *      summary="Создание расписания",
     *      operationId="add_schedule",
     * 
     *      @OA\Parameter(
     *          name="department_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="schedule_setting_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="subject_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="department_group_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="teacher_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="shedule_setting_item_order",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="shedule_setting_item_order",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="sub_group",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="day_of_week",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="repeatability",
     *          in="query",
     *          required=true,
     *          description="0 = ONCE, 1 = EVERY, 2 = EVEN, 3 = ODD"
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="repeat_start",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="repeat_end",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     * )
     * @param Request $request
     * @return bool
     */
    protected function store(ScheduleFormRequest $request)
    {
        $input = $request->validated();

        if (!Schedule::checkRelations($input, $this->accountService->getId())) {
            abort(404);
        }

        $input['account_id'] = $this->accountService->getId();

        $schedule = Schedule::create($input);

        $schedule['schedule_setting_item'] = $schedule->schedule_setting_item();

        return $schedule->load('department')
            ->load('schedule_setting')
            ->load('subject')
            ->load('teacher');
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/schedules/{scheduleId}",
     *      tags={"Schedules"},
     *      summary="Обновление расписания",
     *      operationId="edit_schedule",
     * 
     *      @OA\Parameter(
     *          name="scheduleId",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="department_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="schedule_setting_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="subject_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="department_group_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="teacher_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="shedule_setting_item_order",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="shedule_setting_item_order",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="sub_group",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="day_of_week",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="repeatability",
     *          in="query",
     *          required=true,
     *          description="0 = ONCE, 1 = EVERY, 2 = EVEN, 3 = ODD"
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="repeat_start",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="repeat_end",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     * )
     * @param Request $request
     * @return bool
     */
    protected function update(Schedule $schedule, ScheduleFormRequest $request)
    {
        $input = $request->validated();

        if ($schedule->update($input)) {
            return $schedule->load('department')
                ->load('schedule_setting')
                ->load('subject')
                ->load('teacher');
        }

        return $this->sendError(__('Server error'));
    }
}
