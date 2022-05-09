<?php namespace Ivacuum\Generic\Policies;

use App\User;

class WithoutCreateAndEdit extends Base
{
    public function create(User $me)
    {
        return false;
    }

    /** @deprecated use update */
    public function edit(User $me)
    {
        return false;
    }

    public function update(User $me)
    {
        return false;
    }
}
