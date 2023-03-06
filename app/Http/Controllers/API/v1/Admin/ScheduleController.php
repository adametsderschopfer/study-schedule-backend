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
        return Schedule::where('account_id', $this->accountService->getId())
                ->get();
    }

    protected function store(ScheduleFormRequest $request)
    {
        $input = $request->validated();

        if (!Schedule::checkRelations($input, $this->accountService->getId())) {
            abort(404);
        }

        $input['account_id'] = $this->accountService->getId();

        $schedule = Schedule::create($input);

        return $schedule;
    }

    public function getByDay()
    {
        $date = date('Y-m-d');
        
        $schedules = Schedule::where('account_id', $this->accountService->getId())
                ->where('repeat_start', '<=', $date)
                ->where('repeat_end', '>=', $date)
                ->get();

        return $schedules;
    }
}
