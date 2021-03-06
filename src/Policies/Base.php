<?php namespace Ivacuum\Generic\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Base
{
    use HandlesAuthorization;

    public function create(User $me)
    {
        return $me->isRoot();
    }

    public function delete(User $me)
    {
        return $me->isRoot();
    }

    public function destroy(User $me)
    {
        return $me->isRoot();
    }

    public function edit(User $me)
    {
        return $me->isRoot();
    }

    public function list(User $me)
    {
        return $me->isRoot();
    }

    public function show(User $me)
    {
        return $me->isRoot();
    }

    public function update(User $me)
    {
        return $me->isRoot();
    }

    public function view(User $me)
    {
        return $me->isRoot();
    }

    public function viewAny(User $me)
    {
        return $me->isRoot();
    }
}
