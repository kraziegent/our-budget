<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Uuids;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get user categories
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'user_id', 'uuid');
    }

    /**
     * Get user master categories
     */
    public function masterCategories(): HasMany
    {
        return $this->hasMany(MasterCategory::class, 'user_id', 'uuid');
    }

    /**
     * Get user budgets
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'user_id', 'uuid');
    }

    /**
     * Get budgets shared with user
     */
    public function shared()
    {
        return $this->hasMany(SharedBudget::class, 'user_id', 'uuid');
    }

    /**
     * Get budgets shared with other users
     */
    public function share()
    {
        return $this->hasManyThrough(SharedBudget::class, Budget::class, 'user_id', 'budget_id', 'uuid', 'uuid');
    }

    /**
     * Get user monthly budgets
     */
    public function monthlyBudgets(): HasMany
    {
        return $this->hasMany(MonthlyBudget::class, 'user_id', 'uuid');
    }

    /**
     * Get user accounts
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'user_id', 'uuid');
    }

    /**
     * Get user payees
     */
    public function payees(): HasMany
    {
        return $this->hasMany(Payee::class, 'user_id', 'uuid');
    }

    /**
     * Get user transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'uuid');
    }
}
