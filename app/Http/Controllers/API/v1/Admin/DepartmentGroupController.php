<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\DepartmentGroup;
use App\Models\Department;
use App\Http\Requests\DepartmentGroupFormRequest;

class DepartmentGroupController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/department_groups?department_id={departmentId}",
     *   tags={"Department Groups"},
     *   summary="Получение списка классов",
     *   operationId="get_department_groups",
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

        return $department->department_groups;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/department_groups/{departmentGroupId}",
     *   tags={"Department Groups"},
     *   summary="Получение одного класса",
     *   operationId="show_department_group",
     *
     *   @OA\Parameter(
     *      name="departmentGroupId",
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
    protected function show(DepartmentGroup $departmentGroup)
    {
        return $departmentGroup;
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/department_groups/{departmentGroupId}",
     *   tags={"Department Groups"},
     *   summary="Удаление класса",
     *   operationId="delete_department_group",
     *
     *   @OA\Parameter(
     *      name="departmentGroupId",
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
    protected function destroy(DepartmentGroup $departmentGroup)
    {
        $departmentGroup->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/department_groups",
     *      tags={"Department Groups"},
     *      summary="Создание класса",
     *      operationId="add_department_group",
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
     *          name="name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="sub_group",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="degree",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="year_of_education",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="form_of_education",
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
    protected function store(DepartmentGroupFormRequest $request)
    {
        $input = $request->validated();

        $department = Department::findOrFail($input['department_id']);

        if (!$department->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        $departmentGroup = DepartmentGroup::create($input);

        return $departmentGroup;
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/department_groups/{departmentGroupId}",
     *      tags={"Department Groups"},
     *      summary="Обновление класса",
     *      operationId="edit_department_group",
     * 
     *      @OA\Parameter(
     *          name="departmentGroupId",
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
     *          name="name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="sub_group",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="degree",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="year_of_education",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="form_of_education",
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
    protected function update(DepartmentGroup $departmentGroup, DepartmentGroupFormRequest $request)
    {
        $input = $request->validated();
        
        $department = Department::findOrFail($input['department_id']);

        if (!$department->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        if ($departmentGroup->update($input)) {
            return $departmentGroup;
        }

        return $this->sendError(__('Server error'));
    }
}
