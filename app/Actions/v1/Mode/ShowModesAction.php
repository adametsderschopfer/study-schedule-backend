<?php

namespace App\Actions\v1\Mode;

use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\Admin\ModeController;
use App\Models\Mode;

class ShowModesAction extends ModeController
{
    /**
     * @OA\Get(
     * path="/api/v1/admin/modes/{modeId}",
     *   tags={"Modes"},
     *   summary="Получение одного режима звонков",
     *   operationId="show_mode",
     *
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
    protected function execute(int $id)
    {
        $mode = Mode::where('id', $id)
            ->where('account_id', $this->accountService->getId())
            ->first();

        if (!$mode) {
            return $this->sendError(__('Not found'));
        }

        return $this->sendResponse($mode);
    }
}