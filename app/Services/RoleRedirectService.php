<?php

namespace App\Services;

use App\Models\User;

class RoleRedirectService
{
    /**
     * Determine the appropriate dashboard route for a given user based on their role.
     *
     * Owner and Super Admin → /dashboard
     * All other roles → /employee/dashboard
     */
    public function getDashboardRoute(User $user): string
    {
        $adminRoles = config('roles.admin_roles', ['Owner', 'Super Admin']);

        if ($user->role && in_array($user->role->name, $adminRoles)) {
            return route('dashboard');
        }

        return route('employee.dashboard');
    }

    /**
     * Check if a given user has an admin-level role.
     */
    public function isAdminRole(User $user): bool
    {
        $adminRoles = config('roles.admin_roles', ['Owner', 'Super Admin']);

        return $user->role && in_array($user->role->name, $adminRoles);
    }
}
