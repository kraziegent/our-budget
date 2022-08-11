<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use Uuids;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'type',
        'user_id',
        'account_id',
        'category_id',
        'transfer_account_id',
        'payee_id',
        'amount',
        'is_cleared',
        'transaction_date',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => MoneyCast::class,
        'is_cleared' => 'boolean',
        'is_checked' => 'boolean',
        'transaction_date' => 'date',
    ];

    /**
     * Get the user who owns this account.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    /**
     * Get the account the transaction will be posted.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'uuid');
    }

    /**
     * Get the category for the transaction.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'uuid');
    }

    /**
     * Get the counter party for the transaction.
     */
    public function payee(): BelongsTo
    {
        return $this->belongsTo(Payee::class, 'payee_id', 'uuid');
    }
}
