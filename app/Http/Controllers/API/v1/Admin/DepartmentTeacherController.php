<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\DepartmentTeacher;
use App\Models\Department;
use App\Http\Requests\DepartmentTeacherFormRequest;

class DepartmentTeacherController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/department_teachers?department_id={departmentId}",
     *   tags={"Department Teachers"},
     *   summary="Получение списка преподавателей",
     *   operationId="get_department_teachers",
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
    protected function index(Request $request)
    {
        $input = $request->only('department_id');

        $department = Department::findOrFail($input['department_id']);

        if (!$department->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        return $department->department_teachers;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/department_teachers/{departmentTeacherId}",
     *   tags={"Department Teachers"},
     *   summary="Получение одного преподавателя",
     *   operationId="show_department_teacher",
     *
     *   @OA\Parameter(
     *      name="departmentTeacherId",
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
    protected function show(DepartmentTeacher $departmentTeacher)
    {
        return $departmentTeacher;
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/department_teachers/{departmentTeacherId}",
     *   tags={"Department Teachers"},
     *   summary="Удаление преподавателя",
     *   operationId="delete_department_teacher",
     *
     *   @OA\Parameter(
     *      name="departmentTeacherId",
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
    protected function destroy(DepartmentTeacher $departmentTeacher)
    {
        $departmentTeacher->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/department_teachers",
     *      tags={"Department Teachers"},
     *      summary="Создание преподавателя",
     *      operationId="add_department_teacher",
     * 
     *      @OA\Parameter(
     *          name="department_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="full_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="position",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="degree",
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
    protected function store(DepartmentTeacherFormRequest $request)
    {
        $input = $request->validated();

        $department = Department::findOrFail($input['department_id']);

        if (!$department->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        $departmentTeacher = DepartmentTeacher::create($input);

        return $departmentTeacher;
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/department_teachers/{departmentTeacherId}",
     *      tags={"Department Teachers"},
     *      summary="Обновление преподавателя",
     *      operationId="edit_department_teacher",
     * 
     *      @OA\Parameter(
     *          name="departmentTeacherId",
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
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="full_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="position",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="degree",
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
    protected function update(DepartmentTeacher $departmentTeacher, DepartmentTeacherFormRequest $request)
    {
        $input = $request->validated();
        
        $department = Department::findOrFail($input['department_id']);

        if (!$department->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        if ($departmentTeacher->update($input)) {
            return $departmentTeacher;
        }

        return $this->sendError(__('Server error'));
    }
}
