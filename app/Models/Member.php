<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'fathers_name',
        'mothers_name',
        'permanent_address',
        'present_address',
        'branch_id',
        'nominees',
        'society_id',
        'occupation',
        'type',
        'meta',
    ];

    protected $casts = [
        'nominees' => 'array',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    // public function society(): BelongsTo
    // {
    //     return $this->belongsTo(Society::class, 'society_id', 'id');
    // }

    public function permanentAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'permanent_address');
    }

    public function presentAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'present_address');
    }
}
