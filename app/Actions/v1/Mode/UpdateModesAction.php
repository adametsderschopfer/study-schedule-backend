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
     * path="/api/v1/admin/modes/{modeId}",
     *   tags={"Modes"},
     *   summary="Обновление режима звонков",
     *   operationId="edit_mode",
     *
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="modeId",
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
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('Validation error'), $validator->errors(), 422);
        }

        if ($mode->update($input)) {
            return $this->sendResponse($mode);
        }

        return $this->sendError(__('Server error'));
    }
}