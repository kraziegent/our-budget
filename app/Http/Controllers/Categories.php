<?php

namespace App\Http\Controllers;

use App\Actions\Category;
use App\Http\Requests\Category\StoreManyRequest;

class Categories extends Controller
{
    public function __invoke(StoreManyRequest $request, Category $action)
    {
        $validated = $request->validated();

        $categories = $action->storeMany($request->user(), $validated['categories']);

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}
