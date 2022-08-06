<?php

namespace App\Listeners;

use App\Models\User;
use App\Actions\Category;
use App\Jobs\DefaultCategories;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;

class CreateDefaultCategories implements ShouldQueue
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
            Bus::dispatch(new DefaultCategories($event->user));
        }
    }
}
