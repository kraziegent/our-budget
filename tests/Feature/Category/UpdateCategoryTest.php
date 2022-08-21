<?php

namespace Tests\Feature\Category;

use App\Models\Budget;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\MasterCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_updated()
    {
        $user = User::factory()->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->putJson(route('categories.update', $category), [
            'name' => 'Rent',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'uuid' => $category->uuid,
            'master_category_id' => $mastercategory->uuid,
            'name' => 'Rent',
            'is_default' => 0
        ]);
    }

    public function test_name_is_required_to_update_category()
    {
        $user = User::factory()->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->putJson(route('categories.update', $category), [

        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            "name" => [
                "The name field is required."
            ]
        ]);
    }

    public function test_different_user_cannot_update_another_users_category()
    {
        $user = User::factory()->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->putJson(route('categories.update', $category), [
            'name' => 'Rent',
        ]);

        $response->assertStatus(403);
    }
}
