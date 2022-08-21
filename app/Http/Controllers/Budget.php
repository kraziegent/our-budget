<?php

namespace App\Http\Controllers;

use App\Models\Budget as BudgetModel;
use App\Actions\Budget as BudgetAction;
use App\Http\Requests\Budget\ShareRequest;
use App\Http\Requests\Budget\StoreRequest;
use App\Http\Requests\Budget\UpdateRequest;

class Budget extends Controller
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
     * @param  \App\Http\Requests\Budget\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, BudgetAction $action)
    {
        $validated = $request->validated();

        $budget = $action->store($request->user(), $validated);

        return response()->json([
            'status' => 'success',
            'data' => $budget,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Budget\UpdateRequest  $request
     * @param  \App\Models\Budget $budget
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, BudgetModel $budget, BudgetAction $action)
    {
        $validated = $request->validated();

        if($updated = $action->update($budget, $validated)) {
            return response()->json([
                'status' => 'success',
                'data' => $updated
            ]);
        }

        abort(403, 'Update unsuccesful, we be right back.');
    }

    /**
     * Share a budget with another user or email.
     *
     * @param  \App\Http\Requests\Budget\ShareRequest  $request
     * @param  \App\Models\Budget $budget
     * @return \Illuminate\Http\Response
     */
    public function share(ShareRequest $request, BudgetModel $budget, BudgetAction $action)
    {
        $validated = $request->validated();

        if($shared = $action->share($budget, $validated)) {
            return response()->json([
                'status' => 'success',
                'data' => $shared
            ]);
        }

        abort(403, 'Sharing unsuccesful, we\'ll be right back.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Budget $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(BudgetModel $budget)
    {
        //
    }
}
