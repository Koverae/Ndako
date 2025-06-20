<?php
namespace Modules\Settings\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user list or user detail.
     */
    public function view(User $authUser, ?User $targetUser = null): bool
    {
        return $authUser->can('view_users');
    }

    /**
     * Determine whether the user can create new users.
     */
    public function create(User $authUser): bool
    {
        return $authUser->can('invite_users');
    }

    /**
     * Determine whether the user can update another user.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        return $authUser->can('manage_staff');
    }

    /**
     * Determine whether the user can assign roles.
     */
    public function assignRoles(User $authUser, ?User $targetUser = null): bool
    {
        return $authUser->can('manage_roles');
    }

    /**
     * Determine whether the user can assign individual permissions.
     */
    public function assignPermissions(User $authUser, ?User $targetUser = null): bool
    {
        return $authUser->can('manage_roles');
    }

    /**
     * Determine whether the user can delete users.
     */
    public function delete(User $authUser, User $targetUser): bool
    {
        return $authUser->can('manage_staff');
    }
}
