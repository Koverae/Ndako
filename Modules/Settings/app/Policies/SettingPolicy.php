<?php

namespace Modules\Settings\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    public function access(User $user): bool
    {
        return $user->can('access_settings');
    }

    public function update(User $user): bool
    {
        return $user->can('modify_settings');
    }

    public function manageIntegrations(User $user): bool
    {
        return $user->can('manage_integrations');
    }

    public function manageSubscription(User $user): bool
    {
        return $user->can('manage_kover_subscription');
    }

    public function inviteUsers(User $user): bool
    {
        return $user->can('invite_users');
    }
}
