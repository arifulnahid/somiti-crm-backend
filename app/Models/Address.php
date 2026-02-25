<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'division',
        'district',
        'upazila',
        'thana',
        'union',
        'village',
        'ward',
        'ward_no',
        'post_office',
        'postal_code',
    ];
}
