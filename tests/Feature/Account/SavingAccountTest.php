<?php

namespace Tests\Feature\Account;

use App\Enums\AccountType;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SavingAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_new_account_can_be_saved()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('accounts.store'), [
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

    public function test_a_new_account_can_be_saved_with_opening_balance()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('accounts.store'), [
            'name' => 'Savings Account',
            'currency' => 'NGN',
            'type' => AccountType::SavingsAccount,
            'is_budget' => true,
            'account_number' => '1234567890',
            'opening_balance' => 5000,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->uuid,
            'account_id' => $user->accounts()->first()->uuid,
        ]);
    }

    public function test_wrong_type_not_allowed_when_saving_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('accounts.store'), [
            'name' => 'Savings Account',
            'currency' => 'NGN',
            'type' => 'Wrong Type',
            'is_budget' => true,
            'account_number' => '1234567890',
            'opening_balance' => 5000,
        ]);

        $response->assertStatus(422);
    }
}
