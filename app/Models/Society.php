<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Society extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'cover_image',
        'description',
        'address',
        'committee',
        'meta',
    ];

    protected $casts = [
        'committee' => 'array',
        'meta' => 'array',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'society_id');
    }
}
