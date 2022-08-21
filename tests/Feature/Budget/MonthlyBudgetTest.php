<?php

namespace Tests\Feature\Budget;

use App\Actions\MonthlyBudget as MonthlyBudgetAction;
use App\Actions\Category;
use App\Jobs\MonthlyBudget;
use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class MonthlyBudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_budgets_can_be_auto_created_for_user_categories()
    {
        $user = User::factory()->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $action = app(Category::class);

        $category = $action->store($user, $budget, [
            'master_category_name' => 'Yearly Bills',
            'name' => 'Rent',
            'is_default' => true,
        ]);

        Bus::dispatch(new MonthlyBudget($user, $budget));

        $this->assertDatabaseHas('monthly_budgets', [
            'category_id' => $category->uuid,
            'user_id' => $user->uuid,
            'budget_month' => now()->firstOfMonth()->format('Y-m-d'),
            'period' => strtolower(now()->monthName),
        ]);
    }

    public function test_budgets_cannot_be_created_more_than_once_for_user_categories_per_month()
    {
        $user = User::factory()->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $action = app(Category::class);

        $category = $action->store($user, $budget, [
            'master_category_name' => 'Yearly Bills',
            'name' => 'Rent',
            'is_default' => true,
        ]);

        Bus::dispatch(new MonthlyBudget($user, $budget));
        Bus::dispatch(new MonthlyBudget($user, $budget));

        $budgets = $user->monthlyBudgets()->where('category_id', $category->uuid)
        ->where('budget_month', now()->firstOfMonth()->format('Y-m-d'))
        ->where('period', strtolower(now()->monthName))
        ->get();

        $this->assertCount(1, $budgets);
    }

    public function test_budgets_can_be_created_from_previous_month_values()
    {
        $user = User::factory()->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $action = app(Category::class);

        $category = $action->store($user, $budget, [
            'master_category_name' => 'Yearly Bills',
            'name' => 'Rent',
            'is_default' => true,
        ]);

        $budgetAction = app(MonthlyBudgetAction::class);
        $prevBudget = $budgetAction->store($user, $category, $budget, budgetmonth: now()->firstOfMonth()->subMonthNoOverflow());

        $prevBudget->budgeted = makeMoney('50000', $user->currency);
        $prevBudget->save();

        $newBudget = $budgetAction->store($user, $category, $budget);

        $this->assertEquals($prevBudget->budgeted, $newBudget->budgeted);
    }
}
