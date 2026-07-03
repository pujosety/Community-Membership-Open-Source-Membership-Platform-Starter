<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;

class ProfileController extends ApiController
{
    public function __construct(
        private UserRepository $users,
        private MemberRepository $members
    ) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'member_number' => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        if (isset($data['name'])) {
            $this->users->update($user, ['name' => $data['name']]);
        }

        if (isset($data['member_number'])) {
            $this->members->updateForUser($user, [
                'member_number' => $data['member_number'],
            ]);
        }

        return $this->ok($user->load('member'), 'Profile updated');
    }
}
