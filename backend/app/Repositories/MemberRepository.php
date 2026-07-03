<?php

namespace App\Repositories;

use App\Models\Member;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MemberRepository
{
    public function paginated(\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard $auth): LengthAwarePaginator
    {
        return Member::query()
            ->with('user')
            ->when($auth->check() && $auth->user()->role !== 'superadmin', function ($q) use ($auth) {
                $q->where('user_id', $auth->id());
            })
            ->paginate(20);
    }

    public function create(array $data): Member
    {
        return Member::create($data);
    }

    public function update(Member $member, array $data): Member
    {
        $member->update($data);

        return $member->refresh();
    }

    public function updateForUser(User $user, array $data): Member
    {
        $member = $user->member ?: new Member(['user_id' => $user->id]);

        $member->fill($data);

        return $member->save() ? $member->refresh() : $member;
    }

    public function delete(Member $member): void
    {
        $member->delete();
    }
}
