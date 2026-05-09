<?php
<<<<<<< HEAD
namespace App\Filters;
use App\Filters\V1\ApiFilter;

class AddressFilter extends ApiFilter{
=======

namespace App\Filters\V1;

use App\Filters\ApiFilter;


class AddressFilter extends ApiFilter {
>>>>>>> origin/filter
    protected $safeParms = [
        'division' => ['eq'],
        'district' => ['eq'],
        'upazila' => ['eq'],
        'thana' => ['eq'],
        'union' => ['eq'],
<<<<<<< HEAD
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
=======
        'ward' => ['eq'],
        'postalCode'  => ['eq', 'gt', 'lt']
    ];

    protected $columnMap = [
        'postalCode' => 'postal_code'
>>>>>>> origin/filter
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];
<<<<<<< HEAD
=======

>>>>>>> origin/filter
}