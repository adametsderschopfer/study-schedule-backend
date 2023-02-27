<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\DepartmentSubject;
use App\Models\Department;
use App\Http\Requests\DepartmentSubjectFormRequest;

class DepartmentSubjectController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/department_subjects?department_id={departmentId}",
     *   tags={"Department Subjects"},
     *   summary="Получение списка предметов",
     *   operationId="get_department_subjects",
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

        return $department->department_subjects;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/department_subjects/{departmentSubjectId}",
     *   tags={"Department Subjects"},
     *   summary="Получение одного предмета",
     *   operationId="show_department_subject",
     *
     *   @OA\Parameter(
     *      name="departmentSubjectId",
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
    protected function show(DepartmentSubject $departmentSubject)
    {
        return $departmentSubject;
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/department_subjects/{departmentSubjectId}",
     *   tags={"Department Subjects"},
     *   summary="Удаление предмета",
     *   operationId="delete_department_subject",
     *
     *   @OA\Parameter(
     *      name="departmentSubjectId",
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
    protected function destroy(DepartmentSubject $departmentSubject)
    {
        $departmentSubject->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/department_subjects",
     *      tags={"Department Subjects"},
     *      summary="Создание предмета",
     *      operationId="add_department_subject",
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
     *          name="department_id",
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
    protected function store(DepartmentSubjectFormRequest $request)
    {
        $input = $request->validated();

        $department = Department::findOrFail($input['department_id']);

        if (!$department->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        $departmentSubject = DepartmentSubject::create($input);

        return $departmentSubject;
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/department_subjects/{departmentSubjectId}",
     *      tags={"Department Subjects"},
     *      summary="Обновление предмета",
     *      operationId="edit_department_subject",
     * 
     *      @OA\Parameter(
     *          name="departmentSubjectId",
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
    protected function update(DepartmentSubject $departmentSubject, DepartmentSubjectFormRequest $request)
    {
        $input = $request->validated();
        
        $department = Department::findOrFail($input['department_id']);

        if (!$department->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        if ($departmentSubject->update($input)) {
            return $departmentSubject;
        }

        return $this->sendError(__('Server error'));
    }
}
