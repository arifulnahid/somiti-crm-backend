<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GlobalCollection extends ResourceCollection
{
    protected $message;

    public function __construct($resource, string $message = 'Success')
    {
        parent::__construct($resource);
        $this->message = $message;
    }

    public function toArray(Request $request): array
    {
        return [
            'success'     => true,
            'message'     => $this->message,
            'data'        => $this->collection, // Maps nicely to whatever Resource type was passed
            'timestamp'   => now()->toIso8601String(),
            'status_code' => 200,
        ];
    }
}
