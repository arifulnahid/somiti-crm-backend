<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    
    protected $fillable = [
        'user_id',
        'amount',
        'installment',
        'duration'
    ];
    
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'amount' => 'integer',
        'installment' => 'integer',
        'duration' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Accessors
    public function getDurationInMonthsAttribute(): ?int
    {
        $value = (int) filter_var($this->duration, FILTER_SANITIZE_NUMBER_INT);
        
        if (str_contains($this->duration, 'Y')) {
            return $value * 12;
        }
        
        return $value;
    }
    
    public function getTotalPayableAttribute(): int
    {
        return $this->installment * $this->duration_in_months;
    }
    
    // Scopes
    public function scopeHighValue($query, int $threshold = 50000)
    {
        return $query->where('amount', '>=', $threshold);
    }
}