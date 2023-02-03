<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mode;
use Illuminate\Support\Facades\Validator;

class ModeController extends Controller
{
    protected function index()
    {
        $data = Mode::where('account_id', session('account_id'))->get();
        return $this->sendResponse($data);
    }

    protected function store(Request $request) 
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('Validation error'), $validator->errors(), 422);
        }

        $input['account_id'] = session('account_id');
        $mode = Mode::create($input);

        return $this->sendResponse($mode);
    }

    protected function update(Request $request, $id)
    {
        $input = $request->all();

        $mode = Mode::where('id', $id)
            ->where('account_id', session('account_id'))
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

    protected function show($id)
    {
        $mode = Mode::where('id', $id)
            ->where('account_id', session('account_id'))
            ->first();

        if (!$mode) {
            return $this->sendError(__('Not found'));
        }

        return $this->sendResponse($mode);
    }

    protected function destroy($id)
    {
        $mode = Mode::where('id', $id)
            ->where('account_id', session('account_id'))
            ->first();

        if (!$mode) {
            return $this->sendError(__('Not found'));
        }

        $mode->delete();

        return $this->sendResponse();
    }
}
