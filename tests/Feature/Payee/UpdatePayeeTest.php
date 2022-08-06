<?php

namespace Tests\Feature\Payee;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePayeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_payee_can_be_updated()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();

        $response = $this->actingAs($user)->putJson(route('payees.update', $payee), [
            'name' => 'John Doe',
            'description' => 'This is my padi oh',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('payees', [
            'name' => 'John Doe',
            'description' => 'This is my padi oh',
        ]);
    }

    public function test_a_user_cannot_update_another_users_payee()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->putJson(route('payees.update', $payee), [
            'name' => 'John Doe',
        ]);

        $response->assertStatus(403);
    }
}
