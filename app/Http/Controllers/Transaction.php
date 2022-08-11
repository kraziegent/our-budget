<?php

namespace App\Http\Controllers;

use App\Actions\Transaction as TransactionAction;
use App\Http\Requests\Transaction\StoreRequest;
use App\Http\Requests\Transaction\UpdateRequest;
use App\Models\Transaction as TransactionModel;

class Transaction extends Controller
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
     * @param  \App\Http\Requests\Transaction\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, TransactionAction $action)
    {
        $validated = $request->validated();

        $transaction = $action->store($request->user(), $validated);

        return response()->json([
            'status' => 'success',
            'data' => $transaction,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Transaction\UpdateRequest  $request
     * @param  TransactionModel $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, TransactionModel $transaction, TransactionAction $action)
    {
        $validated = $request->validated();


        if($updated = $action->update($transaction, $validated)) {
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
     * @param  TransactionModel $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionModel $transaction)
    {
        //
    }
}
