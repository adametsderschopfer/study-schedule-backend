<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Account;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Teacher;
use App\Http\Requests\TeacherFormRequest;

class TeacherController extends Controller
{
    private const TEACHERS_DEFAULT_LIMIT = 30;

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
     * path="/api/v1/admin/teachers?page={page}&parent_id={parentId}",
     *   tags={"Teachers"},
     *   summary="Получение списка преподавателей",
     *   operationId="get_teachers",
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
     *      name="parentId",
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
        $input = $request->only('parent_id');

        if (!isset($input['parent_id'])) {
            abort(404);
        }

        $parent = $this->teacherable::findOrFail($input['parent_id']);

        if (!$parent->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        $teachers = $parent->teachers()
                ->with('groups')
                ->paginate(self::TEACHERS_DEFAULT_LIMIT);

        return $this->sendPaginationResponse($teachers);
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/teachers/{teacherId}",
     *   tags={"Teachers"},
     *   summary="Получение одного преподавателя",
     *   operationId="show_teacher",
     *
     *   @OA\Parameter(
     *      name="teacherId",
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
    protected function show(Teacher $teacher)
    {
        return $teacher->with('grops');
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/teachers/{teacherId}",
     *   tags={"Teachers"},
     *   summary="Удаление преподавателя",
     *   operationId="delete_teacher",
     *
     *   @OA\Parameter(
     *      name="teacherId",
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
    protected function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/teachers",
     *      tags={"Teachers"},
     *      summary="Создание преподавателя",
     *      operationId="add_teacher",
     * 
     *      @OA\Parameter(
     *          name="parent_id",
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
    protected function store(TeacherFormRequest $request)
    {
        $input = $request->validated();

        $parent = $this->teacherable::findOrFail($input['parent_id']);
        $input['account_id'] = $this->accountService->getId();

        if (!$parent->hasAccount($input['account_id'])) {
            abort(404);
        }

        $teacher = new Teacher;
        $teacher->account_id = $input['account_id'];
        $teacher->full_name = $input['full_name'];
        $teacher->position = $input['position'] ?? null;
        $teacher->degree = $input['degree'] ?? null;

        $parent->teachers()->save($teacher);

        return $teacher->load('groups');
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/teachers/{teacherId}",
     *      tags={"Teachers"},
     *      summary="Обновление преподавателя",
     *      operationId="edit_teacher",
     * 
     *      @OA\Parameter(
     *          name="teacherId",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="parent_id",
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
    protected function update(Teacher $teacher, TeacherFormRequest $request)
    {
        $input = $request->validated();

        $parent = $this->teacherable::findOrFail($input['parent_id']);

        if (!$parent->hasAccount($this->accountService->getId())) {
            abort(404);
        }

        $teacher->account_id = $this->accountService->getId();
        $teacher->full_name = $input['full_name'];
        $teacher->position = $input['position'] ?? null;
        $teacher->degree = $input['degree'] ?? null;

        $teacher->faculties()->detach();
        $teacher->departments()->detach();

        if ($parent->teachers()->save($teacher)) {
            return $teacher->load('groups');
        }

        return $this->sendError(__('Server error'));
    }
}
