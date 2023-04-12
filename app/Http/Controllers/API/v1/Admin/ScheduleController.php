<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Schedule;
use App\Http\Requests\ScheduleFormRequest;
use App\Http\Requests\ScheduleGetRequest;
use App\Http\Actions\v1\ScheduleAction;
use App\Exports\SchedulesExport;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{
    private const EXPORT_FILENAME_PREFIX = 'schedules_';

    private const EXPORT_FILENAME_FORMAT = 'xls';

    public function __construct(
        AccountService $accountService, 
        ScheduleAction $scheduleAction
    ) {
        $this->accountService = $accountService;
        $this->scheduleAction = $scheduleAction;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/schedules?page={page}&date_start={dateStart}&date_end={dateEnd}&teacher_id={teacherId}&group_id={groupId}&building_id={buildingId}&building_classroom_id={buildingClassroomId}",
     *   tags={"Schedules"},
     *   summary="Получение списка расписаний",
     *   operationId="get_schedules",
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

        $schedulesData = $this->scheduleAction->get($input);

        return $this->sendPaginationResponse($schedulesData['data'], $schedulesData['includes']);
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
            ->load('group')
            ->load('teacher')
            ->load('building')
            ->load('building_classroom');

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
     *          name="group_id",
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
     *          name="building_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="building_classroom_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="schedule_setting_item_order",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="schedule_setting_item_order",
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
     *          description="0 = ONCE, 1 = EVERY, 2 = EVEN, 3 = ODD",
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
            ->load('group')
            ->load('teacher')
            ->load('building')
            ->load('building_classroom');
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
     *          name="group_id",
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
     *          name="building_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="building_classroom_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="schedule_setting_item_order",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="schedule_setting_item_order",
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
     *          description="0 = ONCE, 1 = EVERY, 2 = EVEN, 3 = ODD",
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
                ->load('group')
                ->load('teacher')
                ->load('building')
                ->load('building_classroom');
        }

        return $this->sendError(__('Server error'));
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/schedules_export?date_start={dateStart}&date_end={dateEnd}&teacher_id={teacherId}&group_id={groupId}&building_id={buildingId}&building_classroom_id={buildingClassroomId}",
     *   tags={"Schedules"},
     *   summary="Экспорт списка расписаний в CSV",
     *   operationId="export_schedules",
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
    public function export(ScheduleGetRequest $request) 
    {
        $filename = self::EXPORT_FILENAME_PREFIX . time() . '.' . self::EXPORT_FILENAME_FORMAT;

        $input = $request->validated();

        $schedulesData = [];
        $schedulesRepeatabilities = [];
        $input['page'] = 1;
        $schedulesQuery = $this->scheduleAction->getForExport($input);

        while (!empty($schedulesQuery['data'])) {
            array_push($schedulesData, $schedulesQuery['data']);
            array_push($schedulesRepeatabilities, $schedulesQuery['includes']['repeatabilities']);
            $input['page'] ++;
            $schedulesQuery = $this->scheduleAction->getForExport($input);
        }

        $schedulesData = collect($schedulesData)->collapse();
        $schedulesRepeatabilities = collect($schedulesRepeatabilities)->collapse();

        return Excel::download(new SchedulesExport($schedulesData, $schedulesRepeatabilities, $this->accountService), $filename, \Maatwebsite\Excel\Excel::XLS);
    }
}
