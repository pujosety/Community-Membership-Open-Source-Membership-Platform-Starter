<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    public function __construct(private UserRepository $users) {}

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = $this->users->create($data);
        $token = $user->createToken('app')->plainTextToken;

        return $this->ok([
            'user' => $user,
            'token' => $token,
        ], 'Registered');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($data)) {
            return $this->fail('Invalid credentials', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('app')->plainTextToken;

        return $this->ok([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->ok(null, 'Logged out');
    }

    public function me(Request $request)
    {
        return $this->ok($request->user());
    }
}
