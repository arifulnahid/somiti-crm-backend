<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;


class AddressFilter extends ApiFilter {
    protected $safeParms = [
        'division' => ['eq'],
        'district' => ['eq'],
        'upazila' => ['eq'],
        'thana' => ['eq'],
        'union' => ['eq'],
        'ward' => ['eq'],
        'postalCode'  => ['eq', 'gt', 'lt']
    ];

    protected $columnMap = [
        'postalCode' => 'postal_code'
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

}