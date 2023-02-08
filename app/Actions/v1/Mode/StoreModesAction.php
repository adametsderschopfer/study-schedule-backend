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
     * path="/api/v1/admin/modes",
     *   tags={"Modes"},
     *   summary="Создание режима звонков",
     *   operationId="add_mode",
     * 
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
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
    public function execute(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('Validation error'), $validator->errors(), 422);
        }

        $input['account_id'] = $this->accountService->getId();

        $mode = Mode::create($input);

        return $this->sendResponse($mode);
    }
}