<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
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
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }
    
    public function approve(): bool
    {
        if (!$this->isPending()) {
            return false;
        }
        
        DB::transaction(function () {
            // Update receiver balance
            $receiver = $this->receiver;
            $receiver->balance += $this->amount;
            $receiver->save();
            
            // Update transaction
            $this->status = 'approved';
            $this->approved_at = now();
            $this->receiver_after_balance = $receiver->balance;
            $this->save();
        });
        
        return true;
    }
    
    public function refund(): bool
    {
        if (!$this->isApproved()) {
            return false;
        }
        
        DB::transaction(function () {
            // Return money to sender
            $sender = $this->sender;
            $sender->balance += $this->amount;
            $sender->save();
            
            // Update transaction
            $this->status = 'refunded';
            $this->refunded_at = now();
            $this->sender_after_balance = $sender->balance;
            $this->save();
        });
        
        return true;
    }
}
