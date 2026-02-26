<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'title',
        'amount',
        'current_balance',
        'type',
        'status',
        'success',
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'description',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'success' => 'boolean',
        'meta' => 'array',
    ];

    protected static function boot():void
    {
        parent::boot();
        static::creating(function ($model){
            $model->transaction_id = Str::uuid()->toString();
        });
    }

    public function sender(): MorphTo
    {
        return $this->morphTo();
    }

    public function receiver(): MorphTo
    {
        return $this->morphTo();
    }
}
