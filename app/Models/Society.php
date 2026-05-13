<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Scout;
use Laravel\Scout\Searchable;

class Society extends Model
{
    use SoftDeletes;
    use Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();
 
        return $array;
    }


    /**
     * Get the engine used to index the model.
     */
    public function searchableUsing(): Engine
    {
        return Scout::engine('database');
    }

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
