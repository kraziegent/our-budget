<?php

namespace Tests\Feature\Payee;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SavingPayeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_single_payee_can_be_saved()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('payees.store'), [
            'name' => 'John Doe',
            'description' => 'This guy is my person oh.',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('payees', [
            'name' => 'John Doe',
            'description' => 'This guy is my person oh.',
        ]);
    }

    public function test_payee_saving_require_name()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('payees.store'), [
            'description' => 'This guy is my person oh.',
        ]);

        $response->assertStatus(422);
    }
}
