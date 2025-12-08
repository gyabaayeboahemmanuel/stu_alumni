<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Business;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // Public directory
    }

    public function view(User $user, Business $business)
    {
        return true; // Public directory
    }

    public function create(User $user)
    {
        return $user->alumni !== null && $user->alumni->verification_status === 'verified';
    }

    public function update(User $user, Business $business)
    {
        return $user->alumni && $user->alumni->id === $business->alumni_id;
    }

    public function delete(User $user, Business $business)
    {
        return $user->alumni && $user->alumni->id === $business->alumni_id;
    }

    public function manage(User $user)
    {
        return $user->isAdmin();
    }

    public function verify(User $user)
    {
        return $user->isAdmin();
    }
}
