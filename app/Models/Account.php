<?php

namespace App\Models;

use App\Enums\AccountType;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use Uuids;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'currency',
        'type',
        'is_budget',
        'account_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_budget' => 'boolean',
        'type' => AccountType::class,
    ];

    /**
     * Get the user who owns this account.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    /**
     * Get all transactions that occured for this account
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id', 'uuid');
    }

    /**
     * Get the real bank account this account is mapped to
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(AccountMapping::class, 'account_mapping_id');
    }
}
