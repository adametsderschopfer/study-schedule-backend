<?php

namespace App\Actions\v1\Mode;

use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\Admin\ModeController;
use App\Models\Mode;
use Illuminate\Support\Facades\Validator;

class StoreModesAction extends ModeController
{
    /**
     * @OA\Post(
     *      path="/api/v1/admin/modes",
     *      tags={"Modes"},
     *      summary="Создание режима звонков",
     *      operationId="add_mode",
     * 
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
    public function execute(Request $request)
    {
        $input = $request->all();
        
        if (isset($input['timings'])) {
            $input['timings'] = json_decode(json_encode($input['timings']), true);
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

        $input['account_id'] = $this->accountService->getId();

        $mode = Mode::create($input);

        if (isset($input['timings'])) {
            $mode->giveTimings($input['timings']);
            
            $mode->load([
                'timings' => function ($q) {
                    $q->orderBy('offset', 'ASC');
                }
            ]);
        }
        
        return $this->sendResponse($mode);
    }
}