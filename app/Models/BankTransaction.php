<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankTransaction extends Model
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

    /**
     * Get the bank account where this transaction occured.
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(AccountMapping::class, 'account_mapping_id');
    }
}
