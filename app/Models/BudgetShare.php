<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetShare extends Model
{
    use Uuids;
    use HasFactory;

    /**
     * Get the user who owns this account.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
