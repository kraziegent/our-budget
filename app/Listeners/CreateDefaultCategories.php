<?php

namespace App\Listeners;

use App\Models\User;
use App\Actions\Category;
use Illuminate\Auth\Events\Registered;

class CreateDefaultCategories
{
    /**
     *
     */
    public function __construct(Category $action)
    {
        $this->action = $action;
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->user instanceof User && $event->user->categories()->where('is_default', true)->count() == 0) {
            $master_categories = config('ob_categories');

            foreach($master_categories as $master_category => $categories) {
                if (count($categories) > 0) {
                    foreach($categories as $category) {
                        $data = [
                            'master_category_name' => $master_category,
                            'name' => $category,
                            'is_default' => true,
                        ];

                        $this->action->store($event->user, $data);
                    }
                } else {
                    $event->user->masterCategories()->firstOrCreate([
                        'name' => $master_category,
                        'is_default' => true,
                    ]);
                }
            }
        }
    }
}
