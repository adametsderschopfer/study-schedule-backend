<?php

namespace App\Actions\v1\Mode;

use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\Admin\ModeController;
use App\Models\Mode;

class DeleteModesAction extends ModeController
{
    /**
     * @OA\Delete(
     * path="/api/v1/admin/modes/{modeId}",
     *   tags={"Modes"},
     *   summary="Удаление режима звонков",
     *   operationId="delete_mode",
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

        $mode->delete();

        return $this->sendResponse();
    }
}