<?php

namespace App\Policies;

use App\Models\LinkedMessenger;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LinkedMessengerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LinkedMessenger $linkedMessenger): bool
    {
        return $linkedMessenger->user_id === $user->getKey();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LinkedMessenger $linkedMessenger): bool
    {
        return $linkedMessenger->user_id === $user->getKey();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LinkedMessenger $linkedMessenger): bool
    {
        return $linkedMessenger->user_id === $user->getKey();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LinkedMessenger $linkedMessenger): bool
    {
        return $linkedMessenger->user_id === $user->getKey();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LinkedMessenger $linkedMessenger): bool
    {
        return $linkedMessenger->user_id === $user->getKey();
    }
}
