<?php

namespace App\Models;

use App\Traits\Uuids;
use App\Enums\SharedBudgetStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SharedBudget extends Model
{
    use Uuids;
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => SharedBudgetStatus::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'status',
    ];

    /**
     * Get the user whom a budget was shared with.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the budget that was shared with the user.
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }
}
