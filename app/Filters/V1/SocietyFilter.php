<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class SocietyFilter extends ApiFilter
{
    protected $safeParms = [
        'name' => ['eq'],
        'address' => ['eq'],
        'established_at' => ['eq'],
    ];

    protected $columnMap = [

    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];
}
