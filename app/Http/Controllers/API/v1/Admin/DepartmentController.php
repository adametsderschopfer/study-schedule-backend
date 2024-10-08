<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Faculty;
use App\Models\Department;
use App\Http\Requests\DepartmentFormRequest;

class DepartmentController extends Controller
{
    private const DEPARTMENTS_DEFAULT_LIMIT = 30;

    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/departments?page={page}&faculty_id={facultyId}",
     *   tags={"Departments"},
     *   summary="Получение списка кафедр",
     *   operationId="get_departments",
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
     *      name="facultyId",
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
    protected function index(Request $request)
    {
        $input = $request->all();

        if (isset($input['faculty_id'])) {
            $faculty = Faculty::findOrFail($input['faculty_id']);
            if (!$faculty->hasAccount($this->accountService->getId())) {
                abort(404);
            }
            $faculties[] = $faculty->id;
        } else {
            $faculties = Faculty::select('id')->where('account_id', $this->accountService->getId());
            $faculties = $faculties->pluck('id')->toArray();
        }
        
        $departments = Department::whereIn('faculty_id', $faculties)
                ->with('subjects')
                ->with('groups')
                ->with('teachers')
                ->paginate(self::DEPARTMENTS_DEFAULT_LIMIT);

        return $this->sendPaginationResponse($departments);
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/departments/{departmentId}",
     *   tags={"Departments"},
     *   summary="Получение одной кафедры",
     *   operationId="show_department",
     *
     *   @OA\Parameter(
     *      name="departmentId",
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
    protected function show(Department $department)
    {
        return $department->load('subjects')
                ->load('groups')
                ->load('teachers')
                ->load('subjects');
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/departments/{departmentId}",
     *   tags={"Departments"},
     *   summary="Удаление кафедры",
     *   operationId="delete_department",
     *
     *   @OA\Parameter(
     *      name="departmentId",
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
    protected function destroy(Department $department)
    {
        $department->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/departments",
     *      tags={"Departments"},
     *      summary="Создание кафедры",
     *      operationId="add_department",
     * 
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="faculty_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
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
    protected function store(DepartmentFormRequest $request)
    {
        $input = $request->validated();

        $input['account_id'] = $this->accountService->getId();
        
        $faculty = Faculty::findOrFail($input['faculty_id']);

        if (!$faculty->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        $department = Department::create($input);

        return $department;
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/departments/{departmentId}",
     *      tags={"Departments"},
     *      summary="Обновление кафедры",
     *      operationId="edit_department",
     * 
     *      @OA\Parameter(
     *          name="departmentId",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="faculty_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          required=true,
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
    protected function update(Department $department, DepartmentFormRequest $request)
    {
        $input = $request->validated();

        $input['account_id'] = $this->accountService->getId();
        
        $faculty = Faculty::findOrFail($input['faculty_id']);

        if (!$faculty->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        if ($department->update($input)) {
            return $department->load('subjects')
                    ->load('groups')
                    ->load('teachers');
        }

        return $this->sendError(__('Server error'));
    }
}
