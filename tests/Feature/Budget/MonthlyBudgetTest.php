<?php

namespace Tests\Feature\Budget;

use App\Actions\Category;
use App\Jobs\MonthlyBudget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class MonthlyBudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_budgets_can_be_created_for_user_categories()
    {
        $user = User::factory()->create();
        $action = app(Category::class);

        $category = $action->store($user, [
            'master_category_name' => 'Yearly Bills',
            'name' => 'Rent',
            'is_default' => true,
        ]);

        Bus::dispatch(new MonthlyBudget($user));

        $this->assertDatabaseHas('budgets', [
            'category_id' => $category->uuid,
            'user_id' => $user->uuid,
            'budget_month' => now()->firstOfMonth()->format('Y-m-d'),
            'period' => strtolower(now()->monthName),
        ]);
    }

    public function test_budgets_cannot_be_created_more_than_once_for_user_categories_per_month()
    {
        $user = User::factory()->create();
        $action = app(Category::class);

        $category = $action->store($user, [
            'master_category_name' => 'Yearly Bills',
            'name' => 'Rent',
            'is_default' => true,
        ]);

        Bus::dispatch(new MonthlyBudget($user));
        Bus::dispatch(new MonthlyBudget($user));

        $budgets = $user->budgets()->where('category_id', $category->uuid)
        ->where('budget_month', now()->firstOfMonth()->format('Y-m-d'))
        ->where('period', strtolower(now()->monthName))
        ->get();

        $this->assertCount(1, $budgets);
    }

    public function test_budgets_can_be_created_from_previous_month_values()
    {
        $user = User::factory()->create();
        $action = app(Category::class);

        $action->store($user, [
            'master_category_name' => 'Yearly Bills',
            'name' => 'Rent',
            'is_default' => true,
        ]);

        Bus::dispatch(new MonthlyBudget($user, now()->firstOfMonth()->subMonthNoOverflow()));

        $budget = $user->budgets()->first();
        $budget->budgeted = makeMoney('50000', 'NGN');
        $budget->save();

        Bus::dispatch(new MonthlyBudget($user));

        $newBudget = $user->budgets()->where('uuid', '<>', $budget->uuid)->first();

        $this->assertEquals($budget->budgeted, $newBudget->budgeted);
    }
}
