<?php

namespace App\Http\Controllers;

use App\Actions\Payee as PayeeAction;
use App\Models\Payee as PayeeModel;
use App\Http\Requests\Payee\StoreRequest;
use App\Http\Requests\Payee\UpdateRequest;

class Payee extends Controller
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
     * @param  \App\Http\Requests\Payee\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, PayeeAction $action)
    {
        $validated = $request->validated();

        $payee = $action->store($request->user(), $validated);

        return response()->json([
            'status' => 'success',
            'data' => $payee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Payee\UpdateRequest   $request
     * @param  \App\Models\Payee $payee
     * @param \App\Actions\Payee $action
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, PayeeModel $payee, PayeeAction $action)
    {
        $validated = $request->validated();

        if($updated = $action->update($payee, $validated)) {
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
     * @param  PayeeModel $payee
     * @return \Illuminate\Http\Response
     */
    public function destroy(PayeeModel $payee)
    {
        //
    }
}
