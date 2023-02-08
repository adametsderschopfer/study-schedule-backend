<?php

namespace App\Actions\v1\Mode;

use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\Admin\ModeController;
use App\Models\Mode;
use Illuminate\Support\Facades\Validator;

class UpdateModesAction extends ModeController
{
    /**
     * @OA\Put(
     *      path="/api/v1/admin/modes/{modeId}",
     *      tags={"Modes"},
     *      summary="Обновление режима звонков",
     *      operationId="edit_mode",
     *      @OA\Parameter(
     *          name="modeId",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="timings", type="array",
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
    public function execute(Request $request, int $id)
    {
        $input = $request->all();

        $mode = Mode::where('id', $id)
            ->where('account_id', $this->accountService->getId())
            ->first();

        if (!$mode) {
            return $this->sendError(__('Not found'));
        }

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'timings' => ['sometimes', 'array'], 
            'timings.*.timeStart' => ['sometimes', 'date_format:H:i'],
            'timings.*.timeEnd' => ['sometimes', 'date_format:H:i', 'after:timings.*.timeStart'],
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('Validation error'), $validator->errors(), 422);
        }

        if ($mode->update($input)) {

            $mode->deleteTimings();

            if (isset($input['timings'])) {
                $mode->giveTimings($input['timings']);
            }

            $mode->load([
                'timings' => function ($q) {
                    $q->orderBy('offset', 'ASC');
                }
            ]);

            return $this->sendResponse($mode);
        }

        return $this->sendError(__('Server error'));
    }
}