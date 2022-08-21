<?php

namespace App\Listeners;

use App\Models\User;
use App\Actions\Budget;
use App\Jobs\DefaultCategories;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;

class NewUserSetup implements ShouldQueue
{
    /**
     *
     */
    public function __construct(Budget $action)
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
            $budget = $this->action->store($event->user, ['name' => 'Budget']);
            Bus::dispatch(new DefaultCategories($event->user, $budget));
        }
    }
}
