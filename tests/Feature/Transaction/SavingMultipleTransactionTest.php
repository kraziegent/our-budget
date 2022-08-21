<?php

namespace Tests\Feature\Transaction;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payee;
use App\Models\Account;
use App\Models\Category;
use App\Models\MasterCategory;
use App\Enums\TransactionType;
use App\Models\Budget;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SavingMultipleTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_multiple_transactions_can_be_created_with_master_category_name_or_id()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $account = Account::factory()->for($user, 'owner')->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->postJson(route('transactions.store.many'), [
            'budget_id' => $budget->uuid,
            'transactions' => [
                [
                    'category_id' => $category->uuid,
                    'account_id' => $account->uuid,
                    'amount' => 1000,
                    'type' => TransactionType::Credit,
                    'payee_id' => $payee->uuid,
                    'is_cleared' => true,
                    'transaction_date' => now(),
                    'description' => 'This is for the win',
                ],
                [
                    'category_id' => $category->uuid,
                    'account_id' => $account->uuid,
                    'amount' => 100,
                    'type' => TransactionType::Debit,
                    'payee_name' => 'GTBank',
                    'is_cleared' => true,
                    'transaction_date' => now(),
                    'description' => 'Bank Charges',
                ]
            ]
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseCount('transactions', 2);
        $this->assertDatabaseCount('payees', 2);
    }

    public function test_multiple_transactions_require_category_id_for_each_transactions()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $account = Account::factory()->for($user, 'owner')->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->postJson(route('transactions.store.many'), [
            'transactions' => [
                [
                    'account_id' => $account->uuid,
                    'amount' => 1000,
                    'type' => TransactionType::Credit,
                    'payee_id' => $payee->uuid,
                    'is_cleared' => true,
                    'transaction_date' => now(),
                    'description' => 'This is for the win',
                ],
                [
                    'category_id' => $category->uuid,
                    'account_id' => $account->uuid,
                    'amount' => 100,
                    'type' => TransactionType::Debit,
                    'payee_name' => 'GTBank',
                    'is_cleared' => true,
                    'transaction_date' => now(),
                    'description' => 'Bank Charges',
                ]
            ]
        ]);

        $response->assertStatus(422);
    }

    public function test_multiple_transactions_require_account_id_for_each_transactions()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $account = Account::factory()->for($user, 'owner')->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->postJson(route('transactions.store.many'), [
            'transactions' => [
                [
                    'category_id' => $category->uuid,
                    'amount' => 1000,
                    'type' => TransactionType::Credit,
                    'payee_id' => $payee->uuid,
                    'is_cleared' => true,
                    'transaction_date' => now(),
                    'description' => 'This is for the win',
                ],
                [
                    'category_id' => $category->uuid,
                    'account_id' => $account->uuid,
                    'amount' => 100,
                    'type' => TransactionType::Debit,
                    'payee_name' => 'GTBank',
                    'is_cleared' => true,
                    'transaction_date' => now(),
                    'description' => 'Bank Charges',
                ]
            ]
        ]);

        $response->assertStatus(422);
    }
}
