<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Building;
use App\Http\Requests\BuildingFormRequest;

class BuildingController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/buildings",
     *   tags={"Buildings"},
     *   summary="Получение списка корпусов",
     *   operationId="get_buildings",
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
        return Building::where('account_id', $this->accountService->getId())
                ->with('building_classrooms')
                ->get();
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/buildings/{buildingId}",
     *   tags={"Buildings"},
     *   summary="Получение одного корпуса",
     *   operationId="show_building",
     *
     *   @OA\Parameter(
     *      name="buildingId",
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
    protected function show(building $building)
    {
        return $building->load('building_classrooms');
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/buildings/{buildingId}",
     *   tags={"Buildings"},
     *   summary="Удаление корпуса",
     *   operationId="delete_building",
     *
     *   @OA\Parameter(
     *      name="buildingId",
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
    protected function destroy(building $building)
    {
        $building->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/buildings",
     *      tags={"Buildings"},
     *      summary="Создание корпуса",
     *      operationId="add_building",
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
     *          name="address",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="building_classrooms", type="array",
     *                  @OA\Items(type="object", properties = {
     *                      @OA\Property(property="id", type="string"),
     *                      @OA\Property(property="name", type="string"),
     *                  }),
     *              )
     *          ),
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
    protected function store(BuildingFormRequest $request)
    {
        $input = $request->validated();

        $input['account_id'] = $this->accountService->getId();

        $building = Building::create($input);

        $building->giveBuildingsClassrooms($input['building_classrooms']);
        
        return $building->load('building_classrooms');
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/buildings/{buildingId}",
     *      tags={"Buildings"},
     *      summary="Обновление корпуса",
     *      operationId="edit_building",
     * 
     *      @OA\Parameter(
     *          name="buildingId",
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
     *      @OA\Parameter(
     *          name="address",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="building_classrooms", type="array",
     *                  @OA\Items(type="object", properties = {
     *                      @OA\Property(property="id", type="string"),
     *                      @OA\Property(property="name", type="string"),
     *                  }),
     *              )
     *          ),
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
    protected function update(building $building, BuildingFormRequest $request)
    {
        $input = $request->validated();

        $building->updateBuildingsClassrooms($input['building_classrooms']);

        if ($building->update($input)) {
            return $building->load('building_classrooms');
        }

        return $this->sendError(__('Server error'));
    }
}
