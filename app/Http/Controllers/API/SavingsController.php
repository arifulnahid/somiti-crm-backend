<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Savings;
use App\Http\Requests\StoreSavingsRequest;
use App\Http\Requests\UpdateSavingsRequest;

class SavingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSavingsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Savings $savings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSavingsRequest $request, Savings $savings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Savings $savings)
    {
        //
    }
}
