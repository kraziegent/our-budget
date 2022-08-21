<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use Uuids;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'is_default',
        'budget_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
        'is_hidden' => 'boolean',
    ];

    /**
     * Get the user who owns this account.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    /**
     * Get the budget the category was created.
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(User::class, 'budget_id', 'uuid');
    }

    /**
     * Get the master category for this category.
     */
    public function masterCategory(): BelongsTo
    {
        return $this->belongsTo(MasterCategory::class, 'master_category_id', 'uuid');
    }
}
