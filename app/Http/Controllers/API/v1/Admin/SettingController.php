<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Models\Setting;

class SettingController extends Controller
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

    protected function index()
    {
        return Setting::where('account_id', $this->accountService->getId())
                ->with('settingItems')
                ->get();
    }

    protected function show(Setting $setting)
    {
        return $setting;
    }

    protected function destroy(Setting $setting)
    {
        return $setting->delete();
    }

    protected function store()
    {
        dd($this->accountService);
        // return $showModesAction->execute($id);
    }

    protected function update(Setting $setting)
    {
        dd($setting);
        // return $showModesAction->execute($id);
    }


}
