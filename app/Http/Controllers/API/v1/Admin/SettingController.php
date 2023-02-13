<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Setting;
use App\Http\Requests\SettingFormRequest;

class SettingController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/settings",
     *   tags={"Settings"},
     *   summary="Получение списка режимов звонков",
     *   operationId="get_settings",
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
        return Setting::where('account_id', $this->accountService->getId())
                ->with('setting_items')
                ->get();
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/settings/{settingId}",
     *   tags={"Settings"},
     *   summary="Получение одного режима звонков",
     *   operationId="show_setting",
     *
     *   @OA\Parameter(
     *      name="settingId",
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
    protected function show(Setting $setting)
    {
        return $setting->load('setting_items');
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/settings/{settingId}",
     *   tags={"Settings"},
     *   summary="Удаление режима звонков",
     *   operationId="delete_setting",
     *
     *   @OA\Parameter(
     *      name="settingId",
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
    protected function destroy(Setting $setting)
    {
        $setting->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/settings",
     *      tags={"Settings"},
     *      summary="Создание режима звонков",
     *      operationId="add_setting",
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
     *          name="count",
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
    protected function store(SettingFormRequest $request)
    {
        $input = $request->validated();

        $input['account_id'] = $this->accountService->getId();

        $setting = Setting::create($input);

        $settingItems = [];

        if (isset($input['count']) && $input['count'] > 0) {
            $settingItems = array_fill(0, $input['count'], []);
        }

        $setting->giveSettingItems($settingItems);

        $setting->load([
            'setting_items' => function ($q) {
                $q->orderBy('offset', 'ASC');
            }
        ]);
        
        return $setting;
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/settings/{settingId}",
     *      tags={"Settings"},
     *      summary="Обновление режима звонков",
     *      operationId="edit_setting",
     * 
     *      @OA\Parameter(
     *          name="settingId",
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
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="setting_items", type="array",
     *                  @OA\Items(type="object", properties = {
     *                      @OA\Property(property="time_start", type="string"),
     *                      @OA\Property(property="time_end", type="string"),
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
    protected function update(Setting $setting, SettingFormRequest $request)
    {
        $input = $request->validated();

        if ($setting->update($input)) {

            if (isset($input['setting_items'])) {
                $setting->deleteSettingItems();
                $setting->giveSettingItems($input['setting_items']);
            }

            $setting->load([
                'setting_items' => function ($q) {
                    $q->orderBy('offset', 'ASC');
                }
            ]);
    
            return $setting;
        }

        return $this->sendError(__('Server error'));
    }
}
