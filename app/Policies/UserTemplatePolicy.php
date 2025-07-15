<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserTemplate;
use Illuminate\Auth\Access\Response;

class UserTemplatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage-templates');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserTemplate $userTemplate): bool
    {
        return $user->id === $userTemplate->user_id || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage-templates');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserTemplate $userTemplate): bool
    {
        return $user->id === $userTemplate->user_id || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserTemplate $userTemplate): bool
    {
        return ($user->id === $userTemplate->user_id || $user->hasRole('super-admin')) 
            && !$userTemplate->is_default;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserTemplate $userTemplate): bool
    {
        return $user->id === $userTemplate->user_id || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserTemplate $userTemplate): bool
    {
        return $user->hasRole('super-admin');
    }
}
