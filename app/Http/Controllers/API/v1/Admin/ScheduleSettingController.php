<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\ScheduleSetting;
use App\Http\Requests\ScheduleSettingFormRequest;

class ScheduleSettingController extends Controller
{
    private const SCHEDULE_SETTINGS_DEFAULT_LIMIT = 30;

    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/schedule_settings?page={page}",
     *   tags={"Schedule settings"},
     *   summary="Получение списка режимов звонков",
     *   operationId="get_schedule_settings",
     * 
     *   @OA\Parameter(
     *      name="page",
     *      in="path",
     *      required=false, 
     *      default=1,
     *      @OA\Schema(
     *           type="int"
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
    protected function index()
    {
        $scheduleSettings = ScheduleSetting::where('account_id', $this->accountService->getId())
                ->with('schedule_setting_items')
                ->paginate(self::SCHEDULE_SETTINGS_DEFAULT_LIMIT);

        return $this->sendPaginationResponse($scheduleSettings);
    }

     /**
     * @OA\Get(
     * path="/api/v1/admin/schedule_settings/{scheduleSettingId}",
     *   tags={"Schedule settings"},
     *   summary="Получение одного режима звонков",
     *   operationId="show_schedule_setting",
     *
     *   @OA\Parameter(
     *      name="scheduleSettingId",
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
    protected function show(ScheduleSetting $scheduleSetting)
    {
        return $scheduleSetting->load('schedule_setting_items');
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/schedule_settings/{scheduleSettingId}",
     *   tags={"Schedule settings"},
     *   summary="Удаление режима звонков",
     *   operationId="delete_schedule_setting",
     *
     *   @OA\Parameter(
     *      name="scheduleSettingId",
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
    protected function destroy(ScheduleSetting $scheduleSetting)
    {
        $scheduleSetting->delete();

        return $this->sendResponse();
    }

     /**
     * @OA\Post(
     *      path="/api/v1/admin/schedule_settings",
     *      tags={"Schedule settings"},
     *      summary="Создание режима звонков",
     *      operationId="add_schedule_setting",
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
    protected function store(ScheduleSettingFormRequest $request)
    {
        $input = $request->validated();

        $input['account_id'] = $this->accountService->getId();

        $scheduleSetting = ScheduleSetting::create($input);

        $scheduleSettingItems = [];

        if (isset($input['count']) && $input['count'] > 0) {
            $scheduleSettingItems = array_fill(0, $input['count'], []);
        }

        $scheduleSetting->giveScheduleSettingItems($scheduleSettingItems);

        $scheduleSetting->load([
            'schedule_setting_items' => function ($q) {
                $q->orderBy('order', 'ASC');
            }
        ]);
        
        return $scheduleSetting;
    }

     /**
     * @OA\Put(
     *      path="/api/v1/admin/schedule_settings/{scheduleSettingId}",
     *      tags={"Schedule settings"},
     *      summary="Обновление режима звонков",
     *      operationId="edit_schedule_setting",
     * 
     *      @OA\Parameter(
     *          name="scheduleSettingId",
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
     *              @OA\Property(property="schedule_setting_items", type="array",
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
    protected function update(ScheduleSetting $scheduleSetting, ScheduleSettingFormRequest $request)
    {
        $input = $request->validated();

        if ($scheduleSetting->update($input)) {

            if (isset($input['schedule_setting_items'])) {
                $scheduleSetting->deleteScheduleSettingItems();
                $scheduleSetting->giveScheduleSettingItems($input['schedule_setting_items']);
            }

            $scheduleSetting->load([
                'schedule_setting_items' => function ($q) {
                    $q->orderBy('order', 'ASC');
                }
            ]);
    
            return $scheduleSetting;
        }

        return $this->sendError(__('Server error'));
    }
}
