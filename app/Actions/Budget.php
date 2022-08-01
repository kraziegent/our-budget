<?php

namespace App\Actions;

use App\Models\Budget as BudgetModel;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;

class Budget
{

    /**
     * Create a budget for the user for a category.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Category $category
     * @param mixed $budgeted
     * @param \Carbon\Carbon $budgetmonth
     *
     * @return \App\Models\Budget
     */
    public function store(User $user, Category $category, mixed $budgeted = null, Carbon $budgetmonth = null)
    {
        $budgetmonth = optional($budgetmonth)->firstOfMonth() ?? now()->firstOfMonth();
        $period = strtolower($budgetmonth->monthName);

        if (
            $budget = $user->budgets()->where('category_id', $category->uuid)
            ->where('budget_month', $budgetmonth)
            ->where('period', $period)
            ->first()
            )
        {
            if(! $budgeted) {
                return $budget;
            }

            return $this->update($budget, $budgeted);
        }

        if (! $budgeted) {
            $previousbudgets = $user->budgets()->whereDate('budget_month', $budgetmonth->subMonthNoOverflow())->get();
            $budgetmonth->addMonthNoOverflow();

            if ($previousBudget = $previousbudgets->where('category_id', $category->uuid)->first()) {
                $budgeted = $previousBudget->budgeted;
            } else {
                $budgeted = makeMoney('0', $user->currency);
            }
        } else {
            $budgeted = makeMoney($budgeted, $user->currency);
        }

        return $user->budgets()->create([
            'category_id' => $category->uuid,
            'budget_month' => $budgetmonth,
            'period' => $period,
            'budgeted' => $budgeted,
        ]);
    }

    /**
     * Update a budget value for a month
     *
     * @param \App\Models\Budget $budget
     * @param mixed budgeted
     * @return \App\Models\Budget
     */
    public function update(BudgetModel $budget, mixed $budgeted)
    {
        $budget->budgeted = makeMoney($budgeted, $budget->owner->currency);
        $budget->save();

        return $budget;
    }
}
