<?php

namespace Tests\Feature\Category;

use App\Enums\TransactionType;
use App\Models\Account;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\MasterCategory;
use App\Models\Payee;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaction_can_be_updated()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $account = Account::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($user, 'owner')->create();
        $category = Category::factory()->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $transaction = Transaction::factory()
                                    ->for($user, 'owner')
                                    ->for($payee)
                                    ->for($account)
                                    ->for($category)
                                    ->create();

        $response = $this->actingAs($user)->putJson(route('transactions.update', $transaction), [
            'is_cleared' => true,
            'type' => TransactionType::Credit,
            'description' => 'Purchase of rice',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('transactions', [
            'is_cleared' => true,
            'type' => TransactionType::Credit,
            'description' => 'Purchase of rice',
        ]);
    }

    public function test_different_user_cannot_update_another_users_transaction()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $account = Account::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($user, 'owner')->create();
        $category = Category::factory()->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();
        $transaction = Transaction::factory()
                                    ->for($user, 'owner')
                                    ->for($payee)
                                    ->for($account)
                                    ->for($category)
                                    ->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->putJson(route('transactions.update', $transaction), [
            'is_cleared' => true,
            'type' => TransactionType::Credit,
            'description' => 'Purchase of rice',
        ]);

        $response->assertStatus(403);
    }
}
