<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Actions\v1\Mode\GetModesAction;
use App\Actions\v1\Mode\StoreModesAction;
use App\Actions\v1\Mode\UpdateModesAction;
use App\Actions\v1\Mode\ShowModesAction;
use App\Actions\v1\Mode\DeleteModesAction;

class ModeController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

    public function index(GetModesAction $getModesAction)
    {
        return $getModesAction->execute();
    }

    public function store(Request $request, StoreModesAction $storeModesAction) 
    {
        return $storeModesAction->execute($request);
    }

    protected function update(Request $request, UpdateModesAction $updateModesAction, int $id)
    {
        return $updateModesAction->execute($request, $id);
    }

    protected function show(ShowModesAction $showModesAction, int $id)
    {
        return $showModesAction->execute($id);
    }

    protected function destroy(DeleteModesAction $deleteModesAction, int $id)
    {
        return $deleteModesAction->execute($id);
    }
}
