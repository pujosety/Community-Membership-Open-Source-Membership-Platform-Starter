<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function __construct(private UserRepository $users) {}

    public function index()
    {
        return $this->ok($this->users->all());
    }

    public function show(User $user)
    {
        return $this->ok($user->load('member'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
        ]);

        return $this->ok($this->users->update($user, $data));
    }
}
