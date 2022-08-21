<?php

namespace App\Actions;

use App\Models\Budget;
use App\Models\Category as CategoryModel;
use App\Models\User;
use App\Models\MasterCategory;
use Illuminate\Support\Facades\DB;

class Category
{
    /**
     * Store a new category for a user and attach it to a master category
     *
     * @param \App\Models\User $user
     * @param array $data
     * @param \App\Models\MasterCategory $masterCategory
     *
     * @return \App\Models\Category
     */
    public function store(User $user, Budget $budget, array $data, MasterCategory $masterCategory = null)
    {
        $is_default = isset($data['is_default']) ? $data['is_default'] : false;

        if (! $masterCategory) {
            $masterCategory = $user->masterCategories()->firstOrCreate([
                'budget_id' => $budget->uuid,
                'name' => $data['master_category_name'],
                'is_default' => $is_default
            ]);
        }

        return $masterCategory->categories()->firstOrCreate([
            'user_id' => $user->uuid,
            'budget_id' => $budget->uuid,
            'name' => $data['name'],
            'is_default' => $is_default,
            'is_hidden' => $masterCategory->name === 'Hidden Categories' ? true : false,
        ]);
    }

    /**
     * Store multiple categories for a user and attach each to a master category
     *
     * @param \App\Models\User $user
     * @param array $data
     *
     * @return \Illuminate\Support\Collection
     */
    public function storeMany(User $user, Budget $budget, array $data)
    {
        $categories = collect();

        DB::beginTransaction();
            foreach($data as $value) {
                if (isset($value['master_category_id']) && $value['master_category_id']) {
                    $masterCategory = $user->masterCategories()->find($value['master_category_id']);

                    abort_if(! $masterCategory, 404, 'Invalid master category, kindly check the master category.');

                    $category = $this->store($user, $budget, $value, $masterCategory);
                } else {
                    $category = $this->store($user, $budget, $value);
                }

                $categories->push($category);
            }
        DB::commit();

        return $categories;
    }

    /**
     * Update a category in the database.
     *
     * @param \App\Models\Category $category
     * @param array $data
     * @return \App\Models\Category|null
     */
    public function update(CategoryModel $category, array $data)
    {
        if (isset($data['name'])) {
            $category->name = $data['name'];
        }

        if ($category->save()) {
            return $category;
        }

        return null;
    }
}
