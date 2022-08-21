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
        Schema::create('monthly_budgets', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('user_id')->constrained('users', 'uuid')->onDelete('cascade');
            $table->foreignUuid('budget_id')->constrained('budgets', 'uuid')->onDelete('cascade');
            $table->foreignUuid('category_id')->constrained('categories', 'uuid');
            $table->json('budgeted')->nullable();
            $table->json('overflow')->nullable();
            $table->boolean('allow_overspending')->default(1);
            $table->string('period');
            $table->date('budget_month');
            $table->json('target')->nullable();
            $table->json('frequency')->nullable(); // type, recurrence, recurrence date, start_date, next_run_date
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
        Schema::dropIfExists('monthly_budgets');
    }
};
