<?php

namespace Ivacuum\Generic\Policies;

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

    /** @deprecated use delete */
    public function destroy(User $me)
    {
        return $me->isRoot();
    }

    /** @deprecated use update */
    public function edit(User $me)
    {
        return $me->isRoot();
    }

    /** @deprecated use viewAny */
    public function list(User $me)
    {
        return $me->isRoot();
    }

    /** @deprecated use view */
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
