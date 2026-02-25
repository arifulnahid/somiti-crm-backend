<?php

namespace App\Enums;

enum BranchType:string
{
    case CENTRAL = 'central';
    case DIVISION = 'division';
    case DISTRICT = 'disctrict';
    case UPAZILA = 'upazila';
    case UNION = 'union';
    case WARD = 'ward';
}
