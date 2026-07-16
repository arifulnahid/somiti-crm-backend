<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Savings extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'installment',
        'duration',
        'deadline',
        'meta',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'installment' => 'integer',
        'deadline' => 'date',
        'meta' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function nominees(): BelongsToMany
    {
        return $this->belongsToMany(Nominee::class, 'deposit_nominee', 'deposit_id', 'nominee_id');
    }

    // Accessors
    public function getDurationInYearsAttribute(): ?int
    {
        return (int) filter_var($this->duration, FILTER_SANITIZE_NUMBER_INT);
    }

    // Scopes
    public function scopeUpcomingDeadline($query)
    {
        return $query->where('deadline', '>', now())
            ->orderBy('deadline', 'asc');
    }

    public function scopeExpired($query)
    {
        return $query->where('deadline', '<', now());
    }
}
