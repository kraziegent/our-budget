<?php

namespace Tests\Feature\Category;

use App\Models\Budget;
use Tests\TestCase;
use App\Models\User;
use App\Models\MasterCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SavingMultipleCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_multiple_categories_can_be_created_with_master_category_name_or_id()
    {
        $user = User::factory()->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();

        $response = $this->actingAs($user)->postJson(route('categories.store.many'), [
            'budget_id' => $budget->uuid,
            'categories' => [
                ['name' => 'Rent', 'master_category_name' => 'Yearly Bills'],
                ['name' => 'Medical', 'master_category_id' => $mastercategory->uuid]
            ]
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseCount('categories', 2);
    }

    public function test_multiple_categories_require_name_for_each_category()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('categories.store.many'), [
            'categories' => [
                ['master_category_name' => 'Yearly Bills'],
            ]
        ]);

        $response->assertStatus(422);
    }

    public function test_multiple_categories_require_one_of_master_category_id_or_name_for_each_category()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('categories.store.many'), [
            'categories' => [
                ['name' => 'Rent', 'master_category_name' => 'Yearly Bills'],
                ['name' => 'Medical']
            ]
        ]);

        $response->assertStatus(422);
    }
}
