<?php

namespace App\Http\Controllers;

use App\Actions\Account as AccountAction;
use App\Http\Requests\Account\StoreRequest;
use App\Http\Requests\Account\UpdateRequest;
use App\Models\Account as AccountModel;

class Account extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Account\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, AccountAction $action)
    {
        $validated = $request->validated();

        $account = $action->store($request->user(), $validated);

        return response()->json([
            'status' => 'success',
            'data' => $account,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Account\UpdateRequest  $request
     * @param  \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, AccountModel $account, AccountAction $action)
    {
        $validated = $request->validated();

        if($updated = $action->update($account, $validated)) {
            return response()->json([
                'status' => 'success',
                'data' => $updated
            ]);
        }

        abort(403, 'Update unsuccesful, we be right back.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountModel $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountModel $account)
    {
        //
    }
}
