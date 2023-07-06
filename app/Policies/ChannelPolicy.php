<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChannelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return in_array(Role::SUPER_ADMIN, $user->roles()->pluck('roles.id')->toArray())
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Channel $channel): Response
    {
        return in_array(Role::SUPER_ADMIN, $user->roles()->pluck('roles.id')->toArray())
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return in_array(Role::SUPER_ADMIN, $user->roles()->pluck('roles.id')->toArray())
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Channel $channel): Response
    {
        return in_array(Role::SUPER_ADMIN, $user->roles()->pluck('roles.id')->toArray())
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Channel $channel): Response
    {
        return in_array(Role::SUPER_ADMIN, $user->roles()->pluck('roles.id')->toArray())
            ? Response::allow()
            : Response::deny();
    }
}
