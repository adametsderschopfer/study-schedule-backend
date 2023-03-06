<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Schedule;
use App\Models\Department;
use App\Models\ScheduleSetting;
use App\Models\DepartmentSubject;
use App\Models\Teacher;
use App\Http\Requests\ScheduleFormRequest;

class ScheduleController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/schedules",
     *   tags={"Schedules"},
     *   summary="Получение списка расписаний",
     *   operationId="get_schedules",
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
        return Schedule::all();
    }

    protected function store(ScheduleFormRequest $request)
    {
        $input = $request->validated();

        $department = Department::findOrFail($input['department_id']);
        $scheduleSetting = ScheduleSetting::findOrFail($input['schedule_setting_id']);
        $departmentSubject = DepartmentSubject::findOrFail($input['department_subject_id']);
        $teacher = Teacher::findOrFail($input['teacher_id']);

        if (
            !$department->hasAccount($this->accountService->getId()) ||
            !$scheduleSetting->hasAccount($this->accountService->getId()) ||
            !$departmentSubject->hasAccount($this->accountService->getId()) ||
            !$teacher->hasAccount($this->accountService->getId())
        ) {
            abort(404);
        }

        $schedule = Schedule::create($input);

        return $schedule;
    }
}
