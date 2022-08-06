<?php

namespace App\Jobs;

use App\Models\User;
use App\Actions\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DefaultCategories implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**@var \App\Models\User */
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Category $action)
    {
        $master_categories = config('ob_categories');

        foreach($master_categories as $master_category => $categories) {
            if (count($categories) > 0) {
                foreach($categories as $category) {
                    $data = [
                        'master_category_name' => $master_category,
                        'name' => $category,
                        'is_default' => true,
                    ];

                    $action->store($this->user, $data);
                }
            } else {
                $this->user->masterCategories()->firstOrCreate([
                    'name' => $master_category,
                    'is_default' => true,
                ]);
            }
        }
    }
}
