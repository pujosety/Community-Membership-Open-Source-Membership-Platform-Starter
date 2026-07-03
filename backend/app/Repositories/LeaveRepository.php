<?php

namespace App\Repositories;

use App\Models\Leave;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaveRepository
{
    public function paginatedForUser(\Illuminate\Http\Request $request): LengthAwarePaginator
    {
        return Leave::query()
            ->when($request->user()?->role !== 'superadmin', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    public function createFor(\App\Models\User $user, array $data): Leave
    {
        return $user->leaves()->create($data);
    }

    public function updateStatus(Leave $leave, string $status): Leave
    {
        $leave->update(['status' => $status]);

        return $leave->refresh();
    }
}
