<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterCategory extends Model
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
    ];

    /**
     * Get the user who owns this account.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    /**
     * Get the budget the mastercategory is created for.
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(User::class, 'budget_id', 'uuid');
    }

    /**
     * Get sub categories
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'master_category_id', 'uuid');
    }
}
