<?php

namespace App\Http\Controllers\API\v1\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Account;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Teacher;

class TeacherClientController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;

        switch ($this->accountService->getType()) {
            case Account::TYPES['UNIVERSITY'] :
                $this->teacherable = Department::class;
                break;
            case Account::TYPES['COLLEGE'] :
                $this->teacherable = Faculty::class;
                break;
            case Account::TYPES['SCHOOL'] :
                $this->teacherable = Account::class;
                break;
            default:
            $this->teacherable = false;
        }
    }

     /**
     * @OA\Get(
     * path="/api/v1/client/teachers",
     *   tags={"Teachers"},
     *   summary="Получение списка преподавателей",
     *   operationId="get_client_teachers",
     * 
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
        $teachers = Teacher::where('account_id', $this->accountService->getId())
            ->get();

        foreach ($teachers as $teacher) {
            $teacher['groups'] = $teacher->getGroups();
            unset($teacher['schedules']);
        }

        return $teachers;
    }
}
