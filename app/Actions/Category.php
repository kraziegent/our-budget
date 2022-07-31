<?php

namespace App\Actions;

use App\Models\User;
use App\Models\MasterCategory;

class Category
{
    /**
     * Store a new category for a user and attach it to a master category
     *
     * @param App\Models\User $user
     * @param array $data
     * @param App\Models\MasterCategory $masterCategory
     *
     * @return App\Models\Category
     */
    public function store(User $user, array $data, MasterCategory $masterCategory = null)
    {
        $is_default = isset($data['is_default']) ? $data['is_default'] : false;

        if (! $masterCategory) {
            $masterCategory = $user->masterCategories()->firstOrCreate([
                'name' => $data['master_category_name'],
                'is_default' => $is_default
            ]);
        }

        return $masterCategory->categories()->firstOrCreate([
            'user_id' => $user->uuid,
            'name' => $data['name'],
            'is_default' => $is_default
        ]);
    }
}
