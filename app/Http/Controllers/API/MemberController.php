<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use Symfony\Component\HttpFoundation\JsonResponse;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $members = Member::with(['user', 'branch', 'society', 'permanentAddress', 'presentAddress'])->get();

        return response()->json($members);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request): JsonResponse
    {
        $member = Member::create($request->validated());

        return response()->json($member, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member): JsonResponse
    {
        $member->load(['user', 'branch', 'society', 'permanentAddress', 'presentAddress']);

        return response()->json($member);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member): JsonResponse
    {
        // Exclude uui from update validation if it shouldn't change,
        // but for simplicity we allow it if unique excluding current ID
        $validated = $request->validated();

        $member->update($validated);

        return response()->json($member);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member): JsonResponse
    {
        $member->delete();

        return response()->json(null, 204);
    }
}
