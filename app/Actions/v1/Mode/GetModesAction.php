<?php

namespace App\Actions\v1\Mode;

use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\Admin\ModeController;
use App\Models\Mode;

class GetModesAction extends ModeController
{
     /**
     * @OA\Get(
     * path="/api/v1/admin/modes",
     *   tags={"Modes"},
     *   summary="Получение списка режимов звонков",
     *   operationId="get_modes",
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
    public function execute()
    {
        $data = Mode::where('account_id', $this->accountService->getId())
            ->with([
                'timings' => function ($q) {
                    $q->orderBy('offset', 'ASC');
                }
            ])
            ->get();

        return $this->sendResponse($data);
    }
}