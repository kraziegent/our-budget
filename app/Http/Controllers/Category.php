<?php

namespace App\Http\Controllers;

use App\Actions\Category as CategoryAction;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category as CategoryModel;
use Illuminate\Validation\ValidationException;

class Category extends Controller
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
     * @param  \App\Http\Requests\Category\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, CategoryAction $action)
    {
        $validated = $request->validated();
        $budget = $request->user()->budgets()->find($validated['budget_id']);

        if (! $budget) {
            throw ValidationException::withMessages([
                'budget_id' => 'Seems we were unable to find this budget for the user!'
            ]);
        }

        if (isset($validated['master_category_id']) && $validated['master_category_id']) {
            $masterCategory = $request->user()->masterCategories()->find($validated['master_category_id']);

            abort_if(! $masterCategory, 404, 'Invalid master category, kindly check the master category.');

            $category = $action->store($request->user(), $budget, $validated, $masterCategory);
        } else {
            $category = $action->store($request->user(), $budget, $validated);
        }

        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Category\UpdateRequest  $request
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, CategoryModel $category, CategoryAction $action)
    {
        $validated = $request->validated();

        if($updated = $action->update($category, $validated)) {
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
     * @param  \App\Models\CategoryModel $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryModel $category)
    {
        //
    }
}
