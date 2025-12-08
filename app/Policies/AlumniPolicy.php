<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Alumni;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlumniPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    public function view(User $user, Alumni $alumni)
    {
        return $user->isAdmin() || $user->alumni->id === $alumni->id;
    }

    public function create(User $user)
    {
        return false; // Alumni are created through registration process
    }

    public function update(User $user, Alumni $alumni)
    {
        return $user->isAdmin() || $user->alumni->id === $alumni->id;
    }

    public function delete(User $user, Alumni $alumni)
    {
        return $user->isAdmin();
    }

    public function verify(User $user)
    {
        return $user->isAdmin() || $user->role->name === \App\Models\Role::VERIFICATION_OFFICER;
    }

    public function manage(User $user)
    {
        return $user->isAdmin();
    }
}
