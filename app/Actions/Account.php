<?php

namespace App\Actions;

use App\Enums\BudgetStatus;
use App\Models\User;
use App\Enums\TransactionType;
use App\Jobs\DefaultCategories;
use App\Models\Account as AccountModel;
use Illuminate\Support\Facades\Bus;
use Illuminate\Validation\ValidationException;

class Account
{
    /**
     * Store a new account for a user
     *
     * @param \App\Models\User $user
     * @param array $data
     * @return \App\Models\Account
     */
    public function store(User $user, array $data)
    {
        $account = $user->accounts()->create([
            'name' => $data['name'],
            'currency' => $data['currency'],
            'type' => $data['type'],
            'is_budget' => $data['is_budget'],
            'account_number' => $data['account_number'],
        ]);

        if (isset($data['opening_balance'])) {
            $budget = $user->budgets()->where('is_default', true)->where('status', BudgetStatus::Active)->first();

            if (! $budget) {
                throw ValidationException::withMessages([
                    'budget_id' => 'Seems like you jumped the gun somewhere, kindly create a budget to continue.'
                ]);
            }

            if (! $category = $user->categories()->where('name', 'Opening Balance')->first()) {
                Bus::dispatchSync(new DefaultCategories($user, $budget));

                $category = $user->categories()->where('name', 'Opening Balance')->first();
            }

            $account->transactions()->create([
                'user_id' => $user->uuid,
                'category_id' => $category->uuid,
                'budget_id' => $budget->uuid,
                'amount' => makeMoney($data['opening_balance'], $account->currency),
                'is_cleared' => true,
                'type' => TransactionType::Credit,
                'transaction_date' => now(),
                'description' => 'Opening Balance',
            ]);
        }

        return $account;
    }

    /**
     * Update an account Model
     *
     * @param \App\Models\Account $account
     * @param array $data
     * @return \App\Models\Account|null
     */
    public function update(AccountModel $account, array $data)
    {
        if (isset($data['name'])) {
            $account->name = $data['name'];
        }

        if (isset($data['currency'])) {
            $account->currency = $data['currency'];
        }

        if (isset($data['type'])) {
            $account->type = $data['type'];
        }

        if (isset($data['is_budget'])) {
            $account->is_budget = $data['is_budget'];
        }

        if (isset($data['account_number'])) {
            $account->account_number = $data['account_number'];
        }

        if ($account->save()) {
            return $account;
        }

        return null;
    }
}
