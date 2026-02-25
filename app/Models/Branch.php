<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

     protected $fillable = [
        'name',
        'address_id',
        'type',
        'manager',
        'cashier',
        'parent_branch',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta' => 'array'
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager');
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'parent_branch');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Branch::class, 'parent_branch');
    }
}
