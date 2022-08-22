<?php

namespace Tests\Feature\Budget;

use App\Enums\BudgetStatus;
use App\Jobs\NewBudget;
use App\Models\Budget;
use App\Models\Category;
use App\Models\MasterCategory;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_budget_can_be_created_for_user()
    {
        Bus::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('budgets.store'), [
            'name' => 'Personal Budget',
            'status' => BudgetStatus::Active,
            'is_default' => true,
        ]);

        $response->assertStatus(200);

        Bus::assertDispatched(NewBudget::class);

        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->uuid,
            'name' => 'Personal Budget',
            'status' => BudgetStatus::Active,
            'is_default' => true,
        ]);
    }

    public function test_budget_cannot_be_created_with_invalid_status()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('budgets.store'), [
            'name' => 'Personal Budget',
            'status' => 'pending',
            'is_default' => true,
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_share_budget_with_new_email_address()
    {
        $user = User::factory()->create(['email' => 'janedoe@test']);
        $budget = Budget::factory()->for($user, 'owner')->create();

        $response = $this->actingAs($user)->postJson(route('budgets.share', $budget), [
            'email' => 'johndoe@test.com',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@test.com',
        ]);

        $this->assertDatabaseHas('shared_budgets', [
            'budget_id' => $budget->uuid,
        ]);
    }

    public function test_user_can_share_budget_with_exisiting_user()
    {
        $user = User::factory()->create();
        $sharee = User::factory()->create(['email' => 'johndoe@test.com']);

        $budget = Budget::factory()->for($user, 'owner')->create();

        $response = $this->actingAs($user)->postJson(route('budgets.share', $budget), [
            'email' => 'johndoe@test.com',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('shared_budgets', [
            'user_id' => $sharee->uuid,
            'budget_id' => $budget->uuid,
        ]);
    }

    public function test_user_can_update_budget()
    {
        $user = User::factory()->create();
        $budget = Budget::factory()->for($user, 'owner')->create();

        $response = $this->actingAs($user)->putJson(route('budgets.update', $budget), [
            'name' => 'Personal Budget',
            'status' => BudgetStatus::Active,
            'is_default' => true,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->uuid,
            'name' => 'Personal Budget',
            'status' => BudgetStatus::Active,
            'is_default' => true,
        ]);
    }

    public function test_new_budget_creates_defaults()
    {
        $user = User::factory()->create();
        $budget = Budget::factory()->for($user, 'owner')->create(['name' => 'Budget']);
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->count(3)->create();

        $response = $this->actingAs($user)->postJson(route('budgets.store'), [
            'name' => 'Second Budget',
            'status' => BudgetStatus::Active,
            'is_default' => true,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseCount('categories', 6);
        $this->assertDatabaseCount('master_categories', 2);
    }
}
