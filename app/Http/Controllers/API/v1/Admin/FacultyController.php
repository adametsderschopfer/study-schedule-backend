<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Faculty;
use App\Http\Requests\FacultyFormRequest;

class FacultyController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/faculties",
     *   tags={"Faculties"},
     *   summary="Получение списка факультетов",
     *   operationId="get_faculties",
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
        return Faculty::where('account_id', $this->accountService->getId())
                ->get();
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/faculties/{facultyId}",
     *   tags={"Faculties"},
     *   summary="Получение одного факультета",
     *   operationId="show_faculty",
     *
     *   @OA\Parameter(
     *      name="facultyId",
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
    protected function show(Faculty $faculty)
    {
        return $faculty;
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/faculties/{facultyId}",
     *   tags={"Faculties"},
     *   summary="Удаление факультета",
     *   operationId="delete_faculty",
     *
     *   @OA\Parameter(
     *      name="facultyId",
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
    protected function destroy(Faculty $faculty)
    {
        $faculty->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/faculties",
     *      tags={"Faculties"},
     *      summary="Создание факультета",
     *      operationId="add_faculty",
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
    protected function store(FacultyFormRequest $request)
    {
        $input = $request->validated();

        $input['account_id'] = $this->accountService->getId();

        $faculty = Faculty::create($input);
        
        return $faculty;
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/faculties/{facultyId}",
     *      tags={"Faculties"},
     *      summary="Обновление факультета",
     *      operationId="edit_faculty",
     * 
     *      @OA\Parameter(
     *          name="facultyId",
     *          in="path",
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
    protected function update(Faculty $faculty, FacultyFormRequest $request)
    {
        $input = $request->validated();

        if ($faculty->update($input)) {
            return $faculty;
        }

        return $this->sendError(__('Server error'));
    }
}