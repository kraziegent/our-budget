<?php

namespace App\Actions;

use App\Models\User;
use App\Enums\BudgetStatus;
use App\Enums\SharedBudgetStatus;
use App\Models\Budget as BudgetModel;
use Illuminate\Validation\ValidationException;

class Budget
{

    public function store(User $user, array $data)
    {
        $is_default = !isset($data['is_default']) ? false : $data['is_default'];
        $status = !isset($data['status']) ? BudgetStatus::Active : $data['status'];

        if ($is_default) {
            $user->budgets()->where('is_default', true)->update(['is_default' => false]);
        }

        $budget = $user->budgets()->create([
            'name' => $data['name'],
            'status' => $status,
            'is_default' => $is_default,
        ]);

        return $budget;
    }

    public function update(BudgetModel $budget, array $data)
    {
        if (isset($data['name'])) {
            $budget->name = $data['name'];
        }

        if (isset($data['status'])) {
            $budget->status = $data['status'];
        }

        if (isset($data['is_default'])) {
            $user = $budget->owner;

            if ($data['is_default']) {
                $user->budgets()->where('is_default', true)->where('uuid', '<>', $budget->uuid)->update(['is_default' => false]);
                $budget->is_default = $data['is_default'];
            }

            if (!$data['is_default']) {
                if ($user->budgets()->where('is_default', true)->count() == 1 && $budget->is_default) {
                    throw ValidationException::withMessages([
                        'is_default' => 'User must have at least 1 default budget!'
                    ]);
                }

                $budget->is_default = $data['is_default'];
            }
        }

        if($budget->save()) {
            return $budget;
        }

        return null;
    }

    public function share(BudgetModel $budget, array $data)
    {
        try {
            $user = User::firstOrCreate([
                'email' => $data['email'],
            ]);
        } catch(\Throwable $e) {

        }

        $status = isset($data['status']) ? $data['status'] : SharedBudgetStatus::Active;

        $shared = $budget->share()->create([
            'user_id' => $user->uuid,
            'status' => $status,
        ]);

        return $shared;
    }

    public function unShare(BudgetModel $budget, User $user)
    {

    }
}
