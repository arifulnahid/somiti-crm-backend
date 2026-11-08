<?php
namespace App\Services;

use App\Models\Transaction;

class DashboardService
{
    public function getSummaryStats(array $filters)
    {
        return Transaction::query()
            ->when($filters['branch_id'] ?? null, fn ($q, $id) => $q->where('branch_id', $id))
            ->when($filters['start_date'] ?? null, fn ($q, $date) => $q->where('created_at', '>=', $date))
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(amount) as gross_revenue,
                AVG(amount) as avg_order_value
            ')
            ->first();
    }
}
