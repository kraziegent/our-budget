<?php

namespace Tests\Feature\Category;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\MasterCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SavingCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_single_category_can_be_saved_using_master_category_name()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('categories.store'), [
            'name' => 'Rent',
            'master_category_id' => null,
            'master_category_name' => 'Yearly Bills'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('master_categories', [
            'name' => 'Yearly Bills',
            'is_default' => 0
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Rent',
            'is_default' => 0
        ]);
    }

    public function test_single_category_can_be_saved_using_master_category_id()
    {
        $user = User::factory()->create();
        $mastercategory = MasterCategory::factory()->for($user, 'owner')->create();

        $response = $this->actingAs($user)->postJson(route('categories.store'), [
            'name' => 'Rent',
            'master_category_id' => $mastercategory->uuid,
            'master_category_name' => null
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'master_category_id' => $mastercategory->uuid,
            'name' => 'Rent',
            'is_default' => 0
        ]);
    }

    public function test_single_category_creation_requires_master_name_or_id()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('categories.store'), [
            'name' => 'Rent',
            'master_category_id' => null,
            'master_category_name' => null
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            "master_category_name" => [
                "You must provide either a previous master category or the name for a new one."
            ],
            "master_category_id" => [
                "You must provide either a previous master category or the name for a new one."
            ]
        ]);
    }

    public function test_category_cannot_be_created_twice()
    {
        $user = User::factory()->create();
        $mastercategory = MasterCategory::factory()->for($user, 'owner')->create();
        $category = Category::factory()->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->postJson(route('categories.store'), [
            'name' => $category->name,
            'master_category_id' => $mastercategory->uuid,
            'master_category_name' => null
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseCount('categories', 1);
    }
}
