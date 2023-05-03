<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Account;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Group;
use App\Models\Teacher;
use App\Http\Requests\GroupFormRequest;

class GroupController extends Controller
{
    private const GROUPS_DEFAULT_LIMIT = 30;

    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;

        switch ($this->accountService->getType()) {
            case Account::TYPES['UNIVERSITY'] :
                $this->groupable = Department::class;
                break;
            case Account::TYPES['COLLEGE'] :
                $this->groupable = Faculty::class;
                break;
            default:
                $this->groupable = false;
        }
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/groups?page={page}&parent_id={parentId}",
     *   tags={"Groups"},
     *   summary="Получение списка групп",
     *   operationId="get_groups",
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

        if (isset($input['parent_id'])) {
            $parent = $this->groupable::findOrFail($input['parent_id']);
            if (!$parent->hasAccount($this->accountService->getId())) {
                abort(404);
            }
        } else {
            $parent = Account::findOrFail($this->accountService->getId());
        }

        $groups = $parent->groups()
                ->with('teacher')
                ->paginate(self::GROUPS_DEFAULT_LIMIT);

        return $this->sendPaginationResponse($groups);
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/groups/{groupId}",
     *   tags={"Groups"},
     *   summary="Получение одной группы",
     *   operationId="show_group",
     *
     *   @OA\Parameter(
     *      name="groupId",
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
    protected function show(Group $group)
    {
        return $group->load('teacher');
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/groups/{groupId}",
     *   tags={"Groups"},
     *   summary="Удаление группы",
     *   operationId="delete_group",
     *
     *   @OA\Parameter(
     *      name="groupId",
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
    protected function destroy(group $group)
    {
        $group->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/groups",
     *      tags={"Groups"},
     *      summary="Создание группы",
     *      operationId="add_group",
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
     *          name="teacher_id",
     *          in="query",
     *          required=false,
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
     *          name="letter",
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
    protected function store(GroupFormRequest $request)
    {
        $input = $request->validated();

        if (isset($input['parent_id']) && $this->groupable !== false) {
            $parent = $this->groupable::findOrFail($input['parent_id']);
            if (!$parent->hasAccount($this->accountService->getId())) {
                abort(404);
            }
        } else {
            $parent = Account::findOrFail($this->accountService->getId());
        }

        if (isset($input['teacher_id'])) {
            $teacher = Teacher::findOrFail($input['teacher_id']);
            if (!$teacher->hasAccount($this->accountService->getId()) || $this->groupable !== false) {
                unset($input['teacher_id']);
            }
        }

        $group = new Group;
        $group->name = $input['name'];
        $group->letter = $input['letter'] ?? null;
        $group->sub_group = $input['sub_group'] ?? 0;
        $group->degree = $input['degree'] ?? 0;
        $group->year_of_education = $input['year_of_education'] ?? 0;
        $group->form_of_education = $input['form_of_education'] ?? 0;
        $group->teacher_id = $input['teacher_id'] ?? null;
        $group->account_id = $this->accountService->getId();

        $parent->groups()->save($group);

        return $group->load('teacher');
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/groups/{groupId}",
     *      tags={"Groups"},
     *      summary="Обновление группы",
     *      operationId="edit_group",
     * 
     *      @OA\Parameter(
     *          name="groupId",
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
     *          name="teacher_id",
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
     *          name="letter",
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
    protected function update(Group $group, GroupFormRequest $request)
    {
        $input = $request->validated();

        if (isset($input['parent_id']) && $this->groupable !== false) {
            $parent = $this->groupable::findOrFail($input['parent_id']);
            if (!$parent->hasAccount($this->accountService->getId())) {
                abort(404);
            }
        } else {
            $parent = Account::findOrFail($this->accountService->getId());
        }

        if (isset($input['teacher_id'])) {
            $teacher = Teacher::findOrFail($input['teacher_id']);
            if (!$teacher->hasAccount($this->accountService->getId())) {
                unset($input['teacher_id']);
            }
        }

        $group->name = $input['name'];
        $group->letter = $input['letter'] ?? null;
        $group->sub_group = $input['sub_group'] ?? 0;
        $group->degree = $input['degree'] ?? 0;
        $group->year_of_education = $input['year_of_education'] ?? 0;
        $group->form_of_education = $input['form_of_education'] ?? 0;
        $group->teacher_id = $input['teacher_id'] ?? null;

        $group->faculties()->detach();
        $group->departments()->detach();

        if ($parent->groups()->save($group)) {
            return $group->load('teacher');
        }

        return $this->sendError(__('Server error'));
    }
}
