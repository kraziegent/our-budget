<?php

namespace Tests\Feature\Account;

use App\Enums\AccountType;
use App\Models\Account;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_can_be_updated()
    {
        $user = User::factory()->create();
        $account = Account::factory()->for($user, 'owner')->create();

        $response = $this->actingAs($user)->putJson(route('accounts.update', $account), [
            'name' => 'Savings Account',
            'currency' => 'NGN',
            'type' => AccountType::SavingsAccount,
            'is_budget' => true,
            'account_number' => '1234567890',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('accounts', [
            'name' => 'Savings Account',
            'currency' => 'NGN',
            'type' => AccountType::SavingsAccount,
            'is_budget' => true,
            'account_number' => '1234567890',
        ]);
    }

    public function test_different_user_cannot_update_another_users_account()
    {
        $user = User::factory()->create();
        $account = Account::factory()->for($user, 'owner')->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->putJson(route('accounts.update', $account), [
            'name' => 'Savings Account',
        ]);

        $response->assertStatus(403);
    }
}
