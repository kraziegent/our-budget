<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ForUser
{
    /**
     * Boot the current company trait for a model.
     *
     * @return void
     */
    public static function bootForUser()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', auth()->user()->id);
        });
    }
}
