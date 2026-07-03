<?php

namespace App\Repositories;

use App\Models\Report;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportRepository
{
    public function paginatedForUser(\Illuminate\Http\Request $request): LengthAwarePaginator
    {
        return Report::query()
            ->when($request->user()?->role !== 'superadmin', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    public function createFor(\App\Models\User $user, array $data): Report
    {
        return $user->reports()->create($data);
    }

    public function delete(Report $report): void
    {
        $report->delete();
    }
}
