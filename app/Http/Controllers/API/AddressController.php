<?php

namespace App\Http\Controllers\API;

use App\Filters\V1\AddressFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // 1. Process query filters
        $filter = new AddressFilter;
        $filterItems = $filter->transform($request);

        // 2. Fetch cleanly validated pagination inputs
        $perPage = (int) $request->query('pageSize', 10);
        $currentPage = (int) $request->query('currentPage', 1);

        // 3. Must use paginate() instead of simplePaginate() to get total records
        $addresses = Address::query()
            ->where($filterItems)
            ->latest()
            ->paginate($perPage, ['*'], 'page', $currentPage);

        // 4. Wrap with Eloquent Resource Collection and hand off to trait
        return $this->successResponse(AddressResource::collection($addresses));
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

    public function getDivisionsAndDistricts(): JsonResponse
    {
        // 1. Fetch only unique division and district combinations from the DB
        $data = Address::query()
            ->select('division', 'district')
            ->groupBy('division', 'district')
            ->orderBy('division', 'asc')
            ->orderBy('district', 'asc')
            ->get();

        // 2. Format the collection into a 'division' => ['district1', 'district2'] structure
        $structuredData = $data->groupBy('division')
            ->map(function ($items) {
                // Pull out just the district strings into a flat, sequential array
                return $items->pluck('district')->values()->toArray();
            });

        return $this->successResponse($structuredData);
    }

    public function divisions()
    {
        // $addresses = Address::seletct('division', 'district')->distinct()->get();

        $address = Address::query()->distinct()->pluck('division')->toArray();

        return response()->json($address);
    }

    public function districts()
    {
        // $addresses = Address::seletct('division', 'district')->distinct()->get();

        $address = Address::query()->where('')->pluck('division')->toArray();

        return response()->json($address);
    }
}
