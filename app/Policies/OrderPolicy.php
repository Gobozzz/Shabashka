<?php

namespace App\Policies;

use App\Actions\User\IsUserActive;
use App\Enums\RolesUser;
use App\Enums\StatusOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
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
    public function view(User $user, Order $order): bool
    {
        if ($user && $order->employer_id === $user?->employer?->getKey()) { // хозяин объявления
            return true;
        }
        if ($order->status->value === StatusOrder::ACTIVE->value && (new IsUserActive())->handle($order->employer->user)) {
            return true;
        }
        return false;
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
    public function update(User $user, Order $order): bool
    {
        return $order->employer_id === $user->employer->getKey() && in_array($order->status, [StatusOrder::ACTIVE, StatusOrder::MODERATION]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return $order->employer_id === $user->employer->getKey();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        return $order->employer_id === $user->employer->getKey();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return $order->employer_id === $user->employer->getKey();
    }

    /**
     * Can freezing order
     */
    public function freez(User $user, Order $order): bool
    {
        return $order->employer_id === $user->employer->getKey() && in_array($order->status, [StatusOrder::ACTIVE, StatusOrder::MODERATION]);
    }

    /**
     * Can unfreezing order
     */
    public function unfreez(User $user, Order $order): bool
    {
        return $order->employer_id === $user->employer->getKey() && $order->status->value === StatusOrder::FREEZ->value;
    }

    /**
     * Its My Order
     */
    public function my_order(User $user, Order $order): bool
    {
        return $order->employer_id === $user->employer->getKey();
    }

}
