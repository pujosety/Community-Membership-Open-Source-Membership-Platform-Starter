<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\Member;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;

class MemberController extends ApiController
{
    public function __construct(private MemberRepository $members) {}

    public function index(Request $request)
    {
        return $this->ok($this->members->paginated($request));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'member_number' => 'nullable|string|max:255',
            'card_status' => 'nullable|string|max:255',
            'metadata' => 'nullable|array',
        ]);

        return $this->ok($this->members->create($data), 'Created', 201);
    }

    public function show(Member $member)
    {
        return $this->ok($member);
    }

    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'member_number' => 'nullable|string|max:255',
            'card_status' => 'nullable|string|max:255',
            'metadata' => 'nullable|array',
        ]);

        return $this->ok($this->members->update($member, $data));
    }

    public function destroy(Member $member)
    {
        $this->members->delete($member);

        return $this->ok(null, 'Deleted');
    }
}
