<?php

namespace Tests\Feature\Transaction;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payee;
use App\Models\Account;
use App\Models\Category;
use App\Enums\TransactionType;
use App\Models\Budget;
use App\Models\MasterCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SavingTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_single_transaction_can_be_saved()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $account = Account::factory()->for($user, 'owner')->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->postJson(route('transactions.store'), [
            'budget_id' => $budget->uuid,
            'category_id' => $category->uuid,
            'amount' => 5000,
            'payee_id' => $payee->uuid,
            'account_id' => $account->uuid,
            'is_cleared' => true,
            'type' => TransactionType::Credit,
            'transaction_date' => now(),
            'description' => 'Purchase of rice',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('transactions', [
            'category_id' => $category->uuid,
            'payee_id' => $payee->uuid,
            'account_id' => $account->uuid,
            'is_cleared' => true,
            'type' => TransactionType::Credit,
        ]);
    }

    public function test_transaction_amount_is_required()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $account = Account::factory()->for($user, 'owner')->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->postJson(route('transactions.store'), [
            'budget_id' => $budget->uuid,
            'category_id' => $category->uuid,
            'payee_id' => $payee->uuid,
            'account_id' => $account->uuid,
            'is_cleared' => true,
            'type' => TransactionType::Credit,
            'transaction_date' => now(),
            'description' => 'Purchase of rice',
        ]);

        $response->assertStatus(422);
    }

    public function test_transaction_category_is_required()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $account = Account::factory()->for($user, 'owner')->create();

        $response = $this->actingAs($user)->postJson(route('transactions.store'), [
            'amount' => 5000,
            'payee_id' => $payee->uuid,
            'account_id' => $account->uuid,
            'is_cleared' => true,
            'type' => TransactionType::Credit,
            'transaction_date' => now(),
            'description' => 'Purchase of rice',
        ]);

        $response->assertStatus(422);
    }

    public function test_transaction_account_is_required()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->postJson(route('transactions.store'), [
            'budget_id' => $budget->uuid,
            'category_id' => $category->uuid,
            'amount' => 5000,
            'payee_id' => $payee->uuid,
            'is_cleared' => true,
            'type' => TransactionType::Credit,
            'transaction_date' => now(),
            'description' => 'Purchase of rice',
        ]);

        $response->assertStatus(422);
    }

    public function test_transaction_payee_is_required()
    {
        $user = User::factory()->create();
        $account = Account::factory()->for($user, 'owner')->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->postJson(route('transactions.store'), [
            'budget_id' => $budget->uuid,
            'category_id' => $category->uuid,
            'amount' => 5000,
            'account_id' => $account->uuid,
            'is_cleared' => true,
            'type' => TransactionType::Credit,
            'transaction_date' => now(),
            'description' => 'Purchase of rice',
        ]);

        $response->assertStatus(422);
    }

    public function test_transaction_type_is_required()
    {
        $user = User::factory()->create();
        $payee = Payee::factory()->for($user, 'owner')->create();
        $account = Account::factory()->for($user, 'owner')->create();
        $budget = Budget::factory()->for($user, 'owner')->create();
        $mastercategory = MasterCategory::factory()->for($budget)->for($user, 'owner')->create();
        $category = Category::factory()->for($budget)->for($user, 'owner')->for($mastercategory, 'masterCategory')->create();

        $response = $this->actingAs($user)->postJson(route('transactions.store'), [
            'budget_id' => $budget->uuid,
            'category_id' => $category->uuid,
            'amount' => 5000,
            'payee_id' => $payee->uuid,
            'account_id' => $account->uuid,
            'is_cleared' => true,
            'transaction_date' => now(),
            'description' => 'Purchase of rice',
        ]);

        $response->assertStatus(422);
    }
}
