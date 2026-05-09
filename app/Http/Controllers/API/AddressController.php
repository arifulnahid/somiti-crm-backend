<?php

namespace App\Http\Controllers\API;

use App\Filters\V1\AddressFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressCollection;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
<<<<<<< HEAD
use Illuminate\Support\Facades\Log;
=======
>>>>>>> origin/filter

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
<<<<<<< HEAD
        Log::info($request->query());
        $address = Address::all();

        return response()->json([
            'data' => $address,
            'total' => $address->count()
        ]);
=======
        $filter = new AddressFilter;
        $filterItems = $filter->transform($request);

        $address = Address::query()->where($filterItems)->paginate();

        return $this->successResponse(AddressResource::collection($address)->response()->getData(true), 'Successfully retrive address');
>>>>>>> origin/filter
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request): JsonResponse
    {
        $data = $request->validated();

        $address = Address::create($data);

        return $this->createdResponse($address, 'created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        return $this->successResponse(new AddressResource($address), 'Address get successfully');

        return response()->json([
            'status' => true,
            'message' => 'Address',
            'data' => $address,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        $validated = $request->validated();

        $address->update($validated);

        return response()->json($address);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $address->delete($address->id);

        return response()->json(
            [
                'success' => true,
                'message' => 'Deleted Successfully',
                'data' => [
                    'id' => $address->id,
                ],
            ],
            200
        );
    }

    public function divisions()
    {
        // $addresses = Address::seletct('division', 'district')->distinct()->get();

        $address = Address::all('division', 'district')->groupBy('division')
        ->map(function ($items) {
            return $items->pluck('district')->unique()->values();
        });

        return response()->json($address);
    }
}
