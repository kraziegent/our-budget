<?php

namespace App\Jobs;

use App\Actions\Budget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MonthlyBudget implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**@var \Carbon\Carbon */
    private $budgetmonth;

    /**@var \App\Models\User */
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Carbon $budgetmonth = null)
    {
        $this->user = $user;
        $this->budgetmonth = $budgetmonth ? $budgetmonth->firstOfMonth() : now()->firstOfMonth();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Budget $action)
    {
        $this->user->categories()->orderBy('created_at')->chunk(50, function($categories) use($action) {
            foreach($categories as $category) {
                $action->store($this->user, $category, budgetmonth: $this->budgetmonth);
            }
        });
    }
}
