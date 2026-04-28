<?php
namespace App\Filters;
use App\Filters\V1\ApiFilter;

class AddressFilter extends ApiFilter{
    protected $safeParms = [
        'division' => ['eq'],
        'district' => ['eq'],
        'upazila' => ['eq'],
        'thana' => ['eq'],
        'union' => ['eq'],
        'village' => ['eq'],
        'ward'  => ['eq'],
        'wardNo'  => ['eq'],
        'postOffice'  => ['eq'],
        'postalCode'  => ['eq'],
    ];

    protected $columnMap = [
        'postalCode' => 'postal_code',
        'postOffice' => 'post_office',
        'wardNo' => 'ward_no'
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];
}