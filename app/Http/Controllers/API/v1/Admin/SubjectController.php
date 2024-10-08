<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Account;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Subject;
use App\Http\Requests\SubjectFormRequest;
use App\Services\SearchService;
use App\Http\Requests\SearchRequest;

class SubjectController extends Controller
{
    private const SUBJECTS_DEFAULT_LIMIT = 30;

    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
        $this->searchService = new SearchService(new Subject());

        switch ($this->accountService->getType()) {
            case Account::TYPES['UNIVERSITY'] :
                $this->subjectable = Department::class;
                break;
            case Account::TYPES['COLLEGE'] :
                $this->subjectable = Faculty::class;
                break;
            default:
                $this->subjectable = false;
        }
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/subjects?page={page}&parent_id={parentId}",
     *   tags={"Subjects"},
     *   summary="Получение списка предметов",
     *   operationId="get_subjects",
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

        if (isset($input['parent_id']) && $this->subjectable !== false) {
            $parent = $this->subjectable::findOrFail($input['parent_id']);
            if (!$parent->hasAccount($this->accountService->getId())) {
                abort(404);
            }
        } else {
            $parent = Account::findOrFail($this->accountService->getId());
        }

        $subjects = $parent->subjects()
                ->paginate(self::SUBJECTS_DEFAULT_LIMIT);

        return $this->sendPaginationResponse($subjects);
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/subjects/{subjectId}",
     *   tags={"Subjects"},
     *   summary="Получение одного предмета",
     *   operationId="show_subject",
     *
     *   @OA\Parameter(
     *      name="subjectId",
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
    protected function show(Subject $subject)
    {
        return $subject;
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/subjects/{subjectId}",
     *   tags={"Subjects"},
     *   summary="Удаление предмета",
     *   operationId="delete_subject",
     *
     *   @OA\Parameter(
     *      name="subjectId",
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
    protected function destroy(Subject $subject)
    {
        $subject->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/subjects",
     *      tags={"Subjects"},
     *      summary="Создание предмета",
     *      operationId="add_subject",
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
     *          name="name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *      @OA\Parameter(
     *          name="set_for_all",
     *          in="query",
     *          required=false,
     *          description="Only for Account type - College",
     *          @OA\Schema(
     *              type="boolean"
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
    protected function store(SubjectFormRequest $request)
    {
        $input = $request->validated();

        if ($this->accountService->getType() == Account::TYPES['COLLEGE'] && isset($input['set_for_all']) && $input['set_for_all'] == true) {
            $parents = $this->subjectable::where('account_id', $this->accountService->getId())->get();
        } else {
            if (isset($input['parent_id']) && $this->subjectable !== false) {
                $parent = $this->subjectable::findOrFail($input['parent_id']);
                if (!$parent->hasAccount($this->accountService->getId())) {
                    abort(404);
                }
            } else {
                $parent = Account::findOrFail($this->accountService->getId());
            }
            $parents[] = $parent;
        }

        $subject = new Subject;
        $subject->name = $input['name'];
        $subject->account_id = $this->accountService->getId();

        foreach ($parents as $parent) {
            $parent->subjects()->save($subject);
        }

        return $subject;
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/subjects/{subjectId}",
     *      tags={"Subjects"},
     *      summary="Обновление предмета",
     *      operationId="edit_subject",
     * 
     *      @OA\Parameter(
     *          name="subjectId",
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
    protected function update(Subject $subject, SubjectFormRequest $request)
    {
        $input = $request->validated();

        if (isset($input['parent_id']) && $this->subjectable !== false) {
            $parent = $this->subjectable::findOrFail($input['parent_id']);
            if (!$parent->hasAccount($this->accountService->getId())) {
                abort(404);
            }
        } else {
            $parent = Account::findOrFail($this->accountService->getId());
        }

        $subject->name = $input['name'];

        $subject->faculties()->detach();
        $subject->departments()->detach();

        if ($parent->subjects()->save($subject)) {
            return $subject;
        }

        return $this->sendError(__('Server error'));
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/subject/search?search={search}",
     *   tags={"Subjects"},
     *   summary="Поиск по предметам",
     *   operationId="search_subjects",
     * 
     *   @OA\Parameter(
     *      name="search",
     *      in="path",
     *      required=true,
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
    public function search(SearchRequest $request)
    {
        $input = $request->validated();
        $input['account_id'] = $this->accountService->getId();
        
        $subjects = $this->searchService->search($input);

        return $this->sendResponse($subjects);
    }
}
