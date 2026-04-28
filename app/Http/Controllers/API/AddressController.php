<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Log::info($request->query());
        $address = Address::all();

        return response()->json([
            'data' => $address,
            'total' => $address->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request): JsonResponse
    {
        $data = $request->validated();

        $address = Address::create($data);

        return $this->createdResponse($address, "created");

        return response()->json([
            'status' => true,
            'message' => 'Address Create Successfully',
            'data' => $address,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
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
        $address->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Deleted Successfully',
                'data' => [
                    'id' => $address->id
                ]
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
