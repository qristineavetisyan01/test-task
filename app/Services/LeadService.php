<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeadService
{
    /**
     * @param  array<string, string|null>  $filters
     */
    public function paginated(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Lead::query()->latest();

        if (! empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status']) && in_array($filters['status'], Lead::STATUSES, true)) {
            $query->where('status', $filters['status']);
        }

        return $query
            ->paginate($perPage)
            ->appends($filters);
    }
}
