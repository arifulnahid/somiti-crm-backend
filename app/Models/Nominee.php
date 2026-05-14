<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Nominee extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'name',
        'fathers_name',
        'mothers_name',
        'gender',
        'dob',
        'nid',
        'birth_id',
        'passport_id',
        'present_address',
        'relationship',
        'meta',
    ];

    protected $casts = [
        'dob' => 'date',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function deposits(): BelongsToMany
    {
        return $this->belongsToMany(Deposit::class, 'deposit_nominee', 'nominee_id', 'deposit_id');
    }
}
