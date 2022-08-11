<?php

namespace App\Http\Controllers;

use App\Actions\Transaction;
use App\Http\Requests\Transaction\StoreManyRequest;

class Transactions extends Controller
{
    public function __invoke(StoreManyRequest $request, Transaction $action)
    {
        $validated = $request->validated();

        $transactions = $action->storeMany($request->user(), $validated['transactions']);

        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ]);
    }
}
