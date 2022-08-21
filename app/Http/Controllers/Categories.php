<?php

namespace App\Http\Controllers;

use App\Actions\Category;
use App\Http\Requests\Category\StoreManyRequest;
use Illuminate\Validation\ValidationException;

class Categories extends Controller
{
    public function __invoke(StoreManyRequest $request, Category $action)
    {
        $validated = $request->validated();
        $budget = $request->user()->budgets()->find($validated['budget_id']);

        if (! $budget) {
            throw ValidationException::withMessages([
                'budget_id' => 'Seems we were unable to find this budget for the user!'
            ]);
        }

        $categories = $action->storeMany($request->user(), $budget, $validated['categories']);

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}
