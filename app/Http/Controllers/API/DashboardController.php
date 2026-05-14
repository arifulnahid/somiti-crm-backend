<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, DashboardService $service)
    {
        // 1. Authorize via Policy
        $this->authorize('viewDashboard', Transaction::class);

        // 2. Collect Filters (Role-based)
        $filters = $request->only(['start_date', 'end_date']);
        
        if ($request->user()->hasRole(['branch_manager'])) {
            $filters['branch_id'] = $request->user()->branch_id;
        }

        // 3. Return Stats
        return response()->json($service->getSummaryStats($filters));
    }
}
