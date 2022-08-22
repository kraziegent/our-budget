<?php

namespace App\Jobs;

use App\Actions\Category;
use App\Models\User;
use App\Models\Budget;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewBudget implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**@var \App\Models\User */
    private $user;

    /**@var \App\Models\Budget */
    private $budget;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Budget $budget)
    {
        $this->user = $user;
        $this->budget = $budget;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Category $action)
    {
        // Copy categories and master categories
        $this->user->masterCategories()->orderBy('created_at')->lazy()->each(function($masterCategory) use($action) {
            $categories = $masterCategory->categories()->get();

            $newMasterCategory = $this->user->masterCategories()->firstOrCreate([
                'budget_id' => $this->budget->uuid,
                'name' => $masterCategory->name,
                'is_default' => $masterCategory->is_default,
            ]);

            if ($categories->count() > 0) {
                foreach($categories as $category) {
                    $data = [
                        'name' => $category->name,
                        'is_default' => $category->is_default,
                    ];

                    $action->store($this->user, $this->budget, $data, $newMasterCategory);
                }
            }
        });
    }
}
