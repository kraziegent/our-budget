<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_transactions', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('user_id')->constrained('users', 'uuid')->onDelete('cascade');
            $table->foreignUuid('account_id')->constrained('accounts', 'uuid')->onDelete('cascade');
            $table->foreignUuid('category_id')->constrained('categories', 'uuid')->onDelete('cascade');
            $table->foreignUuid('transfer_account_id')->nullable()->constrained('accounts', 'uuid')->onDelete('cascade');
            $table->foreignUuid('payee_id')->nullable()->constrained('payees', 'uuid')->onDelete('set null');
            $table->json('amount');
            $table->text('description')->nullable();
            $table->json('frequency'); // type, recurrence, start_date, next_run_date
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_transactions');
    }
};
