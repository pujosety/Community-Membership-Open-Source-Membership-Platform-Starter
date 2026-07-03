<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\Leave;
use App\Repositories\LeaveRepository;
use Illuminate\Http\Request;

class LeaveController extends ApiController
{
    public function __construct(private LeaveRepository $leaves) {}

    public function index(Request $request)
    {
        return $this->ok($this->leaves->paginatedForUser($request));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|max:255',
            'reason' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        $leave = $this->leaves->createFor($request->user(), $data);

        return $this->ok($leave, 'Created', 201);
    }

    public function approve(Request $request, Leave $leave)
    {
        $updated = $this->leaves->updateStatus($leave, 'approved');

        return $this->ok($updated, 'Approved');
    }

    public function reject(Request $request, Leave $leave)
    {
        $updated = $this->leaves->updateStatus($leave, 'rejected');

        return $this->ok($updated, 'Rejected');
    }
}
